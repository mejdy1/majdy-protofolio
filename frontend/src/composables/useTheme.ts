import { ref } from 'vue'

type Theme = 'dark'

const theme = ref<Theme>('dark')

export function useTheme() {
  if (typeof window !== 'undefined') {
    const root = document.documentElement
    root.classList.add('dark')
    root.style.colorScheme = 'dark'
    document.querySelector('meta[name="theme-color"]')
      ?.setAttribute('content', '#080A10')
  }

  return { theme, toggle: () => {}, set: () => {} }
}
