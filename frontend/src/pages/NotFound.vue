<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <main class="container-x flex min-h-[78vh] flex-col items-center justify-center text-center">
    <p class="eyebrow text-accent">Error 404</p>
    <h1 class="mt-5 font-display text-6xl tracking-tighter2">Lost in space</h1>
    <p class="mt-4 max-w-sm text-text2">The page you’re looking for drifted off. Let’s get you back.</p>
    <RouterLink to="/" class="btn-primary mt-9">Back home</RouterLink>
  </main>
</template>
