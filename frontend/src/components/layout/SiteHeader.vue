<script setup lang="ts">
import { computed, onBeforeUnmount, ref, watch } from 'vue'
import { nav, profile } from '@/data/content'
import Glyph from '@/components/ui/Glyph.vue'
import { useScrollState } from '@/composables/useMotion'

// which nav word the section on screen belongs to
const groups: Record<string, string> = {
  intro: '', rasid: '#rasid', arabatech: '#rasid', raqeeb: '#rasid', concepts: '#rasid',
  skills: '#skills', experience: '#experience', credentials: '#experience', contact: '#contact',
}
const { active, progress } = useScrollState(Object.keys(groups))
const current = computed(() => groups[active.value] ?? '')

const open = ref(false)

function close() {
  open.value = false
}
function onKey(e: KeyboardEvent) {
  if (e.key === 'Escape') close()
}
watch(open, (v) => {
  document.documentElement.style.overflow = v ? 'hidden' : ''
  if (v) window.addEventListener('keydown', onKey)
  else window.removeEventListener('keydown', onKey)
})
onBeforeUnmount(() => window.removeEventListener('keydown', onKey))
</script>

<template>
  <header class="nav">
    <div class="wrap">
      <a class="skip-link" href="#top">Skip to content</a>
      <nav class="nav__links" aria-label="Sections">
        <a
          v-for="item in nav"
          :key="item.href"
          :href="item.href"
          :class="{ 'is-current': current === item.href }"
          :aria-current="current === item.href ? 'location' : undefined"
        >{{ item.label }}</a>
      </nav>
      <a class="brand" href="#top" @click="close">
        <img src="/favicon.svg" alt="" width="28" height="28" />
        {{ profile.name }}
      </a>
      <div class="nav__end">
        <a class="btn btn--primary nav__cv" :href="profile.cv" :download="profile.cvFileName">
          Download CV
          <Glyph name="download" />
        </a>
        <button
          type="button"
          class="ctrl nav__toggle"
          aria-controls="menu-sheet"
          :aria-expanded="open ? 'true' : 'false'"
          :aria-label="open ? 'Close menu' : 'Open menu'"
          @click="open = !open"
        >
          <Glyph :name="open ? 'close' : 'menu'" />
        </button>
      </div>
    </div>
    <span class="nav__progress" aria-hidden="true" :style="{ transform: `scaleX(${progress})` }"></span>
  </header>

  <Transition name="sheet">
  <div v-show="open" id="menu-sheet" class="sheet" @click="close">
    <div class="wrap">
      <nav class="sheet__links" aria-label="Sections">
        <a v-for="(item, i) in nav" :key="item.href" :href="item.href">
          {{ item.label }}<span class="num" aria-hidden="true">0{{ i + 1 }}</span>
        </a>
      </nav>
      <div class="sheet__foot">
        <a class="btn btn--primary" :href="profile.cv" :download="profile.cvFileName">
          Download CV
          <Glyph name="download" />
        </a>
        <a class="link sheet__mail" :href="`mailto:${profile.email}`">{{ profile.email }}</a>
      </div>
    </div>
  </div>
  </Transition>
</template>
