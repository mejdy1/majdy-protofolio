<script setup lang="ts">
import { RouterView } from 'vue-router'
import Navbar from '@/components/layout/Navbar.vue'
import Footer from '@/components/layout/Footer.vue'
import { useLenis } from '@/composables/useLenis'
import { useTheme } from '@/composables/useTheme'

useLenis()
useTheme()
</script>

<template>
  <!-- fixed ambient atmosphere -->
  <div class="ambient" aria-hidden="true">
    <span class="ambient__blob ambient__blob--a"></span>
    <span class="ambient__blob ambient__blob--b"></span>
    <span class="ambient__blob ambient__blob--c"></span>
  </div>
  <div class="grain" aria-hidden="true"></div>

  <div class="relative z-[1] flex min-h-screen flex-col">
    <Navbar />
    <RouterView />
    <Footer />
  </div>
</template>
