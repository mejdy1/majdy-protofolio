<script setup lang="ts">
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'
import { Icon } from '@iconify/vue'
import { useProfile, useSocialLinks } from '@/composables/useApi'
import { getLenis } from '@/composables/useLenis'

const { data: profile } = useProfile()
const { data: socials } = useSocialLinks()
const scrolled = ref(false)

// reactive mobile drawer state — single source of truth for the menu overlay
const isMenuOpen = ref(false)

const links = [
  { label: 'Projects', href: '#projects' },
  { label: 'Work', href: '#experience' },
  { label: 'Credentials', href: '#credentials' },
  { label: 'Contact', href: '#contact' },
]

function closeMenu() {
  isMenuOpen.value = false
}

function openMenu() {
  isMenuOpen.value = true
}

/** Smooth-scroll to a section, then close the mobile menu immediately. */
function go(href: string) {
  // close the drawer FIRST so the layout/body-scroll re-enable before we scroll
  isMenuOpen.value = false
  const el = document.querySelector(href) as HTMLElement | null
  if (!el) return
  // allow the drawer's leave transition + Lenis restart to flush this frame
  requestAnimationFrame(() => {
    const lenis = getLenis()
    if (lenis) lenis.scrollTo(el, { offset: -72, force: true })
    else el.scrollIntoView({ behavior: 'smooth' })
  })
}

function toTop() {
  isMenuOpen.value = false
  const lenis = getLenis()
  if (lenis) lenis.scrollTo(0, { force: true })
  else window.scrollTo({ top: 0, behavior: 'smooth' })
}

const onScroll = () => { scrolled.value = window.scrollY > 16 }
const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') isMenuOpen.value = false }

// lock background scroll + pause smooth-scroll while the drawer is open
watch(isMenuOpen, (v) => {
  const lenis = getLenis()
  if (v) lenis?.stop()
  else lenis?.start()
  document.documentElement.classList.toggle('menu-open', v)
})

onMounted(() => {
  window.addEventListener('scroll', onScroll, { passive: true }); onScroll()
  window.addEventListener('keydown', onKey)
})
onBeforeUnmount(() => {
  window.removeEventListener('scroll', onScroll)
  window.removeEventListener('keydown', onKey)
  document.documentElement.classList.remove('menu-open')
  getLenis()?.start()
})
</script>

<template>
  <header
    class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
    :class="scrolled ? 'border-b border-line/20 bg-surface/80 backdrop-blur-xl shadow-lg shadow-accent/5' : 'border-b border-transparent'"
  >
    <nav class="container-x flex h-16 items-center justify-between">
      <button @click="toTop" class="flex items-center gap-2.5 font-display text-lg font-semibold tracking-tighter2">
        <span class="grid h-7 w-7 place-items-center rounded-lg bg-accent text-sm text-white shadow-glow">M</span>
        <span>{{ profile?.name?.split(' ')[0] ?? 'Majdy' }}</span>
      </button>

      <!-- Desktop navigation -->
      <div class="hidden items-center gap-8 md:flex">
        <button
          v-for="l in links" :key="l.href" @click="go(l.href)"
          class="text-sm text-text2 transition-colors hover:text-accent"
        >{{ l.label }}</button>
      </div>

      <div class="flex items-center gap-3">
        <a
          v-if="profile?.resume_url" :href="profile.resume_url" download
          class="hidden h-10 items-center gap-2 rounded-full border border-line/15 px-4 text-sm text-text transition-colors hover:border-accent/40 hover:text-accent sm:inline-flex"
        >
          <Icon icon="lucide:download" class="h-4 w-4" /> CV
        </a>
        <!-- Hamburger — opens the reactive drawer -->
        <button
          @click="openMenu"
          class="grid h-10 w-10 place-items-center rounded-full border border-line/15 text-text2 transition-colors hover:border-accent/40 hover:text-accent md:hidden"
          aria-label="Open menu"
          aria-expanded="isMenuOpen"
        >
          <Icon icon="lucide:menu" class="h-5 w-5" />
        </button>
      </div>
    </nav>

    <!-- Mobile side drawer (teleported to body so it sits above everything) -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition-opacity duration-300 ease-out"
        leave-active-class="transition-opacity duration-200 ease-in"
        enter-from-class="opacity-0" leave-to-class="opacity-0"
      >
        <div
          v-if="isMenuOpen" @click="closeMenu"
          class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm md:hidden"
          aria-hidden="true"
        ></div>
      </Transition>

      <Transition
        enter-active-class="transition-transform duration-300 ease-out"
        leave-active-class="transition-transform duration-200 ease-in"
        enter-from-class="translate-x-full" leave-to-class="translate-x-full"
      >
        <aside
          v-if="isMenuOpen"
          class="fixed inset-y-0 right-0 z-[70] flex w-[80%] max-w-xs flex-col border-l border-accent/20 bg-bg/95 backdrop-blur-xl shadow-2xl shadow-accent/10 md:hidden"
          role="dialog" aria-modal="true" aria-label="Menu"
        >
          <div class="flex h-16 shrink-0 items-center justify-between border-b border-accent/10 px-6">
            <span class="font-mono text-xs uppercase tracking-wide2 text-accent">Menu</span>
            <button
              @click="closeMenu"
              class="grid h-10 w-10 place-items-center rounded-full border border-line/15 text-text2 transition-colors hover:border-accent/40 hover:text-accent"
              aria-label="Close menu"
            >
              <Icon icon="lucide:x" class="h-5 w-5" />
            </button>
          </div>

          <nav class="flex flex-1 flex-col gap-1 overflow-y-auto px-4 py-5" style="-webkit-overflow-scrolling: touch;">
            <!-- Each link closes the menu via go() before scrolling -->
            <button
              v-for="l in links" :key="l.href" @click="go(l.href)"
              class="rounded-xl px-3 py-3.5 text-left font-display text-xl font-medium text-text transition-colors hover:bg-accent/10 hover:text-accent active:bg-accent/15 active:text-accent"
            >{{ l.label }}</button>
          </nav>

          <div class="shrink-0 space-y-5 border-t border-accent/10 px-6 py-6">
            <a
              v-if="profile?.resume_url" :href="profile.resume_url" download
              class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-full border border-accent/20 text-text transition-colors hover:border-accent/40 hover:text-accent active:bg-accent/10"
            >
              <Icon icon="lucide:download" class="h-[18px] w-[18px]" /> Download CV
            </a>
            <div v-if="socials && socials.length" class="flex items-center gap-3">
              <a
                v-for="s in socials" :key="s.id" :href="s.url" target="_blank" rel="noopener"
                class="grid h-11 w-11 place-items-center rounded-full border border-line/15 text-text2 transition-colors hover:border-accent/40 hover:text-accent active:bg-accent/10"
                :aria-label="s.platform"
              >
                <Icon :icon="s.icon || 'lucide:link'" class="h-5 w-5" />
              </a>
            </div>
          </div>
        </aside>
      </Transition>
    </Teleport>
  </header>
</template>
