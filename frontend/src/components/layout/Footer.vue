<script setup lang="ts">
import { Icon } from '@iconify/vue'
import { useProfile, useSocialLinks } from '@/composables/useApi'

const { data: profile } = useProfile()
const { data: socials } = useSocialLinks()
const year = new Date().getFullYear()
</script>

<template>
  <footer class="relative border-t border-line/10 py-12">
    <div class="container-x flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
      <div>
        <div class="font-display text-lg font-semibold tracking-tighter2">{{ profile?.name ?? 'Majdi Al-Kediwi' }}</div>
        <p class="mt-1 text-sm text-text3">© {{ year }} · Built with Vue &amp; Laravel</p>
      </div>
      <div class="flex items-center gap-3">
        <a
          v-for="s in socials" :key="s.id" :href="s.url" target="_blank" rel="noopener"
          class="grid h-10 w-10 place-items-center rounded-full border border-line/15 text-text2 transition-colors hover:border-accent/40 hover:text-accent"
          :aria-label="s.platform"
        >
          <Icon :icon="s.icon || 'lucide:link'" class="h-[18px] w-[18px]" />
        </a>
      </div>
    </div>
    <p class="container-x mt-8 font-mono text-[11px] uppercase tracking-wide2 text-text3">
      Set in Clash Display · Satoshi · JetBrains Mono
    </p>
  </footer>
</template>
