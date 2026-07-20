# Majdy — Portfolio

A premium, fully responsive personal portfolio for **Majdy**. Static **Vue 3** site — no backend, no database, no CMS.

```
majdy-protofolio/
└── frontend/   Vue 3 + Vite + TypeScript + Tailwind single‑page portfolio
```

Site content lives in `frontend/src/data/content.ts`. PDFs (CV, recommendation letters, certificates) are served from `frontend/public/`.

## Design

**“Royal / ambient blue glow.”** Premium minimalist, deep near‑black dark base and a clean white light base, the **same** blue accent on both, with drifting blue radial blobs, pointer‑tracked card glow, a vertical timeline with interactive blue nodes, and a real **dark / light toggle** (persisted, no first‑paint flash). Type: Clash Display + Satoshi + JetBrains Mono. Icons: lucide (via Iconify).

## Requirements

Node 18+/npm.

## Run

```bash
npm install --prefix frontend
npm run dev
```

- **Site:** http://localhost:5273

Or from the frontend folder:

```bash
cd frontend
npm install
npm run dev
```

## Edit content

Update `frontend/src/data/content.ts` for profile, projects, experience, testimonials, certifications, and social links.

Replace files under:

- `frontend/public/cv/` — resume PDF
- `frontend/public/letters/` — recommendation letter PDFs
- `frontend/public/certifications/` — certificate PDFs

The contact form opens the visitor’s email client (`mailto:`) with the message pre-filled.

## Build for production

```bash
npm run build
```

Output: `frontend/dist/`

## Ports

| App | URL |
|-----|-----|
| Vue site | http://localhost:5273 |
