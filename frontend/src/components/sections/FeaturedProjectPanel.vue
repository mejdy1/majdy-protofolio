<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Icon } from '@iconify/vue'
import { useProject } from '@/composables/useApi'
import { useReveal } from '@/composables/useReveal'
import type { Project } from '@/types/api'

const props = defineProps<{ slug: string; fallback: Project; index: number; flip?: boolean }>()

const { data: detail } = useProject(props.slug)
const p = computed<Project>(() => detail.value ?? props.fallback)

const images = computed(() => {
  const list = [p.value.cover_url, ...(p.value.gallery ?? [])].filter(
    (src): src is string => Boolean(src),
  )
  return [...new Set(list)]
})

const active = ref(0)
watch(images, () => { active.value = 0 })

const hasGallery = computed(() => images.value.length > 1)

function go(delta: number) {
  const total = images.value.length
  if (total < 2) return
  active.value = (active.value + delta + total) % total
}

function select(i: number) {
  if (i === active.value) return
  active.value = i
}

/** Shortest signed circular offset from active → i in (-n/2, n/2]. */
function circularOffset(i: number): number {
  const total = images.value.length
  if (total < 2) return 0
  let offset = i - active.value
  const half = total / 2
  if (offset > half) offset -= total
  if (offset < -half) offset += total
  // Even counts: map the exact opposite onto +side so only one peek shows (n=2)
  if (total % 2 === 0 && offset === -half) offset = half
  return offset
}

function slotClass(i: number): string {
  if (!hasGallery.value) return 'is-center'
  const offset = circularOffset(i)
  if (offset === 0) return 'is-center'
  if (offset === -1) return 'is-left'
  if (offset === 1) return 'is-right'
  return 'is-hidden'
}

let hoverTimer: ReturnType<typeof setTimeout> | null = null

function onSideEnter(i: number) {
  if (!hasGallery.value || i === active.value) return
  const offset = circularOffset(i)
  if (offset !== -1 && offset !== 1) return
  if (hoverTimer) clearTimeout(hoverTimer)
  hoverTimer = setTimeout(() => {
    select(i)
    hoverTimer = null
  }, 140)
}

function onSideLeave() {
  if (hoverTimer) {
    clearTimeout(hoverTimer)
    hoverTimer = null
  }
}

const pointerStartX = ref<number | null>(null)
const didSwipe = ref(false)

function onPointerDown(e: PointerEvent) {
  if (!hasGallery.value) return
  if (e.pointerType === 'mouse' && e.button !== 0) return
  pointerStartX.value = e.clientX
  didSwipe.value = false
}

function onPointerUp(e: PointerEvent) {
  if (pointerStartX.value == null) return
  const dx = e.clientX - pointerStartX.value
  pointerStartX.value = null
  if (Math.abs(dx) < 42) return
  didSwipe.value = true
  go(dx < 0 ? 1 : -1)
}

function onPointerCancel() {
  pointerStartX.value = null
}

function onCardClick(i: number) {
  if (didSwipe.value) {
    didSwipe.value = false
    return
  }
  select(i)
}

const root = ref<HTMLElement | null>(null)
useReveal(root, { threshold: 0.12 })
</script>

<template>
  <section ref="root" class="relative flex min-h-screen items-center py-20">
    <div class="container-x grid w-full items-center gap-10 lg:grid-cols-2 lg:gap-16">
      <!-- visual gallery — 3D center-focused card stack -->
      <div
        class="reveal"
        :class="flip ? 'lg:order-2' : 'lg:order-1'"
        style="transition-delay:.05s"
      >
        <div
          class="stack-stage relative mx-auto w-full max-w-xl select-none px-9 py-6 sm:px-14 sm:py-8"
          :class="hasGallery ? 'cursor-grab active:cursor-grabbing' : ''"
          role="region"
          :aria-roledescription="hasGallery ? 'carousel' : undefined"
          :aria-label="`${p.title} mockups`"
          :tabindex="hasGallery ? 0 : -1"
          @keydown.left.prevent="go(-1)"
          @keydown.right.prevent="go(1)"
          @pointerdown="onPointerDown"
          @pointerup="onPointerUp"
          @pointercancel="onPointerCancel"
        >
          <div class="relative aspect-[16/11] w-full [perspective:1200px]">
            <template v-if="images.length">
              <button
                v-for="(src, i) in images"
                :key="src"
                type="button"
                class="stack-card absolute inset-0 overflow-hidden rounded-2xl border bg-surface/80 outline-none focus-visible:ring-2 focus-visible:ring-accent/70"
                :class="slotClass(i)"
                :aria-label="`Show mockup ${i + 1} of ${images.length}`"
                :aria-current="i === active ? 'true' : undefined"
                :tabindex="i === active || !hasGallery ? -1 : 0"
                @click="onCardClick(i)"
                @mouseenter="onSideEnter(i)"
                @mouseleave="onSideLeave"
              >
                <img
                  :src="src"
                  :alt="`${p.title} mockup ${i + 1}`"
                  loading="lazy"
                  draggable="false"
                  class="pointer-events-none h-full w-full object-cover"
                />
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-bg/35 to-transparent"></div>
              </button>
            </template>
          </div>
        </div>
      </div>

      <!-- copy -->
      <div class="reveal" :class="flip ? 'lg:order-1' : 'lg:order-2'" style="transition-delay:.15s">
        <div v-if="p.logo_url" class="mb-5 inline-flex h-12 items-center">
          <img
            :src="p.logo_url"
            :alt="`${p.title} logo`"
            class="h-10 w-auto max-w-[160px] object-contain object-left drop-shadow-[0_0_18px_rgb(var(--glow-rgb)/0.35)]"
            loading="lazy"
          />
        </div>
        <p v-if="p.client" class="eyebrow text-accent">{{ p.client }}</p>
        <h3 class="mt-4 font-display text-[clamp(31px,4.8vw,58px)] font-semibold leading-[1.04] tracking-tighter2">
          {{ p.title }}
        </h3>

        <dl class="mt-8 space-y-6">
          <div v-if="p.problem">
            <dt class="font-mono text-[11px] uppercase tracking-wide2 text-accent">Problem</dt>
            <dd class="mt-2 rich" v-html="p.problem"></dd>
          </div>
          <div v-else>
            <dt class="font-mono text-[11px] uppercase tracking-wide2 text-accent">Overview</dt>
            <dd class="mt-2 rich">{{ p.summary }}</dd>
          </div>
          <div v-if="p.solution">
            <dt class="font-mono text-[11px] uppercase tracking-wide2 text-accent">Solution</dt>
            <dd class="mt-2 rich" v-html="p.solution"></dd>
          </div>
          <div v-if="p.role">
            <dt class="font-mono text-[11px] uppercase tracking-wide2 text-accent">My Role</dt>
            <dd class="mt-2 rich">{{ p.role }}</dd>
          </div>
        </dl>

        <div v-if="p.stack && p.stack.length" class="mt-7 flex flex-wrap gap-2">
          <span v-for="s in p.stack" :key="s" class="chip">{{ s }}</span>
        </div>

        <div class="mt-8 flex flex-wrap gap-6">
          <a
            v-if="p.live_url" :href="p.live_url" target="_blank" rel="noopener"
            class="inline-flex items-center gap-1.5 font-medium text-accent transition-all duration-300 hover:gap-2.5"
          >
            Live demo <Icon icon="lucide:arrow-up-right" class="h-4 w-4" />
          </a>
          <a
            v-if="p.repo_url" :href="p.repo_url" target="_blank" rel="noopener"
            class="inline-flex items-center gap-1.5 text-text2 transition-colors hover:text-accent"
          >
            <Icon icon="lucide:github" class="h-4 w-4" /> Source
          </a>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.stack-card {
  transform-style: preserve-3d;
  backface-visibility: hidden;
  will-change: transform, opacity;
  transition:
    transform 500ms var(--ease-soft),
    opacity 500ms var(--ease-soft),
    border-color 500ms var(--ease-soft),
    box-shadow 500ms var(--ease-soft);
  border-color: rgb(var(--line) / 0.14);
  box-shadow: none;
  opacity: 0;
  pointer-events: none;
  z-index: 0;
  transform: translate3d(0, 6%, -90px) scale(0.62);
}

.stack-card.is-center {
  opacity: 1;
  pointer-events: auto;
  z-index: 20;
  border-color: rgb(var(--accent) / 0.6);
  box-shadow:
    0 0 0 1px rgb(var(--accent) / 0.18),
    0 0 28px rgb(var(--glow-rgb) / 0.35),
    0 0 64px -10px rgb(var(--glow-rgb) / 0.28),
    0 18px 40px rgb(0 0 0 / 0.35);
  transform: translate3d(0, 0, 0) scale(1) rotateY(0deg);
}

.stack-card.is-left {
  opacity: 0.6;
  pointer-events: auto;
  z-index: 10;
  border-color: rgb(var(--line) / 0.18);
  transform: translate3d(-24%, 3%, -70px) scale(0.78) rotateY(14deg);
}

.stack-card.is-right {
  opacity: 0.6;
  pointer-events: auto;
  z-index: 10;
  border-color: rgb(var(--line) / 0.18);
  transform: translate3d(24%, 3%, -70px) scale(0.78) rotateY(-14deg);
}

.stack-card.is-hidden {
  opacity: 0;
  pointer-events: none;
  z-index: 0;
  transform: translate3d(0, 6%, -90px) scale(0.62);
}

.stack-card.is-left:hover,
.stack-card.is-right:hover {
  opacity: 0.85;
  border-color: rgb(var(--accent) / 0.35);
}

.stack-stage:focus-visible {
  outline: 2px solid rgb(var(--accent));
  outline-offset: 6px;
  border-radius: 1rem;
}

@media (prefers-reduced-motion: reduce) {
  .stack-card {
    transition-duration: 0.001ms !important;
  }

  .stack-card.is-left {
    transform: translate3d(-18%, 0, 0) scale(0.8) rotateY(0deg);
  }

  .stack-card.is-right {
    transform: translate3d(18%, 0, 0) scale(0.8) rotateY(0deg);
  }

  .stack-card.is-hidden {
    transform: scale(0.75);
  }
}
</style>
