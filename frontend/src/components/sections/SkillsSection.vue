<script setup lang="ts">
import { skills } from '@/data/content'

const [users, researchers, developers, stakeholders] = skills.hub.nodes
</script>

<template>
  <section :id="skills.id" aria-labelledby="skills-title">
    <div class="wrap">
      <div class="grid skills">
        <div v-reveal class="skills__lead">
          <h2 id="skills-title" data-stagger>{{ skills.title }}</h2>
          <p class="lead" data-stagger>{{ skills.intro }}</p>

          <!-- Who I work between, and what passes along each line (from the CV) -->
          <div
            class="hub glass"
            data-stagger
            role="img"
            aria-label="Product design at the centre, connected to users through research and usability tests, to researchers through journeys and information architecture, to developers through the design system, handoff and front-end code, and to stakeholders through the roadmap and sprint priorities"
          >
            <svg class="hub__lines" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
              <path d="M37 50H18V30M18 50V70M63 50H82V30M82 50V70" />
            </svg>
            <div class="hub__node hub__node--tl" aria-hidden="true"><b>{{ users.name }}</b><span>{{ users.flow }}</span></div>
            <div class="hub__node hub__node--tr" aria-hidden="true"><b>{{ researchers.name }}</b><span>{{ researchers.flow }}</span></div>
            <div class="hub__centre sel" aria-hidden="true">{{ skills.hub.centre }}</div>
            <div class="hub__node hub__node--bl" aria-hidden="true"><b>{{ developers.name }}</b><span>{{ developers.flow }}</span></div>
            <div class="hub__node hub__node--br" aria-hidden="true"><b>{{ stakeholders.name }}</b><span>{{ stakeholders.flow }}</span></div>
          </div>
        </div>

        <ul v-reveal="1" class="skills__list">
          <li v-for="s in skills.items" :key="s.title" class="glass" data-stagger>
            <h3>{{ s.title }}</h3>
            <p>{{ s.body }}</p>
          </li>
          <li class="glass skills__tools" data-stagger>
            <h3>Tools</h3>
            <div>
              <ul class="tools" aria-label="Design, front-end and back-end tools">
                <li v-for="t in skills.tools" :key="t.name" :title="t.name">
                  <img :src="t.icon" :alt="t.name" :width="t.w" :height="t.h" loading="lazy" />
                </li>
              </ul>
              <div class="tools__ai">
                <ul class="tools" aria-label="AI tools">
                  <li v-for="t in skills.ai" :key="t.name" :title="t.name" :class="{ 'is-light': t.light }">
                    <img :src="t.icon" :alt="t.name" :width="t.w" :height="t.h" loading="lazy" />
                  </li>
                </ul>
                <p>{{ skills.aiLine }}</p>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </section>
</template>
