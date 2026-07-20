<script setup lang="ts">
import { Icon } from '@iconify/vue'
import { useTheme } from '@/composables/useTheme'

const { theme, toggle } = useTheme()
</script>

<template>
  <button
    @click="toggle"
    class="relative grid h-10 w-10 place-items-center rounded-full border border-line/15 text-text2 transition-colors duration-300 hover:border-accent/40 hover:text-accent"
    :aria-label="theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'"
  >
    <Transition
      mode="out-in"
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 rotate-90 scale-50"
      leave-active-class="transition duration-200 ease-in"
      leave-to-class="opacity-0 -rotate-90 scale-50"
    >
      <Icon v-if="theme === 'dark'" key="sun" icon="lucide:sun" class="h-[18px] w-[18px]" />
      <Icon v-else key="moon" icon="lucide:moon" class="h-[18px] w-[18px]" />
    </Transition>
  </button>
</template>
