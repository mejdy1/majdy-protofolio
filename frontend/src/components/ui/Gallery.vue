<script setup lang="ts">
import { computed, ref } from 'vue'
import type { Shot } from '@/data/content'
import { openLightbox, useSwipe } from '@/composables/useLightbox'
import Glyph from './Glyph.vue'

const props = defineProps<{
  title: string
  shots: Shot[]
}>()

const index = ref(0)
const current = computed(() => props.shots[index.value])
const total = computed(() => props.shots.length)
const pad = (n: number) => String(n).padStart(2, '0')

function go(delta: number) {
  index.value = (index.value + delta + total.value) % total.value
}

const swipe = useSwipe(go)

function open() {
  if (swipe.wasSwipe()) return
  openLightbox(props.title, props.shots, index.value)
}

function onKey(e: KeyboardEvent) {
  if (e.key === 'ArrowRight') { e.preventDefault(); go(1) }
  else if (e.key === 'ArrowLeft') { e.preventDefault(); go(-1) }
}
</script>

<template>
  <div class="gallery" role="group" :aria-label="`${title}: ${total} images`" @keydown="onKey">
    <div class="gallery__stage" v-on="swipe.handlers">
      <button
        type="button"
        class="gallery__open"
        :aria-label="`Open image ${index + 1} of ${total} full screen: ${current.alt}`"
        @click="open"
      >
        <img
          v-for="(shot, i) in shots"
          :key="shot.src"
          :src="shot.src"
          :alt="i === index ? shot.alt : ''"
          :aria-hidden="i === index ? undefined : 'true'"
          :class="{ 'is-hidden': i !== index }"
          :width="shot.width"
          :height="shot.height"
          loading="lazy"
          decoding="async"
          draggable="false"
        />
      </button>
      <button type="button" class="ctrl gallery__expand" aria-label="View full screen" @click="open">
        <Glyph name="expand" />
      </button>
    </div>

    <div class="gallery__bar">
      <div class="gallery__thumbs">
        <button
          v-for="(shot, i) in shots"
          :key="shot.src"
          type="button"
          class="thumb"
          :class="{ sel: i === index }"
          :aria-current="i === index ? 'true' : 'false'"
          :aria-label="`Show image ${i + 1}: ${shot.alt}`"
          @click="index = i"
        >
          <img :src="shot.thumb" alt="" loading="lazy" decoding="async" />
        </button>
      </div>
      <div class="gallery__nav">
        <button type="button" class="ctrl" aria-label="Previous image" @click="go(-1)">
          <Glyph name="prev" />
        </button>
        <span class="gallery__count num" aria-live="polite">{{ pad(index + 1) }} / {{ pad(total) }}</span>
        <button type="button" class="ctrl" aria-label="Next image" @click="go(1)">
          <Glyph name="next" />
        </button>
      </div>
    </div>
  </div>
</template>
