import { reactive } from 'vue'
import type { Shot } from '@/data/content'

/* One lightbox for the whole page: any gallery opens it with its
   own list of shots and the index to start on. */
export const lightbox = reactive({
  open: false,
  title: '',
  shots: [] as Shot[],
  index: 0,
  returnFocus: null as HTMLElement | null,
})

export function openLightbox(title: string, shots: Shot[], index = 0) {
  lightbox.returnFocus = document.activeElement as HTMLElement | null
  lightbox.title = title
  lightbox.shots = shots
  lightbox.index = index
  lightbox.open = true
}

export function closeLightbox() {
  lightbox.open = false
  const el = lightbox.returnFocus
  lightbox.returnFocus = null
  if (el && typeof el.focus === 'function') requestAnimationFrame(() => el.focus())
}

export function stepLightbox(delta: number) {
  const n = lightbox.shots.length
  if (!n) return
  lightbox.index = (lightbox.index + delta + n) % n
}

/* A horizontal swipe on touch: calls back with -1 or +1. Returns
   handlers to spread on the element, and whether the last pointer
   gesture was a swipe (so a click after it can be ignored). */
export function useSwipe(onStep: (delta: number) => void) {
  let x0 = 0
  let y0 = 0
  let active = false
  let swiped = false
  return {
    wasSwipe: () => swiped,
    handlers: {
      pointerdown(e: PointerEvent) {
        if (e.pointerType === 'mouse') return
        active = true
        swiped = false
        x0 = e.clientX
        y0 = e.clientY
      },
      pointerup(e: PointerEvent) {
        if (!active) return
        active = false
        const dx = e.clientX - x0
        const dy = e.clientY - y0
        if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy) * 1.4) {
          swiped = true
          onStep(dx < 0 ? 1 : -1)
          window.setTimeout(() => (swiped = false), 350)
        }
      },
      pointercancel() {
        active = false
      },
    },
  }
}
