<script setup lang="ts">
import { ref } from 'vue'
import { Icon } from '@iconify/vue'
import { useExperience } from '@/composables/useApi'
import { useReveal } from '@/composables/useReveal'

const { data } = useExperience()
const root = ref<HTMLElement | null>(null)
useReveal(root, { threshold: 0.08 })

function fmt(d: string | null): string {
  if (!d) return 'Present'
  // Parse YYYY-MM-DD as local calendar date to avoid UTC off-by-one
  const m = /^(\d{4})-(\d{2})-(\d{2})/.exec(d)
  const date = m
    ? new Date(Number(m[1]), Number(m[2]) - 1, Number(m[3]))
    : new Date(d)
  return date.toLocaleDateString('en', { year: 'numeric', month: 'short' })
}
</script>

<template>
  <section id="experience" ref="root" class="section relative">
    <div class="container-x max-w-3xl">
      <p class="reveal eyebrow text-accent">Career &amp; Education</p>
      <h2 class="reveal mt-4 font-display text-[clamp(33px,5.4vw,62px)] font-semibold tracking-tighter2" style="transition-delay:.08s">
        Work experience
      </h2>

      <ol class="relative mt-14">
        <span class="absolute bottom-2 left-[7px] top-2 w-px bg-line/15" aria-hidden="true"></span>
        <li
          v-for="(e, i) in (data ?? [])" :key="e.id"
          class="group relative pb-12 pl-10 last:pb-0 reveal"
          :style="`transition-delay:${0.1 + i * 0.07}s`"
        >
          <span
            class="absolute left-0 top-1.5 grid h-4 w-4 place-items-center rounded-full border-2 border-line/25 bg-bg transition-all duration-300 group-hover:border-accent group-hover:shadow-[0_0_0_5px_rgb(var(--glow-rgb)/0.12)]"
          >
            <span class="h-1.5 w-1.5 rounded-full bg-transparent transition-colors duration-300 group-hover:bg-accent"></span>
          </span>

          <p class="font-mono text-[12px] uppercase tracking-wide2 text-text3 transition-colors group-hover:text-accent">
            {{ fmt(e.start_date) }} — {{ fmt(e.end_date) }}<span v-if="e.location"> · {{ e.location }}</span>
          </p>
          <h3 class="mt-1.5 font-display text-2xl text-text2 transition-colors group-hover:text-text">
            {{ e.role }} <span class="text-accent">· {{ e.company }}</span>
          </h3>
          <p v-if="e.description" class="mt-2 leading-relaxed text-text3 transition-colors group-hover:text-text2">
            {{ e.description }}
          </p>
          <ul v-if="e.achievements && e.achievements.length" class="mt-3 space-y-1.5">
            <li v-for="a in e.achievements" :key="a" class="flex gap-2.5 text-sm text-text2">
              <Icon icon="lucide:check" class="mt-0.5 h-4 w-4 shrink-0 text-accent" /> {{ a }}
            </li>
          </ul>
        </li>
      </ol>
    </div>
  </section>
</template>
