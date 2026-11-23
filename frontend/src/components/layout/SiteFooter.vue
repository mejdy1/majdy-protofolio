<script setup lang="ts">
import { close, nav, profile } from '@/data/content'
import Glyph from '@/components/ui/Glyph.vue'
</script>

<template>
  <footer id="contact" class="closing" aria-labelledby="contact-title">
    <div class="wrap">
      <div v-reveal class="close">
        <h2 id="contact-title" data-stagger>{{ close.title }}</h2>
        <p class="lead" data-stagger>{{ close.line }}</p>
        <a class="btn btn--primary btn--xl" data-stagger :href="profile.cv" :download="profile.cvFileName">
          Download CV
          <Glyph name="download" />
        </a>
      </div>

      <div v-reveal="1" class="grid foot">
        <div class="foot__contact">
          <p class="foot__label">Email</p>
          <a class="foot__mail" :href="`mailto:${profile.email}`">{{ profile.email }}</a>
        </div>
        <div class="foot__links">
          <ul>
            <li v-for="item in nav.slice(0, 3)" :key="item.href"><a :href="item.href">{{ item.label }}</a></li>
          </ul>
          <ul>
            <li><a :href="profile.linkedin" target="_blank" rel="noopener">LinkedIn</a></li>
            <li><a href="#top">Back to top</a></li>
          </ul>
        </div>
        <div class="legal">
          <p>© 2026 {{ profile.name }} · {{ profile.location }}</p>
          <p>Product names and logos are trademarks of their owners.</p>
        </div>
      </div>
    </div>
  </footer>
</template>
