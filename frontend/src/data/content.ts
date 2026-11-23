/* All site copy and media in one place. Facts come from the CV in /content
   (Majdi_AlKediwi_CV.pdf) and the project files; nothing here is invented. */

export interface Shot {
  src: string
  thumb: string
  alt: string
  width: number
  height: number
}

export interface Fact {
  label: string
  value: string
}

export interface Logo {
  src: string
  /* the tile behind the mark: dark (default), light, or the logo fills it */
  ground?: 'dark' | 'light' | 'fill'
}

export interface CaseStudy {
  id: string
  index: string
  kicker: string
  logo: Logo
  title: string
  award?: string
  summary: string
  facts: Fact[]
  did: string[]
  shots: Shot[]
}

export interface Concept {
  id: string
  logo: Logo
  title: string
  summary: string
  tags: string
  shots: Shot[]
}

export interface Skill {
  title: string
  body: string
}

export interface Role {
  org: string
  role: string
  dates: string
  body: string
  /* months from January 2025 (0) to November 2026 (22), for the timeline */
  from: number
  to: number
  ongoing?: boolean
  before?: boolean
  point?: boolean
}

export interface Certificate {
  title: string
  issuer: string
  date: string
  verify?: string
  file?: string
  preview?: string
  width?: number
  height?: number
}

const w = (path: string) => ({ src: `/work/${path}.webp`, thumb: `/work/${path}-sm.webp` })

export const profile = {
  name: 'Majdi Al-Kediwi',
  role: 'Product designer (UI/UX)',
  location: 'Riyadh, Saudi Arabia',
  email: 'mmkediwi@gmail.com',
  linkedin: 'https://www.linkedin.com/in/majdi-alkediwi',
  linkedinHandle: 'in/majdi-alkediwi',
  cv: '/cv/Majdi-Al-Kediwi-CV.pdf',
  cvFileName: 'Majdi-Al-Kediwi-CV.pdf',
}

export const hero = {
  notice: 'Open to product designer roles in Riyadh',
  name: 'Majdi Al-Kediwi',
  role: 'product designer',
  lead:
    'I hold a bachelor’s in Human-Computer Interaction from Umm Al-Qura University, and I take products from user research to high-fidelity prototypes and accessible interfaces engineers can build. Our ArabaTech cart won first place at Injaz 2026.',
}

export const cases: CaseStudy[] = [
  {
    id: 'rasid',
    index: '01',
    kicker: 'Case study 01 · Co-founder and UX lead',
    logo: { src: '/work/rasid/logo.svg' },
    title: 'Rasid turns lab results into live outbreak maps',
    summary:
      'Public-health teams handled outbreak data without one shared tool, which made a real-time response hard. Rasid brings sequencing, diagnosis and surveillance maps into one dashboard for decision-makers.',
    facts: [
      { label: 'Role', value: 'Co-founder and UX lead' },
      { label: 'Timeline', value: 'March 2025 to August 2026' },
      { label: 'Team', value: 'Developers, researchers, public-health stakeholders' },
    ],
    did: [
      'Built and maintained the design system: components, UI patterns and accessibility rules shared with engineering.',
      'Mapped public-sector workflows with researchers into user journeys, information architecture and dashboard flows.',
      'Owned the roadmap and sprint planning, so developers, researchers and stakeholders worked to one set of priorities.',
    ],
    shots: [
      { ...w('rasid/home'), alt: 'Rasid home page: National Epidemic Intelligence System, with coverage of 13 Saudi regions', width: 2000, height: 1196 },
      { ...w('rasid/pipeline'), alt: 'Rasid solutions: DNA sequencing, AI clinical diagnosis and real-time surveillance maps', width: 1874, height: 1187 },
      { ...w('rasid/map'), alt: 'Rasid live surveillance map of Saudi Arabia with active cases and alerts', width: 699, height: 519 },
      { ...w('rasid/brand'), alt: 'Rasid identity: the Arabic and Latin wordmark beside the dotted map mark', width: 1883, height: 1233 },
    ],
  },
  {
    id: 'arabatech',
    index: '02',
    kicker: 'Case study 02 · Graduation project',
    logo: { src: '/work/smart-cart/logo.webp', ground: 'fill' },
    title: 'ArabaTech, an AI grocery cart you talk to',
    award: 'First place, Injaz 2026 exhibition, AI programme (student track), College of Computing, Umm Al-Qura University',
    summary:
      'Grocery shopping suffers from hard navigation, language barriers and accessibility gaps. ArabaTech pairs a voice interface with in-store navigation and personal recommendations on the cart’s screen, so a shopper can ask it to lead the way to an item.',
    facts: [
      { label: 'Role', value: 'Product and project lead' },
      { label: 'Timeline', value: 'January 2025 to June 2026' },
      { label: 'Context', value: 'Smart Grocery Cart System, a team graduation project at Umm Al-Qura University' },
    ],
    did: [
      'Led user research and journey mapping, then took the product through to high-fidelity prototypes.',
      'Designed the voice interface alongside the screen UI, and worked with engineers on what the hardware could support.',
      'Ran usability testing rounds that simplified the in-store journey and reduced checkout friction.',
    ],
    shots: [
      { ...w('smart-cart/voice-nav'), alt: 'ArabaTech screen: offers, the shopping list and the store map answering “Navigate me to the yogurt”', width: 1092, height: 549 },
      { ...w('smart-cart/store-theme'), alt: 'ArabaTech screen in a second store’s colours, the white-label version, with the voice button ready', width: 1093, height: 550 },
      { ...w('smart-cart/poster'), alt: 'ArabaTech project poster: abstract, opportunity, features, system flow, contributions, tools, methodology and the prototype screens', width: 1439, height: 2020 },
      { ...w('smart-cart/award'), alt: 'The first-place trophy for ArabaTech, Smart Grocery Cart System, at the Injaz 2026 exhibition', width: 1200, height: 1607 },
    ],
  },
]

export const raqeeb = {
  id: 'raqeeb',
  kicker: 'Case study 03 · Personal project, in progress',
  title: 'Raqeeb, the project I’m building now',
  intro:
    'Raqeeb is my current personal project. It is built for one clearly defined group of users, and its design starts from what that group needs. I design and build it, from research and the interface to the code.',
  facts: [
    { label: 'Role', value: 'Software and UX engineer' },
    { label: 'Type', value: 'Personal project' },
    { label: 'Status', value: 'In progress, not launched' },
  ] as Fact[],
  system:
    'Raqeeb has a design system of its own, made for its audience and grounded in detailed, careful research rather than adapted from a general kit.',
  limit:
    'Raqeeb has not launched, so for professional reasons I can’t share its details or a finished design presentation yet. The full case study follows the launch.',
}

export const concepts = {
  id: 'concepts',
  kicker: 'Case study 04 · Two more projects',
  title: 'A UI/UX community and an app redesign.',
  titleMuted: 'One for the web, one for phones.',
  items: [
    {
      id: 'ui-hub',
      logo: { src: '/work/ui-hub/logo.png', ground: 'light' },
      title: 'UI Hub, a community and book market for UI/UX',
      summary:
        'A university project built in HTML, CSS and JavaScript to accessibility standards. It gathers people interested in UI and UX, and runs a marketplace where members sell their books and earn from the platform.',
      tags: 'Web · University project · HTML, CSS, JavaScript · Accessibility',
      shots: [
        { ...w('ui-hub/feed'), alt: 'UI Hub article feed with a featured piece on Apple’s Human Interface Guidelines', width: 1497, height: 970 },
        { ...w('ui-hub/insights'), alt: 'UI Hub “HCI in Focus” page with snippets from Interaction Design and The Design of Everyday Things', width: 1496, height: 971 },
      ],
    },
    {
      id: 'health-volunteering',
      logo: { src: '/work/volunteering/logo.webp', ground: 'light' },
      title: 'Health Volunteering, redesigned on the DGA design system',
      summary:
        'Further development of an existing volunteering app. I redesigned its interfaces to match the platform’s codebase and the Digital Government Authority (DGA) design system: opportunities, urgent calls, a map of sites, group chats and the volunteer profile.',
      tags: 'Mobile · Arabic · Redesign of an existing app · DGA design system',
      shots: [
        { ...w('volunteering/1-sign-in'), alt: 'Health Volunteering sign-in screen in Arabic', width: 536, height: 1176 },
        { ...w('volunteering/2-opportunities'), alt: 'Volunteer opportunities list with active, upcoming and finished tabs', width: 535, height: 1169 },
        { ...w('volunteering/3-calls'), alt: 'Urgent calls for volunteers, each with a priority mark and a location link', width: 533, height: 1168 },
        { ...w('volunteering/4-map'), alt: 'Map of volunteering sites around Makkah', width: 536, height: 1170 },
        { ...w('volunteering/5-chats'), alt: 'Group chats for volunteer teams by area and level', width: 536, height: 1168 },
        { ...w('volunteering/6-profile'), alt: 'Volunteer profile with volunteer number, certificates and contact details', width: 534, height: 1169 },
      ],
    },
  ] as Concept[],
}

export const skills = {
  id: 'skills',
  title: 'How I work with product teams',
  intro: 'Besides the interface itself, this is what I bring to a team, and where I have done it.',
  items: [
    {
      title: 'Product thinking',
      body: 'I scope to the smallest useful version. I practised discovery, business-model checks and MVP scoping at KnowledgeX, and owned Rasid’s roadmap.',
    },
    {
      title: 'Research and testing',
      body: 'I map journeys before I draw screens, and test with users in rounds: ArabaTech’s rounds simplified the whole in-store journey.',
    },
    {
      title: 'Communication',
      body: 'At Rasid I kept developers, researchers and stakeholders aligned, and turned technical constraints into requirements each of them could act on.',
    },
    {
      title: 'Technical fluency',
      body: 'Beyond HTML, CSS and JavaScript, I build front ends in Vue, React and Flutter and back ends in Laravel and Python, so my designs match what engineers can ship.',
    },
    {
      title: 'AI in the work',
      body: 'I use AI tools such as Claude and Codex to speed up the design and build process, designed the voice interface of an AI cart, and studied DeepLearning.AI’s maths for machine learning.',
    },
  ] as Skill[],
  hub: {
    centre: 'Product design',
    nodes: [
      { name: 'Users', flow: 'Research and usability tests' },
      { name: 'Researchers', flow: 'Journeys and information architecture' },
      { name: 'Developers', flow: 'Design system, handoff, front-end code' },
      { name: 'Stakeholders', flow: 'Roadmap and sprint priorities' },
    ],
  },
  tools: [
    { name: 'Figma', icon: '/icons/figma.svg', w: 27, h: 40 },
    { name: 'HTML', icon: '/icons/html-5.svg', w: 28, h: 40 },
    { name: 'CSS', icon: '/icons/css.svg', w: 40, h: 40 },
    { name: 'JavaScript', icon: '/icons/javascript.svg', w: 40, h: 40 },
    { name: 'Vue', icon: '/icons/vue.svg', w: 40, h: 35 },
    { name: 'React', icon: '/icons/react.svg', w: 40, h: 36 },
    { name: 'Flutter', icon: '/icons/flutter.svg', w: 33, h: 40 },
    { name: 'Laravel', icon: '/icons/laravel.svg', w: 39, h: 40 },
    { name: 'Python', icon: '/icons/python.svg', w: 40, h: 40 },
    { name: 'SQL', icon: '/icons/sql.svg', w: 40, h: 40 },
  ],
  toolsLine: 'Figma, HTML, CSS, JavaScript, Vue, React, Flutter, Laravel, Python and SQL.',
  ai: [
    { name: 'Claude', icon: '/icons/claude.svg', w: 40, h: 40 },
    { name: 'Codex', icon: '/icons/codex.svg', w: 40, h: 40, light: true },
  ],
  aiLine: 'AI tools such as Claude and Codex speed up my design and build process.',
}

export const experience = {
  id: 'experience',
  title: 'Experience and education',
  intro: 'Rasid, KnowledgeX and ArabaTech ran alongside my degree. Since then I have been building Raqeeb.',
  roles: [
    {
      org: 'Raqeeb',
      role: 'Software and UX engineer, personal project',
      dates: 'In progress',
      body: 'A product for one clearly defined audience, on a design system of its own built from detailed research. Details follow the launch.',
      from: 22,
      to: 22,
      point: true,
    },
    {
      org: 'Rasid',
      role: 'Co-founder and UX lead',
      dates: 'Mar 2025 – Aug 2026',
      body: 'Led UX strategy and product design for a data platform that turns epidemiological datasets into real-time crisis dashboards for public-health decision-makers.',
      from: 2,
      to: 19,
    },
    {
      org: 'KnowledgeX',
      role: 'UX designer and front-end developer',
      dates: 'Mar – Aug 2025',
      body: 'A six-month programme in UX design, front-end development and product entrepreneurship: research, usability testing, React builds and MVP scoping in agile teams.',
      from: 2,
      to: 7,
    },
    {
      org: 'ArabaTech (Smart Cart)',
      role: 'Product and project lead, graduation project',
      dates: 'Jan 2025 – Jun 2026',
      body: 'End-to-end design of an AI grocery cart with a voice interface, from research and journey maps to tested high-fidelity prototypes. First place at Injaz 2026.',
      from: 0,
      to: 17,
    },
    {
      org: 'Umm Al-Qura University, Makkah',
      role: 'BSc Human-Computer Interaction',
      dates: 'Graduated Jun 2026',
      body: 'GPA 3.45 of 4.00, second-class honours. Focus on software engineering, user-centred design and usability engineering.',
      from: 0,
      to: 17,
      before: true,
    },
  ] as Role[],
}

const cert = (id: string) => ({
  file: `/certifications/Coursera-${id}.pdf`,
  preview: `/certifications/previews/Coursera-${id}.webp`,
})

export const credentials = {
  id: 'credentials',
  title: 'Degree and certificates',
  featured: [
    {
      title: 'Bachelor’s in Human-Computer Interaction',
      issuer: 'Umm Al-Qura University · GPA 3.45 of 4.00, second-class honours',
      date: 'June 2026',
      preview: '/certifications/previews/graduation-certificate.webp',
      width: 1440,
      height: 1016,
    },
    {
      title: 'Google UX Design Professional Certificate',
      issuer: 'Google, on Coursera · seven courses',
      date: 'January 2025',
      verify: 'https://www.coursera.org/verify/professional-cert/4LUYDDIWCETL',
      ...cert('4LUYDDIWCETL'),
    },
    {
      title: 'Linear Algebra for Machine Learning and Data Science',
      issuer: 'DeepLearning.AI, on Coursera',
      date: 'November 2025',
      verify: 'https://www.coursera.org/verify/DR426L83ST2H',
      ...cert('DR426L83ST2H'),
    },
    {
      title: 'Calculus for Machine Learning and Data Science',
      issuer: 'DeepLearning.AI, on Coursera',
      date: 'December 2025',
      verify: 'https://www.coursera.org/verify/T5MYP26559SU',
      ...cert('T5MYP26559SU'),
    },
  ] as Certificate[],
  courses: [
    { title: 'Foundations of User Experience (UX) Design', date: 'Dec 2024', verify: 'https://www.coursera.org/verify/RYQPF7X1OPR8' },
    { title: 'Start the UX Design Process: Empathize, Define, and Ideate', date: 'Jan 2025', verify: 'https://www.coursera.org/verify/MXL63NPU7X74' },
    { title: 'Build Wireframes and Low-Fidelity Prototypes', date: 'Jan 2025', verify: 'https://www.coursera.org/verify/I90G4O8JTE8N' },
    { title: 'Conduct UX Research and Test Early Concepts', date: 'Jan 2025', verify: 'https://www.coursera.org/verify/Y1B9CJ1LBLZX' },
    { title: 'Create High-Fidelity Designs and Prototypes in Figma', date: 'Jan 2025', verify: 'https://www.coursera.org/verify/DQFXM6EEFTMK' },
    { title: 'Build Dynamic User Interfaces (UI) for Websites', date: 'Jan 2025', verify: 'https://www.coursera.org/verify/HIMQKB5FB9YM' },
  ],
}

export const close = {
  title: 'Open to product designer roles',
  line: 'Based in Riyadh, Saudi Arabia. Write to me about a role, or read my CV first.',
}

export const nav = [
  { label: 'Work', href: '#rasid' },
  { label: 'Skills', href: '#skills' },
  { label: 'Experience', href: '#experience' },
  { label: 'Contact', href: '#contact' },
]
