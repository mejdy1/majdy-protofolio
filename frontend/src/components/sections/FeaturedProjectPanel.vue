<script setup lang="ts">
import { ref, computed } from 'vue'
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

const rolePoints = computed<string[]>(
  () => p.value.role_points ?? [],
)

const root = ref<HTMLElement | null>(null)
useReveal(root, { threshold: 0.08 })
</script>

<template>
  <section ref="root" class="relative py-20 md:py-28">
    <div class="container-x">
      <!-- ============================================================
           PROJECT HEADER & TITLE
           ============================================================ -->
      <header class="reveal max-w-4xl">
        <!-- Category tags aligned right above the title block -->
        <div class="flex flex-wrap items-center gap-3">
          <span
            v-if="p.category"
            class="inline-flex items-center rounded-full border border-accent/35 bg-accent/10 px-3 py-1 font-mono text-[12px] uppercase tracking-wide2 text-accent"
          >
            {{ p.category }}
          </span>
          <span
            v-if="p.year"
            class="font-mono text-[12px] uppercase tracking-wide2 text-text3"
          >
            {{ p.year }}
          </span>
        </div>

        <!-- Inline logo directly to the left of the main Project Title -->
        <div class="mt-5 flex items-center gap-4">
          <img
            v-if="p.logo_url"
            :src="p.logo_url"
            :alt="`${p.title} logo`"
            class="h-12 w-12 shrink-0 rounded-xl object-contain object-center drop-shadow-[0_0_18px_rgb(var(--glow-rgb)/0.35)]"
            loading="lazy"
          />
          <h3 class="font-display text-[clamp(32px,5vw,56px)] font-semibold leading-[1.05] tracking-tighter2">
            {{ p.title }}
          </h3>
        </div>
        <p class="mt-4 max-w-2xl text-lg leading-relaxed text-text2">
          {{ p.summary }}
        </p>
      </header>

      <!-- ============================================================
           BODY — consistent two-column layout across ALL case studies:
           01. Problem + 02. Solution stacked on the LEFT,
           03. My Role anchored on the RIGHT (never reordered).
           ============================================================ -->
      <div class="mt-12 grid gap-10 lg:grid-cols-[1.4fr_1fr] lg:gap-12">
        <!-- Overview: Problem & Solution -->
        <div class="reveal space-y-8">
          <div v-if="p.problem">
            <h4 class="font-display text-2xl font-semibold tracking-tight text-text">
              <span class="text-accent">01.</span> Problem
            </h4>
            <div class="mt-3 rich" v-html="p.problem"></div>
          </div>
          <div v-else-if="p.summary">
            <h4 class="font-display text-2xl font-semibold tracking-tight text-text">
              <span class="text-accent">01.</span> Overview
            </h4>
            <p class="mt-3 rich">{{ p.summary }}</p>
          </div>

          <div v-if="p.solution">
            <h4 class="font-display text-2xl font-semibold tracking-tight text-text">
              <span class="text-accent">02.</span> Solution
            </h4>
            <div class="mt-3 rich" v-html="p.solution"></div>
          </div>

          <!-- Timeline metadata kept inline under the solution, no card -->
          <p
            v-if="p.timeline || p.client"
            class="font-mono text-[13px] uppercase tracking-wide2 text-text3"
          >
            {{ p.timeline || p.client }}
          </p>
        </div>

        <!-- My Role — dedicated section with bullet points + Tools & Skills -->
        <aside class="reveal rounded-2xl border border-line/14 bg-surface/55 p-6 lg:p-7">
          <h4 class="font-display text-2xl font-semibold tracking-tight text-text">
            <span class="text-accent">03.</span> My Role
          </h4>
          <p v-if="p.role" class="mt-2 font-mono text-[13px] uppercase tracking-wide2 text-accent">
            {{ p.role }}
          </p>

          <ul v-if="rolePoints.length" class="mt-5 space-y-3">
            <li
              v-for="point in rolePoints"
              :key="point"
              class="flex gap-3 text-base leading-relaxed text-text2"
            >
              <Icon icon="lucide:check" class="mt-1 h-4 w-4 shrink-0 text-accent" />
              <span>{{ point }}</span>
            </li>
          </ul>
          <p v-else-if="p.role" class="mt-4 text-base leading-relaxed text-text2">
            {{ p.role }}
          </p>

          <!-- Tools & Skills pill badges moved into the My Role card -->
          <div v-if="p.stack && p.stack.length" class="mt-6 border-t border-line/12 pt-5">
            <p class="font-mono text-[12px] uppercase tracking-wide2 text-text3">Tools &amp; Skills</p>
            <div class="mt-3 flex flex-wrap gap-1.5">
              <span
                v-for="s in p.stack"
                :key="s"
                class="chip !text-[12px]"
              >{{ s }}</span>
            </div>
          </div>
        </aside>
      </div>

      <!-- ============================================================
           IMAGE GALLERY — clean responsive grid (no 3D / drag effects)
           Uses object-contain + dynamic aspect ratio so wide and narrow
           UI screenshots render completely without clipping.
           ============================================================ -->
      <div v-if="images.length" class="reveal mt-14">
        <div
          class="grid gap-4"
          :class="images.length === 1 ? 'sm:grid-cols-1' : 'sm:grid-cols-2'"
        >
          <figure
            v-for="(src, i) in images"
            :key="src"
            class="overflow-hidden rounded-2xl border border-line/14 bg-surface/55 p-3"
          >
            <!-- dynamic aspect ratio: wide screens use 16/10, narrows stack at full width -->
            <div class="aspect-[16/10] w-full overflow-hidden rounded-xl bg-bg/60">
              <img
                :src="src"
                :alt="`${p.title} mockup ${i + 1}`"
                loading="lazy"
                draggable="false"
                class="h-full w-full object-contain"
              />
            </div>
          </figure>
        </div>
      </div>

      <!-- ============================================================
           LINKS
           ============================================================ -->
      <div v-if="p.live_url || p.repo_url" class="reveal mt-10 flex flex-wrap gap-6">
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
  </section>
</template>
