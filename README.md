# Majdi Al-Kediwi — Portfolio

Personal portfolio of Majdi Al-Kediwi, product designer (UI/UX). A static **Vue 3 + Vite + TypeScript** single page: no backend, no database, no CMS.

```
majdy-protofolio/
├── content/    source files: the CV (Majdi_AlKediwi_CV.pdf) and original project images
└── frontend/   the site
```

## Run

```bash
npm install --prefix frontend
npm run dev
```

Site: http://localhost:5273

## Build

```bash
npm run build
```

Output: `frontend/dist/` (static files; any static host works).

## Page order

1. Hero: the name, bio and a case-study index on the left; a glass workspace of project tiles on the right (each tile scrolls to its case study)
2. Case study 01 — Rasid
3. Case study 02 — ArabaTech (Smart Grocery Cart System), the graduation project that won first place at Injaz 2026, on the one light band; its gallery includes the poster and the trophy
4. Case study 03 — Raqeeb, the current personal project (no screens until launch)
5. Two more projects — UI Hub (university project) and Health Volunteering (app redesign on the DGA design system)
6. How I work with product teams — collaboration diagram, five abilities, tools
7. Experience and education — Gantt timeline, then the roles
8. Certificates
9. Close and footer — Download CV, email, LinkedIn

## Edit content

All copy, facts and image lists live in `frontend/src/data/content.ts`.

- **CV:** `frontend/public/cv/Majdi-Al-Kediwi-CV.pdf` (copied from `content/Majdi_AlKediwi_CV.pdf`). Replace the file to update it.
- **Project images:** `frontend/public/work/<project>/`, WebP, each with a `-sm` thumbnail. Add a shot to a project's `shots` list in `content.ts`.
- **Certificates:** PDFs in `frontend/public/certifications/`, previews in `previews/`. The graduation certificate preview has the student and national ID numbers blacked out; keep it that way if you replace it.
- **Raqeeb:** text only until launch; when it ships, give it `shots` and switch it to the `CaseSection` component like Rasid.

## Design

- **Palette (kept from the previous site):** near-black ground `#080A10`, cool ink `#E9EEF8`, one royal-blue accent (`#3B6EF6`, buttons `#2556E0`). Tokens: `frontend/src/styles/tokens.css`.
- **Type:** Newsreader (headings) and Hanken Grotesk (text), from Google Fonts.
- **Motif:** the design-tool selection frame (blue outline, white handles) marks the chosen frame in the hero, the active thumbnail, linked pictures on hover and focus, and the Raqeeb board.
- **Layout:** one 1280px wrap on a 12-column grid; each section fills one or two screens at 1440×900; case studies split 5/7 (story left, work right) and stack on tablets and phones.
- **Images:** every gallery opens a full-screen viewer (arrows, thumbnails, keyboard ←/→/Esc, swipe on touch).
- **Glass and glow:** content cards use `.glass` (translucent gradient, blur, a light that follows the pointer, a lift on hover); soft blue glows sit behind each section. Project logos sit beside each heading (`ProjectMark.vue`).
- **Motion** (`src/composables/useMotion.ts`, the motion block at the end of `src/style.css`): the hero enters on load; sections reveal once as they scroll in (`v-reveal`, children marked `data-stagger`); timeline bars draw in; smooth scrolling between sections; the nav underlines the section on screen and a line under it tracks progress. Everything is switched off for visitors who ask for reduced motion (`html.has-motion` is set in `index.html` only when they don't).
