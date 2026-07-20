import { onMounted, onBeforeUnmount, type Ref } from 'vue'
import { usePreferredReducedMotion } from '@vueuse/core'

/** Adds `.revealed` to the element when it scrolls into view (once by default). */
interface Opts { threshold?: number; rootMargin?: string; delay?: number; once?: boolean }

export function useReveal(elRef: Ref<HTMLElement | null>, opts: Opts = {}) {
  const { threshold = 0.16, rootMargin = '0px 0px -8% 0px', delay = 0, once = true } = opts
  const reduced = usePreferredReducedMotion()
  let io: IntersectionObserver | null = null

  onMounted(() => {
    const el = elRef.value
    if (!el) return
    const apply = () => el.classList.add('revealed')
    if (reduced.value === 'reduce') { apply(); return }

    io = new IntersectionObserver((entries) => {
      for (const e of entries) {
        if (e.isIntersecting) {
          if (delay) setTimeout(apply, delay)
          else apply()
          if (once) io?.unobserve(e.target)
        } else if (!once) {
          el.classList.remove('revealed')
        }
      }
    }, { threshold, rootMargin })
    io.observe(el)
  })

  onBeforeUnmount(() => io?.disconnect())
}
