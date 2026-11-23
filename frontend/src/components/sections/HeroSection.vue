<script setup lang="ts">
import { ref } from 'vue'
import { hero, profile } from '@/data/content'
import Glyph from '@/components/ui/Glyph.vue'
import ProjectMark from '@/components/ui/ProjectMark.vue'

/* The workspace: one tile per project, each a link that scrolls down to its
   case study. The index under the buttons and the tiles light up together. */
const tiles = [
  { id: 'rasid', no: '01', name: 'Rasid', tag: 'Public-health dashboards', logo: { src: '/work/rasid/logo.svg' }, img: '/work/rasid/home.webp', w: 2000, h: 1196 },
  { id: 'arabatech', no: '02', name: 'ArabaTech', tag: 'AI grocery cart, 1st at Injaz 2026', logo: { src: '/work/smart-cart/logo.webp', ground: 'fill' as const }, img: '/work/smart-cart/voice-nav-sm.webp', w: 560, h: 282 },
  { id: 'health-volunteering', no: '04', name: 'Health Volunteering', tag: 'App redesign', logo: { src: '/work/volunteering/logo.webp', ground: 'light' as const }, img: '/work/volunteering/3-calls-sm.webp', w: 360, h: 789 },
  { id: 'ui-hub', no: '04', name: 'UI Hub', tag: 'UI/UX community', logo: { src: '/work/ui-hub/logo.png', ground: 'light' as const }, img: '/work/ui-hub/feed-sm.webp', w: 560, h: 363 },
  { id: 'raqeeb', no: '03', name: 'Raqeeb', tag: 'In progress', logo: undefined, img: '', w: 0, h: 0 },
]
const index = [
  { id: 'rasid', no: '01', name: 'Rasid', tag: 'Co-founder and UX lead' },
  { id: 'arabatech', no: '02', name: 'ArabaTech', tag: 'Graduation project, first place' },
  { id: 'raqeeb', no: '03', name: 'Raqeeb', tag: 'Personal project, now' },
  { id: 'concepts', no: '04', name: 'UI Hub and Health Volunteering', tag: 'Two more projects' },
]

const lit = ref('')
const litFor = (id: string) =>
  lit.value === id || (lit.value === 'concepts' && (id === 'ui-hub' || id === 'health-volunteering'))

// a short press before the page scrolls, so the click reads as "going there"
const pressed = ref('')
function go(id: string) {
  pressed.value = id
  window.setTimeout(() => (pressed.value = ''), 260)
}
</script>

<template>
  <section id="intro" class="hero" aria-labelledby="hero-title">
    <div class="wrap">
      <div class="hero-text">
        <div class="hero__words">
          <p class="hero__notice" data-enter style="--e: 0">{{ hero.notice }}</p>
          <h1 id="hero-title" data-enter style="--e: 1"><span class="nowrap">{{ hero.name }},</span> {{ hero.role }}</h1>
          <p class="hero__lead" data-enter style="--e: 2">{{ hero.lead }}</p>
          <div class="btn-row" data-enter style="--e: 3">
            <a class="btn btn--primary" :href="profile.cv" :download="profile.cvFileName">
              Download CV
              <Glyph name="download" />
            </a>
            <a class="btn btn--secondary" href="#rasid">See the case studies</a>
          </div>

          <nav class="hero__index" aria-label="Case studies" data-enter style="--e: 4">
            <a
              v-for="item in index"
              :key="item.id"
              :href="`#${item.id}`"
              :class="{ 'is-lit': lit === item.id }"
              @pointerenter="lit = item.id"
              @pointerleave="lit = ''"
              @focus="lit = item.id"
              @blur="lit = ''"
            >
              <span class="num">{{ item.no }}</span>
              <b>{{ item.name }}</b>
              <em>{{ item.tag }}</em>
              <Glyph name="out" />
            </a>
          </nav>
        </div>

        <!-- The workspace: organised project tiles, each scrolls to its case study -->
        <div class="workspace" data-enter style="--e: 1">
          <a
            v-for="(t, i) in tiles"
            :key="t.id"
            :href="`#${t.id}`"
            class="tile"
            :class="[`tile--${t.id}`, { 'is-lit': litFor(t.id), 'is-pressed': pressed === t.id }]"
            :style="{ '--t': i }"
            :aria-label="`${t.name}: ${t.tag}. Go to the case study`"
            @pointerenter="lit = t.id"
            @pointerleave="lit = ''"
            @focus="lit = t.id"
            @blur="lit = ''"
            @click="go(t.id)"
          >
            <span class="tile__bar" aria-hidden="true">
              <ProjectMark :logo="t.logo" size="sm" />
              <b>{{ t.name }}</b>
              <em>{{ t.tag }}</em>
            </span>
            <span class="tile__media" aria-hidden="true">
              <img v-if="t.img" :src="t.img" alt="" :width="t.w" :height="t.h" decoding="async" :fetchpriority="i === 0 ? 'high' : undefined" />
              <span v-else class="redact-mini"><i></i><i></i><i></i><b></b></span>
            </span>
          </a>
        </div>
      </div>
    </div>
  </section>
</template>
