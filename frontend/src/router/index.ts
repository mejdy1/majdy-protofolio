import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import Home from '@/pages/Home.vue'

const routes: RouteRecordRaw[] = [
  { path: '/', alias: '/index.html', name: 'home', component: Home },
  { path: '/:pathMatch(.*)*', name: 'not-found', component: () => import('@/pages/NotFound.vue') },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, _from, saved) {
    if (saved) return saved
    // anchors land on the section's top; the fixed header covers its padding
    if (to.hash) {
      const smooth = document.documentElement.classList.contains('has-motion')
      return { el: to.hash, top: 0, behavior: smooth ? 'smooth' : 'auto' }
    }
    return { top: 0 }
  },
})

export default router
