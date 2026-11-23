<script setup lang="ts">
import type { Concept } from '@/data/content'
import { openLightbox } from '@/composables/useLightbox'
import ProjectMark from '@/components/ui/ProjectMark.vue'

const props = defineProps<{ item: Concept; kind: 'web' | 'app' }>()
const chips = props.item.tags.split(' · ')
</script>

<template>
  <article :id="item.id" class="concept" :aria-labelledby="`${item.id}-title`">
    <div v-reveal class="glass concept__card">
      <div class="concept__name" data-stagger>
        <ProjectMark :logo="item.logo" />
        <h3 :id="`${item.id}-title`">{{ item.title }}</h3>
      </div>
      <div class="concept__about" data-stagger>
        <p>{{ item.summary }}</p>
        <ul class="chips" aria-label="Project type and tools">
          <li v-for="c in chips" :key="c">{{ c }}</li>
        </ul>
      </div>
    </div>
    <div v-reveal="1" class="shots" :class="`shots--${kind}`">
      <button
        v-for="(shot, i) in item.shots"
        :key="shot.src"
        type="button"
        class="shot selectable"
        data-stagger
        :aria-label="`Open full screen: ${shot.alt}`"
        @click="openLightbox(item.title, item.shots, i)"
      >
        <span class="shot__frame">
          <img
            :src="kind === 'app' ? shot.thumb : shot.src"
            :alt="shot.alt"
            :width="kind === 'app' ? 360 : shot.width"
            :height="kind === 'app' ? Math.round((shot.height * 360) / shot.width) : shot.height"
            loading="lazy"
            decoding="async"
          />
        </span>
      </button>
    </div>
  </article>
</template>
