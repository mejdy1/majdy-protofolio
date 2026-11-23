<script setup lang="ts">
/* Interface glyphs, drawn on one 24px grid with one stroke. */
defineProps<{
  name: 'prev' | 'next' | 'expand' | 'close' | 'download' | 'menu' | 'out'
}>()
</script>

<template>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    <path v-if="name === 'prev'" d="M15 5l-7 7 7 7" />
    <path v-else-if="name === 'next'" d="M9 5l7 7-7 7" />
    <path v-else-if="name === 'expand'" d="M14 4h6v6M10 20H4v-6M20 4l-7 7M4 20l7-7" />
    <path v-else-if="name === 'close'" d="M6 6l12 12M18 6L6 18" />
    <path v-else-if="name === 'download'" d="M12 4v11M7 10.5l5 5 5-5M5 20h14" />
    <path v-else-if="name === 'menu'" d="M4 8h16M4 16h16" />
    <path v-else-if="name === 'out'" d="M8 16L16 8M9 8h7v7" />
  </svg>
</template>
