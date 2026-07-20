<script setup lang="ts">
import { ref, computed } from 'vue'
import { Icon } from '@iconify/vue'
import GlowCard from '@/components/ui/GlowCard.vue'
import { useProjects } from '@/composables/useApi'
import { useReveal } from '@/composables/useReveal'

const { data } = useProjects({ featured: false })
const projects = computed(() => (data.value ?? []).filter((p) => !p.featured))
const root = ref<HTMLElement | null>(null)
useReveal(root, { threshold: 0.06 })
</script>

<template>
  <section ref="root" class="section relative">
    <div class="container-x">
      <p class="reveal eyebrow text-accent">More Builds</p>
      <h2 class="reveal mt-4 font-display text-[clamp(31px,5vw,58px)] font-semibold tracking-tighter2" style="transition-delay:.06s">
        Other projects
      </h2>

      <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <GlowCard
          v-for="(p, i) in projects" :key="p.id"
          class="reveal flex flex-col p-6"
          :style="`transition-delay:${0.06 + i * 0.05}s`"
        >
          <div class="flex items-start justify-between gap-3">
            <h3 class="font-display text-xl font-semibold leading-tight">{{ p.title }}</h3>
            <span v-if="p.year" class="shrink-0 font-mono text-xs text-text3">{{ p.year }}</span>
          </div>
          <p class="mt-3 flex-1 text-sm leading-relaxed text-text2">{{ p.summary }}</p>
          <div v-if="p.stack && p.stack.length" class="mt-5 flex flex-wrap gap-1.5">
            <span v-for="s in p.stack.slice(0, 4)" :key="s" class="chip !text-[11px]">{{ s }}</span>
          </div>
          <div v-if="p.live_url || p.repo_url" class="mt-5 flex gap-4 border-t border-line/10 pt-4">
            <a v-if="p.live_url" :href="p.live_url" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-sm text-accent transition-all hover:gap-1.5">
              Live <Icon icon="lucide:arrow-up-right" class="h-3.5 w-3.5" />
            </a>
            <a v-if="p.repo_url" :href="p.repo_url" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-sm text-text2 transition-colors hover:text-accent">
              <Icon icon="lucide:github" class="h-3.5 w-3.5" /> Code
            </a>
          </div>
        </GlowCard>
      </div>
    </div>
  </section>
</template>
