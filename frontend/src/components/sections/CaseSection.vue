<script setup lang="ts">
import type { CaseStudy } from '@/data/content'
import Gallery from '@/components/ui/Gallery.vue'
import ProjectMark from '@/components/ui/ProjectMark.vue'

const props = defineProps<{ item: CaseStudy; paper?: boolean }>()
const headingId = `${props.item.id}-title`
</script>

<template>
  <section :id="item.id" class="case" :class="{ 'ground-paper': paper }" :aria-labelledby="headingId">
    <div class="wrap">
      <!-- split 5 / 7: the story left, the work right (on phones: story, work, then what I did) -->
      <div class="grid case__grid">
        <div class="case__col">
          <div v-reveal class="case__text">
            <p class="kicker" data-stagger>{{ item.kicker }}</p>
            <div class="case__title" data-stagger>
              <ProjectMark :logo="item.logo" />
              <h2 :id="headingId">{{ item.title }}</h2>
            </div>
            <p v-if="item.award" class="award" data-stagger>
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 4h8v5a4 4 0 0 1-8 0V4Z" /><path d="M8 6H5v1a3 3 0 0 0 3 3M16 6h3v1a3 3 0 0 1-3 3M12 13v4M8.5 20h7M10 17h4" /></svg>
              <span>{{ item.award }}</span>
            </p>
            <div class="glass case__card" data-stagger>
              <p class="case__summary">{{ item.summary }}</p>
              <dl class="facts">
                <div v-for="f in item.facts" :key="f.label">
                  <dt>{{ f.label }}</dt>
                  <dd>{{ f.value }}</dd>
                </div>
              </dl>
            </div>
          </div>
          <div v-reveal="2" class="case__more">
            <p class="case__sub">What I did</p>
            <ul class="did">
              <li v-for="(line, i) in item.did" :key="line" class="glass" data-stagger>
                <span class="did__no num" aria-hidden="true">0{{ i + 1 }}</span>
                <span>{{ line }}</span>
              </li>
            </ul>
          </div>
        </div>
        <div v-reveal="1" class="case__visual">
          <Gallery :title="item.title" :shots="item.shots" />
        </div>
      </div>
    </div>
  </section>
</template>
