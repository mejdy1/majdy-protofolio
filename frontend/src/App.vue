<script setup lang="ts">
import { RouterView } from 'vue-router'
import Lightbox from '@/components/ui/Lightbox.vue'
import { useGlassPointer } from '@/composables/useMotion'

useGlassPointer()
</script>

<template>
  <RouterView />
  <Lightbox />
</template>
