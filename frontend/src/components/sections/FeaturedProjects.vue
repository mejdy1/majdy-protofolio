<script setup lang="ts">
import { computed } from 'vue'
import { useProjects } from '@/composables/useApi'
import FeaturedProjectPanel from './FeaturedProjectPanel.vue'

const { data } = useProjects({ featured: true, limit: 2 })
const projects = computed(() => data.value ?? [])
</script>

<template>
  <div id="projects">
    <div class="container-x pt-24 md:pt-32">
      <p class="eyebrow text-accent">Featured Work</p>
      <h2 class="mt-4 max-w-3xl font-display text-[clamp(33px,5.4vw,64px)] font-semibold leading-tight tracking-tighter2">
        Two projects I’m most proud of.
      </h2>
    </div>

    <FeaturedProjectPanel
      v-for="(p, i) in projects"
      :key="p.id"
      :slug="p.slug"
      :fallback="p"
      :index="i"
      :flip="i % 2 === 1"
    />
  </div>
</template>
