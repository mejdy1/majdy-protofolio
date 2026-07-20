import { ref } from 'vue'

type Theme = 'light' | 'dark'
const STORAGE_KEY = 'majdy.theme'

// module-scoped singleton so every component shares one source of truth
const theme = ref<Theme>('dark')
let initialized = false

function systemPrefersDark(): boolean {
  return typeof window !== 'undefined'
    && window.matchMedia('(prefers-color-scheme: dark)').matches
}

function apply(next: Theme, animate = true): void {
  const root = document.documentElement
  if (animate) {
    root.classList.add('theme-transition')
    window.setTimeout(() => root.classList.remove('theme-transition'), 480)
  }
  root.classList.toggle('dark', next === 'dark')
  root.style.colorScheme = next
  document.querySelector('meta[name="theme-color"]')
    ?.setAttribute('content', next === 'dark' ? '#080A10' : '#FFFFFF')
}

export function useTheme() {
  if (!initialized && typeof window !== 'undefined') {
    initialized = true
    const stored = localStorage.getItem(STORAGE_KEY) as Theme | null
    theme.value = stored ?? (systemPrefersDark() ? 'dark' : 'light')
    apply(theme.value, false) // sync class set by the inline script; no flash
    // follow the OS only while the user hasn't made an explicit choice
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
      if (!localStorage.getItem(STORAGE_KEY)) {
        theme.value = e.matches ? 'dark' : 'light'
        apply(theme.value, true)
      }
    })
  }

  const toggle = () => {
    theme.value = theme.value === 'dark' ? 'light' : 'dark'
    localStorage.setItem(STORAGE_KEY, theme.value)
    apply(theme.value, true)
  }

  const set = (t: Theme) => {
    theme.value = t
    localStorage.setItem(STORAGE_KEY, t)
    apply(t, true)
  }

  return { theme, toggle, set }
}
