<script setup lang="ts">
import type { Logo } from '@/data/content'

/* A project's logo on a small glass tile, set beside its heading. Without a
   logo (Raqeeb, before launch) the tile is hatched: withheld, not missing. */
defineProps<{ logo?: Logo; size?: 'sm' | 'md' }>()
</script>

<template>
  <span
    class="mark"
    :class="[`mark--${logo?.ground ?? (logo ? 'dark' : 'withheld')}`, `mark--${size ?? 'md'}`]"
    aria-hidden="true"
  >
    <img v-if="logo" :src="logo.src" alt="" decoding="async" />
  </span>
</template>
