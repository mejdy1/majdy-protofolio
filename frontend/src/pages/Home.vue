<script setup lang="ts">
import { cases } from '@/data/content'
import SiteHeader from '@/components/layout/SiteHeader.vue'
import SiteFooter from '@/components/layout/SiteFooter.vue'
import HeroSection from '@/components/sections/HeroSection.vue'
import CaseSection from '@/components/sections/CaseSection.vue'
import RaqeebSection from '@/components/sections/RaqeebSection.vue'
import ConceptsSection from '@/components/sections/ConceptsSection.vue'
import SkillsSection from '@/components/sections/SkillsSection.vue'
import ExperienceSection from '@/components/sections/ExperienceSection.vue'
import CredentialsSection from '@/components/sections/CredentialsSection.vue'

const [rasid, smartCart] = cases
</script>

<template>
  <!--
    Plan (1440x900 screens): hero 1 · Rasid 1 · Smart Cart 1 (the light band) · Raqeeb 1 ·
    personal concepts 2 · skills 1 · experience 1 · certificates 1 · close and footer 1.
    Deal: hero film (adapted: the work as a framed canvas, words in its lower-left notch),
    nav-split, footer-visit (adapted: the email set large), close-type, dark-band
    (inverted on this dark page: one light band), block buttons. Type: Newsreader /
    Hanken Grotesk. Palette: kept from the previous site at the owner's request.
  -->
  <SiteHeader />
  <main id="top">
    <HeroSection />
    <CaseSection :item="rasid" />
    <CaseSection :item="smartCart" paper />
    <RaqeebSection />
    <ConceptsSection />
    <SkillsSection />
    <ExperienceSection />
    <CredentialsSection />
  </main>
  <SiteFooter />
</template>
