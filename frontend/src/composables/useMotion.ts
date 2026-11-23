import type { Directive } from 'vue'
import { onBeforeUnmount, onMounted, ref } from 'vue'

/* Motion layer. Every effect here is gated: `html.has-motion` is set in
   index.html only when the visitor has not asked for reduced motion, so
   without it (or without script) every element simply stays visible. */

export const motionOK = () =>
  typeof window !== 'undefined' && document.documentElement.classList.contains('has-motion')

/* v-reveal: the element (and children marked [data-stagger]) enter once,
   when 15% of it is on screen. The value is an optional delay index. */
let io: IntersectionObserver | null = null
function observer() {
  if (io) return io
  io = new IntersectionObserver(
    (entries) => {
      for (const e of entries) {
        if (!e.isIntersecting) continue
        e.target.classList.add('is-in')
        io!.unobserve(e.target)
      }
    },
    { rootMargin: '0px 0px -12% 0px', threshold: 0.15 },
  )
  return io
}

export const vReveal: Directive<HTMLElement, number | undefined> = {
  mounted(el, binding) {
    if (!motionOK()) return
    el.classList.add('reveal')
    if (typeof binding.value === 'number') el.style.setProperty('--rd', `${binding.value * 70}ms`)
    el.querySelectorAll<HTMLElement>('[data-stagger]').forEach((c, i) => c.style.setProperty('--sd', `${Math.min(i, 6) * 60}ms`))
    observer().observe(el)
  },
  unmounted(el) {
    io?.unobserve(el)
  },
}

/* The light that follows the pointer across glass cards: sets --mx/--my on the
   card under the pointer (its own custom properties, read by its own ::before). */
export function useGlassPointer() {
  let raf = 0
  let last: PointerEvent | null = null
  const onMove = (e: PointerEvent) => {
    if (e.pointerType !== 'mouse') return
    last = e
    if (raf) return
    raf = requestAnimationFrame(() => {
      raf = 0
      const ev = last!
      const card = (ev.target as Element | null)?.closest<HTMLElement>('.glass, .tile')
      if (!card) return
      const r = card.getBoundingClientRect()
      card.style.setProperty('--mx', `${ev.clientX - r.left}px`)
      card.style.setProperty('--my', `${ev.clientY - r.top}px`)
    })
  }
  onMounted(() => window.addEventListener('pointermove', onMove, { passive: true }))
  onBeforeUnmount(() => {
    window.removeEventListener('pointermove', onMove)
    cancelAnimationFrame(raf)
  })
}

/* Which section is on screen (for the nav), and how far down the page the
   reader is (for the progress line under the header). */
export function useScrollState(ids: string[]) {
  const active = ref('')
  const progress = ref(0)
  let raf = 0
  let sio: IntersectionObserver | null = null
  const onScroll = () => {
    if (raf) return
    raf = requestAnimationFrame(() => {
      raf = 0
      const max = document.documentElement.scrollHeight - innerHeight
      progress.value = max > 0 ? Math.min(1, Math.max(0, scrollY / max)) : 0
    })
  }
  onMounted(() => {
    sio = new IntersectionObserver(
      (entries) => {
        for (const e of entries) if (e.isIntersecting) active.value = e.target.id
      },
      { rootMargin: '-45% 0px -50% 0px' },
    )
    ids.forEach((id) => {
      const el = document.getElementById(id)
      if (el) sio!.observe(el)
    })
    window.addEventListener('scroll', onScroll, { passive: true })
    onScroll()
  })
  onBeforeUnmount(() => {
    sio?.disconnect()
    window.removeEventListener('scroll', onScroll)
    cancelAnimationFrame(raf)
  })
  return { active, progress }
}
