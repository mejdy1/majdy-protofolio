<script setup lang="ts">
import { raqeeb } from '@/data/content'
import ProjectMark from '@/components/ui/ProjectMark.vue'
</script>

<template>
  <section :id="raqeeb.id" class="case" aria-labelledby="raqeeb-title">
    <div class="wrap">
      <div class="grid case__grid">
        <div class="case__col">
          <div v-reveal class="case__text">
            <p class="kicker" data-stagger>{{ raqeeb.kicker }}</p>
            <div class="case__title" data-stagger>
              <ProjectMark />
              <h2 id="raqeeb-title">{{ raqeeb.title }}</h2>
            </div>
            <div class="glass case__card" data-stagger>
              <p class="case__summary">{{ raqeeb.intro }}</p>
              <dl class="facts">
                <div v-for="f in raqeeb.facts" :key="f.label">
                  <dt>{{ f.label }}</dt>
                  <dd>{{ f.value }}</dd>
                </div>
              </dl>
            </div>
          </div>
          <div v-reveal="2" class="case__more">
            <p class="glass case__note" data-stagger><strong>Its own design system.</strong> {{ raqeeb.system }}</p>
            <p class="glass case__note" data-stagger><strong>Why there are no screens.</strong> {{ raqeeb.limit }}</p>
          </div>
        </div>
        <div v-reveal="1" class="case__visual">
          <!-- Withheld until launch: a design-system board with every part redacted -->
          <div class="redact glass" role="img" aria-label="Raqeeb’s design system board, with every detail withheld until launch">
            <div class="redact__side"><i></i><i></i><i></i><i></i><i></i><i></i></div>
            <div class="redact__main">
              <span class="r-title"></span>
              <span class="r-lines"><i></i><i></i><i></i></span>
              <span class="r-swatches"><i></i><i></i><i></i><i></i><i></i><i></i></span>
              <span class="r-type"><i></i><i></i><i></i><i></i></span>
              <span class="r-comps sel"><i></i><i></i><i></i><i></i></span>
              <span class="r-table"><i></i><i></i><i></i><i></i></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
