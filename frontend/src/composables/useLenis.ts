import Lenis from 'lenis'
import { onMounted, onBeforeUnmount } from 'vue'
import { usePreferredReducedMotion } from '@vueuse/core'

let lenis: Lenis | null = null
let rafId = 0

export function useLenis() {
  const reduced = usePreferredReducedMotion()

  onMounted(() => {
    if (reduced.value === 'reduce') return
    if (lenis) return
    lenis = new Lenis({
      duration: 1.15,
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      smoothWheel: true,
    })
    const raf = (time: number) => {
      lenis?.raf(time)
      rafId = requestAnimationFrame(raf)
    }
    rafId = requestAnimationFrame(raf)
  })

  onBeforeUnmount(() => {
    cancelAnimationFrame(rafId)
    lenis?.destroy()
    lenis = null
  })
}

export const getLenis = () => lenis
