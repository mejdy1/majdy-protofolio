<script setup lang="ts">
import { usePreferredReducedMotion } from '@vueuse/core'

const reduced = usePreferredReducedMotion()

function onMove(e: MouseEvent) {
  if (reduced.value === 'reduce') return
  const el = e.currentTarget as HTMLElement
  const r = el.getBoundingClientRect()
  el.style.setProperty('--mx', `${e.clientX - r.left}px`)
  el.style.setProperty('--my', `${e.clientY - r.top}px`)
}
</script>

<template>
  <div class="glow-card" @mousemove="onMove">
    <slot />
  </div>
</template>
