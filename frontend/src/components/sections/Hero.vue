<script setup lang="ts">
import { ref } from 'vue'
import { Icon } from '@iconify/vue'
import { useProfile } from '@/composables/useApi'
import { useReveal } from '@/composables/useReveal'
import { getLenis } from '@/composables/useLenis'

const { data: profile } = useProfile()
const root = ref<HTMLElement | null>(null)
useReveal(root, { threshold: 0.05 })

function toContact() {
  const el = document.getElementById('contact')
  if (!el) return
  const lenis = getLenis()
  if (lenis) lenis.scrollTo(el, { offset: -40 })
  else el.scrollIntoView({ behavior: 'smooth' })
}
</script>

<template>
  <section id="top" ref="root" class="relative isolate flex min-h-screen items-center overflow-hidden">
    <div class="hero-bloom" aria-hidden="true"></div>

    <div class="container-x w-full pt-20">
      <!-- L1: context meta -->
      <div
        class="reveal hero-meta flex flex-wrap items-center gap-x-3 gap-y-2 text-[15px] sm:text-base"
        style="transition-delay:.05s"
      >
        <span class="inline-flex items-center gap-3 font-mono text-[13px] font-medium uppercase tracking-[0.18em] text-white sm:text-[14px]">
          <span class="h-px w-6 bg-white/55" aria-hidden="true"></span>
          {{ profile?.location ?? 'Makkah, Saudi Arabia' }}
        </span>
        <span
          v-if="profile?.available_for_hire"
          class="inline-flex items-center gap-2 rounded-full border border-accent/35 bg-accent/10 px-3 py-1 text-[13px] font-semibold text-accent sm:text-[14px]"
        >
          <span class="h-1.5 w-1.5 rounded-full bg-accent shadow-[0_0_10px_rgb(var(--glow-rgb)/0.95)]"></span>
          Available for hire
        </span>
      </div>

      <!-- L2: primary identity -->
      <h1
        class="reveal mt-6 font-display text-[clamp(46px,9vw,120px)] font-semibold leading-[0.94] tracking-tighter2"
        style="transition-delay:.12s"
      >
        {{ profile?.name ?? 'Majdi Al-Kediwi' }}
      </h1>

      <!-- L3: role -->
      <p
        class="reveal mt-4 font-display text-[clamp(22px,3.8vw,44px)] font-medium leading-tight text-accent"
        style="transition-delay:.18s"
      >
        {{ profile?.headline ?? 'Computer Science Graduate · Software Engineer' }}
      </p>

      <!-- L4: supporting pitch -->
      <p class="reveal mt-6 max-w-2xl text-lg leading-relaxed text-text2" style="transition-delay:.26s">
        {{ profile?.tagline }}
      </p>

      <!-- L5: actions -->
      <div class="reveal mt-10 flex flex-wrap items-center gap-4" style="transition-delay:.34s">
        <button @click="toContact" class="btn-primary">
          <Icon icon="lucide:mail" class="h-[18px] w-[18px]" /> Contact Me
        </button>
        <a v-if="profile?.resume_url" :href="profile.resume_url" download class="btn-ghost">
          <Icon icon="lucide:download" class="h-[18px] w-[18px]" /> Download CV
        </a>
      </div>
    </div>

    <button
      @click="toContact"
      class="absolute bottom-8 left-1/2 -translate-x-1/2 text-text3 transition-colors hover:text-accent"
      aria-label="Scroll down"
    >
      <Icon icon="lucide:chevron-down" class="h-6 w-6 animate-scroll-hint" />
    </button>
  </section>
</template>

<style scoped>
.hero-meta {
  text-shadow:
    0 1px 0 rgb(var(--bg) / 0.55),
    0 0 18px rgb(var(--bg) / 0.65),
    0 0 28px rgb(var(--glow-rgb) / 0.18);
}

.hero-bloom {
  position: absolute;
  inset: -12% -10% auto -10%;
  height: 125%;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(42% 50% at 26% 26%, rgb(var(--glow-rgb) / calc(var(--glow-ambient) * 1.25)), transparent 70%),
    radial-gradient(40% 46% at 78% 40%, rgb(var(--glow-rgb) / calc(var(--glow-ambient) * 0.85)), transparent 70%);
  filter: blur(40px);
}
</style>
