<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <main id="top" class="notfound">
    <div class="wrap">
      <p class="kicker">Error 404</p>
      <h1>This page doesn’t exist</h1>
      <p class="lead">The link may be old. The portfolio itself is one page.</p>
      <RouterLink to="/" class="btn btn--primary">Go to the portfolio</RouterLink>
    </div>
  </main>
</template>
