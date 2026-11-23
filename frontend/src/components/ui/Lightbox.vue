<script setup lang="ts">
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue'
import { lightbox, closeLightbox, stepLightbox, useSwipe } from '@/composables/useLightbox'
import Glyph from './Glyph.vue'

const dialog = ref<HTMLElement | null>(null)
const closeBtn = ref<HTMLButtonElement | null>(null)
const shot = computed(() => lightbox.shots[lightbox.index])
const total = computed(() => lightbox.shots.length)
const swipe = useSwipe(stepLightbox)

function onKey(e: KeyboardEvent) {
  if (!lightbox.open) return
  if (e.key === 'Escape') { e.preventDefault(); closeLightbox() }
  else if (e.key === 'ArrowRight') { e.preventDefault(); stepLightbox(1) }
  else if (e.key === 'ArrowLeft') { e.preventDefault(); stepLightbox(-1) }
  else if (e.key === 'Tab' && dialog.value) {
    const items = Array.from(dialog.value.querySelectorAll<HTMLElement>('button:not([disabled])'))
    if (!items.length) return
    const first = items[0]
    const last = items[items.length - 1]
    if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus() }
    else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus() }
  }
}

watch(
  () => lightbox.open,
  async (open) => {
    const root = document.documentElement
    if (open) {
      root.style.overflow = 'hidden'
      window.addEventListener('keydown', onKey)
      await nextTick()
      closeBtn.value?.focus()
    } else {
      root.style.overflow = ''
      window.removeEventListener('keydown', onKey)
    }
  },
)

onBeforeUnmount(() => {
  window.removeEventListener('keydown', onKey)
  document.documentElement.style.overflow = ''
})
</script>

<template>
  <Teleport to="body">
    <Transition name="lb">
    <div
      v-if="lightbox.open && shot"
      ref="dialog"
      class="lb"
      role="dialog"
      aria-modal="true"
      :aria-label="`${lightbox.title}, image ${lightbox.index + 1} of ${total}`"
    >
      <div class="lb__top">
        <p class="lb__title"><span class="num">{{ lightbox.index + 1 }} / {{ total }}</span>{{ lightbox.title }}</p>
        <button ref="closeBtn" type="button" class="ctrl" aria-label="Close full screen view" @click="closeLightbox">
          <Glyph name="close" />
        </button>
      </div>

      <div class="lb__stage" v-on="swipe.handlers" @click.self="closeLightbox">
        <Transition name="lb-img">
          <img :key="shot.src" :src="shot.src" :alt="shot.alt" :width="shot.width" :height="shot.height" draggable="false" />
        </Transition>
        <template v-if="total > 1">
          <button type="button" class="ctrl lb__prev" aria-label="Previous image" @click="stepLightbox(-1)">
            <Glyph name="prev" />
          </button>
          <button type="button" class="ctrl lb__next" aria-label="Next image" @click="stepLightbox(1)">
            <Glyph name="next" />
          </button>
        </template>
      </div>

      <div v-if="total > 1" class="lb__thumbs">
        <button
          v-for="(s, i) in lightbox.shots"
          :key="s.src"
          type="button"
          class="thumb"
          :class="{ sel: i === lightbox.index, 'thumb--tall': s.height > s.width }"
          :aria-current="i === lightbox.index ? 'true' : 'false'"
          :aria-label="`Show image ${i + 1}: ${s.alt}`"
          @click="lightbox.index = i"
        >
          <img :src="s.thumb" alt="" decoding="async" />
        </button>
      </div>
    </div>
    </Transition>
  </Teleport>
</template>
