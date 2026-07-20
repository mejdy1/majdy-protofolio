<script setup lang="ts">
import { ref } from 'vue'
import { Icon } from '@iconify/vue'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as zod from 'zod'
import { useProfile, useSocialLinks, useContactMutation } from '@/composables/useApi'
import { useReveal } from '@/composables/useReveal'

const { data: profile } = useProfile()
const { data: socials } = useSocialLinks()
const { mutate, isPending } = useContactMutation()

const sent = ref(false)
const errorMsg = ref('')
const buttonFlash = ref(false)

const schema = toTypedSchema(
  zod.object({
    name: zod.string().min(2, 'Please enter your name'),
    email: zod.string().email('Enter a valid email'),
    subject: zod.string().optional(),
    message: zod.string().min(10, 'A little more detail, please'),
    website: zod.string().optional(), // honeypot
  }),
)

const { handleSubmit, errors, defineField, resetForm } = useForm({ validationSchema: schema })
const [name, nameAttrs] = defineField('name')
const [email, emailAttrs] = defineField('email')
const [subject, subjectAttrs] = defineField('subject')
const [message, messageAttrs] = defineField('message')
const [website, websiteAttrs] = defineField('website')

const onSubmit = handleSubmit(async (values) => {
  errorMsg.value = ''
  buttonFlash.value = false

  await mutate(values, {
    onSuccess: () => {
      resetForm()
      buttonFlash.value = true
      sent.value = true
      window.setTimeout(() => {
        buttonFlash.value = false
      }, 2200)
    },
    onError: (msg) => {
      errorMsg.value = msg || 'Something went wrong — please try again.'
    },
  })
})

const root = ref<HTMLElement | null>(null)
useReveal(root, { threshold: 0.06 })
</script>

<template>
  <section id="contact" ref="root" class="section relative overflow-hidden">
    <div class="container-x">
      <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
        <!-- pitch + socials -->
        <div class="reveal">
          <p class="eyebrow text-accent">Contact</p>
          <h2 class="mt-4 font-display text-[clamp(33px,5.4vw,62px)] font-semibold leading-tight tracking-tighter2">
            Let’s build something.
          </h2>
          <p class="mt-5 max-w-md text-lg leading-relaxed text-text2">
            Open to UX leadership, product design, and impactful product opportunities. Send a message and I’ll reply soon.
          </p>
          <div class="mt-6 flex flex-col gap-3">
            <a v-if="profile?.email" :href="`mailto:${profile.email}`" class="inline-flex items-center gap-2 text-accent transition-all hover:gap-2.5">
              <Icon icon="lucide:mail" class="h-5 w-5" /> {{ profile.email }}
            </a>
            <a v-if="profile?.phone" :href="`tel:${profile.phone}`" class="inline-flex items-center gap-2 text-accent transition-all hover:gap-2.5">
              <Icon icon="lucide:phone" class="h-5 w-5" /> {{ profile.phone }}
            </a>
          </div>
          <div class="mt-8 flex items-center gap-3">
            <a
              v-for="s in socials" :key="s.id" :href="s.url" target="_blank" rel="noopener"
              class="grid h-11 w-11 place-items-center rounded-full border border-line/15 text-text2 transition-colors hover:border-accent/40 hover:text-accent" :aria-label="s.platform"
            >
              <Icon :icon="s.icon || 'lucide:link'" class="h-5 w-5" />
            </a>
          </div>
        </div>

        <!-- form -->
        <div class="reveal" style="transition-delay:.12s">
          <Transition mode="out-in" enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-2">
            <div v-if="sent" key="done" class="glow-card flex min-h-[340px] flex-col items-center justify-center p-10 text-center shadow-[0_0_40px_rgb(var(--glow-rgb)/0.22)]">
              <span class="grid h-14 w-14 place-items-center rounded-full bg-accent/12 text-accent shadow-[0_0_24px_rgb(var(--glow-rgb)/0.35)]">
                <Icon icon="lucide:check" class="h-7 w-7" />
              </span>
              <h3 class="mt-5 font-display text-2xl font-semibold">Message Sent Successfully! ✓</h3>
              <p class="mt-2 text-text2">Thanks for reaching out — I’ll get back to you shortly.</p>
              <button type="button" @click="sent = false" class="mt-6 text-sm text-accent hover:underline">Send another</button>
            </div>

            <form v-else key="form" @submit.prevent="onSubmit" class="glow-card space-y-4 p-7" novalidate>
              <input v-model="website" v-bind="websiteAttrs" type="text" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true" />
              <div class="grid gap-4 sm:grid-cols-2">
                <div>
                  <label class="form-label">Name</label>
                  <input v-model="name" v-bind="nameAttrs" type="text" class="form-input" placeholder="Your name" autocomplete="name" />
                  <p v-if="errors.name" class="form-err">{{ errors.name }}</p>
                </div>
                <div>
                  <label class="form-label">Email</label>
                  <input v-model="email" v-bind="emailAttrs" type="email" class="form-input" placeholder="you@email.com" autocomplete="email" />
                  <p v-if="errors.email" class="form-err">{{ errors.email }}</p>
                </div>
              </div>
              <div>
                <label class="form-label">Subject</label>
                <input v-model="subject" v-bind="subjectAttrs" type="text" class="form-input" placeholder="What’s this about?" />
              </div>
              <div>
                <label class="form-label">Message</label>
                <textarea v-model="message" v-bind="messageAttrs" rows="5" class="form-input resize-none" placeholder="Tell me a little about it…"></textarea>
                <p v-if="errors.message" class="form-err">{{ errors.message }}</p>
              </div>
              <p v-if="errorMsg" class="form-err" role="alert">{{ errorMsg }}</p>
              <button
                type="submit"
                :disabled="isPending"
                class="btn-primary w-full disabled:opacity-60"
                :class="buttonFlash ? 'shadow-[0_0_28px_rgb(var(--glow-rgb)/0.55)]' : ''"
              >
                <Icon v-if="isPending" icon="lucide:loader-2" class="h-[18px] w-[18px] animate-spin" />
                <Icon v-else-if="buttonFlash" icon="lucide:check" class="h-[18px] w-[18px]" />
                <Icon v-else icon="lucide:send" class="h-[18px] w-[18px]" />
                {{ isPending ? 'Sending…' : buttonFlash ? 'Message Sent Successfully! ✓' : 'Send message' }}
              </button>
            </form>
          </Transition>
        </div>
      </div>
    </div>
  </section>
</template>
