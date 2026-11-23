<script setup lang="ts">
import { experience } from '@/data/content'

// the chart runs from January 2025 to November 2026: 23 months
const MONTHS = 23
const pct = (m: number) => `${(m / MONTHS) * 100}%`
const span = (from: number, to: number) => ({ left: pct(from), width: pct(to - from + 1) })
const chartLabel =
  'Timeline from January 2025 to now: ' +
  experience.roles.map((r) => `${r.org}, ${r.dates}`).join('; ')
</script>

<template>
  <section :id="experience.id" class="g2 exp" aria-labelledby="experience-title">
    <div class="wrap">
      <div>
        <div v-reveal class="section-head">
          <h2 id="experience-title" data-stagger>{{ experience.title }}</h2>
          <p class="lead" data-stagger>{{ experience.intro }}</p>
        </div>

        <!-- Gantt chart of the roles, drawn from the CV's dates -->
        <div v-reveal="1" class="gantt glass" role="img" :aria-label="chartLabel">
          <div class="gantt__axis" aria-hidden="true">
            <span :style="{ left: pct(0) }">Jan 2025</span>
            <span :style="{ left: pct(12) }">Jan 2026</span>
            <span class="gantt__now">Now</span>
          </div>
          <div v-for="(r, i) in experience.roles" :key="r.org" class="gantt__lane" aria-hidden="true" :style="{ '--i': i }">
            <span class="gantt__name">{{ r.org }}<span class="gantt__when num">{{ r.dates }}</span></span>
            <span class="gantt__track">
              <span v-if="r.point" class="gantt__point">
                <span class="gantt__dates num">{{ r.dates }}</span>
              </span>
              <span
                v-else
                class="gantt__bar"
                :class="{ 'gantt__bar--ongoing': r.ongoing, 'gantt__bar--before': r.before }"
                :style="span(r.from, r.to)"
              >
                <span class="gantt__dates num">{{ r.dates }}</span>
              </span>
            </span>
          </div>
        </div>
      </div>

      <ol v-reveal class="roles">
        <li v-for="r in experience.roles" :key="r.org" class="role glass" data-stagger>
          <div class="role__head">
            <h3>{{ r.org }}</h3>
            <p>{{ r.role }}</p>
          </div>
          <div class="role__body">
            <p class="role__dates num">{{ r.dates }}</p>
            <p>{{ r.body }}</p>
          </div>
        </li>
      </ol>
    </div>
  </section>
</template>
