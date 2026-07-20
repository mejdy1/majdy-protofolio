import { computed, ref, type Ref, unref } from 'vue'
import {
  profile,
  projects,
  experience,
  certifications,
  socialLinks,
  siteSettings,
} from '@/data/content'
import type { Project } from '@/types/api'

export const useProfile = () => ({
  data: computed(() => profile),
})

export const useProjects = (opts: { featured?: boolean; limit?: number } = {}) =>
  ({
    data: computed(() => {
      let list = projects
      if (typeof opts.featured === 'boolean') {
        list = list.filter((p) => p.featured === opts.featured)
      }
      if (opts.limit != null) {
        list = list.slice(0, opts.limit)
      }
      return list
    }),
  })

export const useProject = (slug: Ref<string> | string) =>
  ({
    data: computed<Project | undefined>(() =>
      projects.find((p) => p.slug === unref(slug)),
    ),
  })

export const useExperience = () => ({
  data: computed(() => experience),
})

export const useCertifications = () => ({
  data: computed(() => certifications),
})

export const useSocialLinks = () => ({
  data: computed(() => socialLinks),
})

export const useSettings = () => ({
  data: computed(() => siteSettings),
})

type ContactPayload = {
  name: string
  email: string
  subject?: string
  message: string
  website?: string
}

type MutateOptions = {
  onSuccess?: () => void
  onError?: (message?: string) => void
}

/** Replace with your free key from https://web3forms.com */
const WEB3FORMS_ACCESS_KEY = '7f47c5fc-c0ca-424d-82a8-86828848a68e'
const WEB3FORMS_ENDPOINT = 'https://api.web3forms.com/submit'

export const useContactMutation = () => {
  const isPending = ref(false)

  const mutate = async (payload: ContactPayload, opts?: MutateOptions) => {
    // Honeypot — bots that fill hidden fields get a silent "success"
    if (payload.website) {
      opts?.onSuccess?.()
      return
    }

    isPending.value = true

    try {
      const res = await fetch(WEB3FORMS_ENDPOINT, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify({
          access_key: WEB3FORMS_ACCESS_KEY,
          name: payload.name,
          email: payload.email,
          subject: payload.subject?.trim() || 'Portfolio inquiry',
          message: payload.message,
          from_name: 'Majdi Portfolio',
          replyto: payload.email,
        }),
      })

      const data = (await res.json().catch(() => null)) as
        | { success?: boolean; message?: string }
        | null

      if (!res.ok || !data?.success) {
        throw new Error(data?.message || 'Submission failed')
      }

      opts?.onSuccess?.()
    } catch (err) {
      const message =
        err instanceof Error && err.message
          ? err.message
          : 'Something went wrong — please try again.'
      opts?.onError?.(message)
    } finally {
      isPending.value = false
    }
  }

  return { mutate, isPending }
}
