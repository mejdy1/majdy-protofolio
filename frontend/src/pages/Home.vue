<script setup lang="ts">
import { computed } from 'vue'
import Hero from '@/components/sections/Hero.vue'
import FeaturedProjects from '@/components/sections/FeaturedProjects.vue'
import Experience from '@/components/sections/Experience.vue'
import OtherProjects from '@/components/sections/OtherProjects.vue'
import Certifications from '@/components/sections/Certifications.vue'
import Contact from '@/components/sections/Contact.vue'
import { useProjects } from '@/composables/useApi'

const { data: otherProjects } = useProjects({ featured: false })
const hasOtherProjects = computed(() => (otherProjects.value?.length ?? 0) > 0)
</script>

<template>
  <main>
    <Hero />
    <FeaturedProjects />
    <Experience />
    <OtherProjects v-if="hasOtherProjects" />
    <Certifications />
    <Contact />
  </main>
</template>
