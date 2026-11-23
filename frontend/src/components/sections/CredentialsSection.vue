<script setup lang="ts">
import { credentials } from '@/data/content'
import { openLightbox } from '@/composables/useLightbox'
import type { Shot } from '@/data/content'

const shots: Shot[] = credentials.featured.map((c) => ({
  src: c.preview!,
  thumb: c.preview!,
  alt: `${c.title} certificate, ${c.issuer}, ${c.date}`,
  width: c.width ?? 1400,
  height: c.height ?? 1082,
}))
</script>

<template>
  <section :id="credentials.id" aria-labelledby="credentials-title">
    <div class="wrap">
      <div v-reveal class="section-head">
        <h2 id="credentials-title">{{ credentials.title }}</h2>
      </div>
      <!-- the degree and three certificates in one row, the Google course list under them -->
      <div v-reveal="1" class="grid certs">
        <article v-for="(c, i) in credentials.featured" :key="c.verify" class="cert glass" data-stagger>
          <button
            type="button"
            class="cert__open selectable"
            :aria-label="`Open full screen: ${c.title} certificate`"
            @click="openLightbox('Certificates', shots, i)"
          >
            <img :src="c.preview" :alt="shots[i].alt" :width="shots[i].width" :height="shots[i].height" loading="lazy" decoding="async" />
          </button>
          <h3>{{ c.title }}</h3>
          <p class="cert__meta">{{ c.issuer }}<br />{{ c.date }}</p>
          <a v-if="c.verify" class="link" :href="c.verify" target="_blank" rel="noopener">Verify on Coursera</a>
        </article>
        <div class="courses glass" data-stagger>
          <p class="courses__label">The seven Google courses include</p>
          <ol>
            <li v-for="c in credentials.courses" :key="c.verify">
              <a :href="c.verify" target="_blank" rel="noopener">{{ c.title }}<span class="num">{{ c.date }}</span></a>
            </li>
          </ol>
        </div>
      </div>
    </div>
  </section>
</template>
