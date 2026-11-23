<script setup lang="ts">
import { concepts } from '@/data/content'
import ConceptBlock from './ConceptBlock.vue'

const [web, app] = concepts.items
</script>

<template>
  <section :id="concepts.id" class="g2" aria-labelledby="concepts-title">
    <div class="wrap">
      <div>
        <div v-reveal class="section-head">
          <p class="kicker" data-stagger>{{ concepts.kicker }}</p>
          <h2 id="concepts-title" data-stagger>{{ concepts.title }} <span class="muted">{{ concepts.titleMuted }}</span></h2>
        </div>
        <ConceptBlock :item="web" kind="web" />
      </div>
      <ConceptBlock :item="app" kind="app" />
    </div>
  </section>
</template>
