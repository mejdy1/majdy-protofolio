<script setup lang="ts">
import { ref, computed } from 'vue'
import { Icon } from '@iconify/vue'
import GlowCard from '@/components/ui/GlowCard.vue'
import { useCertifications } from '@/composables/useApi'
import { useReveal } from '@/composables/useReveal'
import type { Certification } from '@/types/api'

const { data } = useCertifications()
const items = computed(() => data.value ?? [])
const education = computed(() => items.value.filter((c) => c.kind !== 'certificate'))
const certificates = computed(() =>
  items.value
    .filter((c) => c.kind === 'certificate')
    .slice()
    .sort((a, b) => {
      const ad = a.issue_date ? new Date(a.issue_date).getTime() : 0
      const bd = b.issue_date ? new Date(b.issue_date).getTime() : 0
      return bd - ad
    }),
)

const root = ref<HTMLElement | null>(null)
useReveal(root, { threshold: 0.05 })

function fmt(d: string | null): string {
  if (!d) return ''
  return new Date(d).toLocaleDateString('en', { year: 'numeric', month: 'short' })
}

function issuerBadge(c: Certification): string {
  if (/google/i.test(c.issuer)) return 'Google'
  if (/deeplearning/i.test(c.issuer)) return 'DeepLearning.AI'
  return c.issuer.split('·')[0]?.trim() || c.issuer
}
</script>

<template>
  <section id="credentials" ref="root" class="section">
    <div class="container-x">
      <div class="max-w-3xl">
        <p class="reveal eyebrow text-accent">Credentials</p>
        <h2
          class="reveal mt-4 font-display text-[clamp(33px,5.4vw,64px)] font-semibold leading-tight tracking-tighter2"
          style="transition-delay:.06s"
        >
          Education &amp; certificates
        </h2>
        <p class="reveal mt-5 max-w-2xl text-lg leading-relaxed text-text2" style="transition-delay:.1s">
          Formal HCI education alongside a curated Coursera gallery — including the full Google UX Design Professional Certificate.
        </p>
      </div>

      <!-- Education -->
      <div v-if="education.length" class="mt-12">
        <p class="reveal font-mono text-[11px] uppercase tracking-wide2 text-text3">Education</p>
        <ul class="mt-5 grid gap-3 md:grid-cols-3">
          <li
            v-for="(c, i) in education"
            :key="c.id"
            class="reveal group flex items-start gap-4 rounded-2xl border border-line/12 bg-surface/55 p-5 transition-colors duration-300 hover:border-accent/40"
            :style="`transition-delay:${0.08 + i * 0.05}s`"
          >
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-accent/10 text-accent shadow-[0_0_24px_rgb(var(--glow-rgb)/0.18)]">
              <Icon icon="lucide:graduation-cap" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
              <div class="font-medium leading-snug text-text">{{ c.title }}</div>
              <div class="mt-1 text-[14px] leading-snug text-text3">
                {{ c.issuer }}
                <span v-if="fmt(c.issue_date)"> · {{ fmt(c.issue_date) }}</span>
              </div>
            </div>
          </li>
        </ul>
      </div>

      <!-- Coursera showcase -->
      <div v-if="certificates.length" class="mt-16">
        <div class="reveal flex flex-wrap items-end justify-between gap-4">
          <div>
            <p class="font-mono text-[11px] uppercase tracking-wide2 text-accent">Coursera Showcase</p>
            <h3 class="mt-3 font-display text-[clamp(24px,3.2vw,36px)] font-semibold tracking-tighter2">
              Professional certificates
            </h3>
          </div>
          <span class="rounded-full border border-accent/30 bg-accent/10 px-3 py-1 font-mono text-[11px] uppercase tracking-wide2 text-accent">
            {{ certificates.length }} credentials
          </span>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
          <GlowCard
            v-for="(c, i) in certificates"
            :key="c.id"
            class="reveal group flex h-full flex-col overflow-hidden"
            :style="`transition-delay:${0.08 + i * 0.04}s`"
          >
            <div class="relative aspect-[16/11] overflow-hidden border-b border-line/10 bg-[#0b1020]">
              <img
                v-if="c.preview_url"
                :src="c.preview_url"
                :alt="c.title"
                loading="lazy"
                class="h-full w-full object-cover object-top transition-transform duration-700 ease-out group-hover:scale-[1.035]"
              />
              <div
                v-else
                class="grid h-full place-items-center bg-gradient-to-br from-accent/20 via-transparent to-accent/5"
              >
                <Icon icon="lucide:award" class="h-10 w-10 text-accent/70" />
              </div>
              <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-bg/70 via-bg/10 to-transparent"></div>
              <span
                class="absolute left-4 top-4 rounded-full border border-white/15 bg-black/35 px-2.5 py-1 font-mono text-[10px] uppercase tracking-wide2 text-white/90 backdrop-blur-md"
              >
                {{ issuerBadge(c) }}
              </span>
            </div>

            <div class="flex flex-1 flex-col p-5">
              <div class="font-medium leading-snug text-text">{{ c.title }}</div>
              <div class="mt-2 text-[13px] text-text3">
                {{ c.issuer }}
                <span v-if="fmt(c.issue_date)"> · {{ fmt(c.issue_date) }}</span>
              </div>

              <div class="mt-5 flex flex-wrap items-center gap-2">
                <a
                  v-if="c.file_url"
                  :href="c.file_url"
                  target="_blank"
                  rel="noopener"
                  class="inline-flex h-9 items-center gap-1.5 rounded-full border border-line/15 px-3 text-[13px] text-text2 transition-colors hover:border-accent/40 hover:text-accent"
                >
                  <Icon icon="lucide:eye" class="h-3.5 w-3.5" /> Preview
                </a>
                <a
                  v-if="c.file_url"
                  :href="c.file_url"
                  :download="`${c.title}.pdf`"
                  class="inline-flex h-9 items-center gap-1.5 rounded-full border border-line/15 px-3 text-[13px] text-text2 transition-colors hover:border-accent/40 hover:text-accent"
                >
                  <Icon icon="lucide:download" class="h-3.5 w-3.5" /> PDF
                </a>
                <a
                  v-if="c.credential_url"
                  :href="c.credential_url"
                  target="_blank"
                  rel="noopener"
                  class="inline-flex h-9 items-center gap-1.5 rounded-full border border-accent/35 bg-accent/10 px-3 text-[13px] font-medium text-accent transition-colors hover:bg-accent hover:text-white"
                >
                  Verify <Icon icon="lucide:arrow-up-right" class="h-3.5 w-3.5" />
                </a>
              </div>
            </div>
          </GlowCard>
        </div>
      </div>
    </div>
  </section>
</template>
