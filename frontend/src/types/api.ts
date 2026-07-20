export interface Profile {
  name: string
  headline: string
  tagline: string | null
  bio: string | null
  location: string | null
  email: string | null
  phone: string | null
  avatar_url: string | null
  hero_image_url: string | null
  resume_url: string | null
  available_for_hire: boolean
  roles: string[]
}

export interface Project {
  id: number
  title: string
  slug: string
  summary: string
  cover_url: string | null
  logo_url?: string | null
  stack: string[]
  year: number | null
  featured: boolean
  client: string | null
  role: string | null
  live_url: string | null
  repo_url: string | null
  body?: string
  problem?: string | null
  solution?: string | null
  gallery?: string[]
}

export interface Experience {
  id: number
  role: string
  company: string
  company_url: string | null
  location: string | null
  start_date: string
  end_date: string | null
  current: boolean
  description: string | null
  achievements: string[]
  stack: string[]
}

export interface Education {
  id: number
  school: string
  degree: string
  field: string | null
  start_date: string
  end_date: string | null
  description: string | null
}

export interface Testimonial {
  id: number
  author: string
  role: string | null
  company: string | null
  avatar_url: string | null
  quote: string
  rating: number
  featured: boolean
  letter_url: string | null
}

export interface Certification {
  id: number
  title: string
  issuer: string
  issue_date: string | null
  expiry_date: string | null
  credential_id: string | null
  credential_url: string | null
  file_url: string | null
  preview_url?: string | null
  featured: boolean
  kind?: 'education' | 'certificate'
}

export interface SocialLink {
  id: number
  platform: string
  url: string
  handle: string | null
  icon: string | null
}

export interface SiteSettings {
  seo: { title: string | null; description: string | null }
  theme: { accent: string; accent_2: string }
  contact: { email: string | null }
}
