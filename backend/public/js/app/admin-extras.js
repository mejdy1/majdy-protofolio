/**
 * Codex Admin — Alpine enhancements
 *  - Cmd/Ctrl+K command palette (restyled as a card catalog)
 *  - Magnetic primary buttons
 *  - Candle flicker in sidebar (auto-injected)
 */
document.addEventListener('alpine:init', () => {
  Alpine.directive('magnetic', (el) => {
    const strength = 14
    const onMove = (e) => {
      const r = el.getBoundingClientRect()
      const x = e.clientX - (r.left + r.width / 2)
      const y = e.clientY - (r.top + r.height / 2)
      el.style.transform = `translate(${x / strength}px, ${y / strength}px)`
    }
    const reset = () => { el.style.transform = '' }
    el.addEventListener('mousemove', onMove)
    el.addEventListener('mouseleave', reset)
  })
})

// --- Command palette --------------------------------------------------
;(() => {
  if (window.__codexPaletteInit) return
  window.__codexPaletteInit = true

  const items = [
    { label: 'Dashboard',          href: '/admin' },
    { label: 'Profile',            href: '/admin/profile' },
    { label: 'Site settings',      href: '/admin/site-settings' },
    { label: 'Projects',           href: '/admin/projects' },
    { label: 'New project',        href: '/admin/projects/create' },
    { label: 'Skills',             href: '/admin/skills' },
    { label: 'Experiences',        href: '/admin/experiences' },
    { label: 'Education',          href: '/admin/education' },
    { label: 'Services',           href: '/admin/services' },
    { label: 'Testimonials',       href: '/admin/testimonials' },
    { label: 'Posts',              href: '/admin/posts' },
    { label: 'New post',           href: '/admin/posts/create' },
    { label: 'Categories',         href: '/admin/categories' },
    { label: 'Contact messages',   href: '/admin/contact-messages' },
    { label: 'Social links',       href: '/admin/social-links' },
  ]

  const css = `
    :root { --ink: #0E0E14; --ink-2:#16161C; --bone:#E9E2D0; --bone-dim:#BAB2A0;
            --vermilion:#E84D3D; --vermdeep:#C4382C; --parchment:#F2E8D5; }
    #codex-palette { position:fixed; inset:0; display:none; z-index:9999;
      background: rgb(0 0 0 / .55); backdrop-filter: blur(6px);
      align-items: flex-start; justify-content: center; padding-top: 14vh; }
    #codex-palette .card {
      width: min(620px, 92vw); background: var(--ink-2);
      border: 1px solid rgba(233,226,208,.18);
      box-shadow: 0 30px 80px -20px rgb(0 0 0 / .65), 2px 2px 0 var(--vermdeep);
      position: relative; overflow: hidden;
      animation: codex-rise .25s cubic-bezier(.16,.84,.44,1.05) both;
    }
    #codex-palette .card::before, #codex-palette .card::after {
      content:''; position:absolute; width:18px; height:18px;
    }
    #codex-palette .card::before { top:0; left:0; border-top:1.5px solid var(--vermilion); border-left:1.5px solid var(--vermilion); }
    #codex-palette .card::after  { bottom:0; right:0; border-bottom:1.5px solid var(--vermilion); border-right:1.5px solid var(--vermilion); }

    #codex-palette .hdr {
      display:flex; align-items:center; gap:12px; padding:18px 22px;
      border-bottom: 1px dashed rgba(233,226,208,.18);
    }
    #codex-palette .hdr .tag {
      font-family: 'IBM Plex Mono', monospace; font-size:10px;
      letter-spacing:.22em; text-transform: uppercase;
      color: var(--parchment); background: var(--vermilion); padding:3px 8px;
    }
    #codex-palette input {
      flex:1; background: transparent; border:0; outline:0; color: var(--bone);
      font: 500 16px/1.4 'Fraunces', serif; font-style: italic;
      font-variation-settings: 'opsz' 30;
    }
    #codex-palette input::placeholder { color: var(--bone-dim); font-style: italic; }
    #codex-palette kbd {
      font: 600 10px/1 'IBM Plex Mono', monospace; color: var(--bone-dim);
      padding:3px 7px; border:1px solid rgba(233,226,208,.15); border-radius:2px;
    }
    #codex-palette ul { list-style:none; margin:0; padding:6px;
      max-height: 55vh; overflow:auto; }
    #codex-palette li {
      padding: 10px 16px; cursor:pointer; color: var(--bone); font-size:14px;
      display:flex; align-items:center; justify-content:space-between;
      font-family: 'General Sans', sans-serif;
      border-bottom: 1px dotted rgba(233,226,208,.07);
    }
    #codex-palette li.active {
      background: rgba(232,77,61,.12);
      color: var(--parchment);
    }
    #codex-palette li.active::before {
      content:'▸'; color: var(--vermilion); margin-right:6px;
    }
    #codex-palette li span.path {
      font-family: 'IBM Plex Mono', monospace; font-size:11px;
      color: var(--bone-dim); letter-spacing:.04em;
    }
    @keyframes codex-rise { from { opacity: 0; transform: translateY(8px) scale(.98); }
                            to   { opacity: 1; transform: none; } }
  `

  const style = document.createElement('style')
  style.textContent = css
  document.head.appendChild(style)

  const wrap = document.createElement('div')
  wrap.id = 'codex-palette'
  wrap.innerHTML = `
    <div class="card">
      <div class="hdr">
        <span class="tag">⌘K · Card Catalog</span>
        <input id="codex-palette-input" placeholder="Search the codex…" />
        <kbd>ESC</kbd>
      </div>
      <ul id="codex-palette-list"></ul>
    </div>
  `
  document.body.appendChild(wrap)

  const input = wrap.querySelector('#codex-palette-input')
  const list  = wrap.querySelector('#codex-palette-list')
  let active = 0

  function render(query='') {
    const q = query.trim().toLowerCase()
    const filtered = items.filter(i => i.label.toLowerCase().includes(q))
    list.innerHTML = filtered.map((it, i) => `
      <li data-idx="${i}" data-href="${it.href}" class="${i===active?'active':''}">
        <span>${it.label}</span>
        <span class="path">${it.href}</span>
      </li>`).join('') || `<li style="color: var(--bone-dim); padding:18px 16px;">No entries found.</li>`
    list.querySelectorAll('li[data-idx]').forEach(li => {
      li.addEventListener('mouseenter', () => { active = +li.dataset.idx; render(input.value) })
      li.addEventListener('click',      () => { window.location.href = li.dataset.href })
    })
  }

  function open()  { wrap.style.display = 'flex'; input.value=''; active=0; render(); setTimeout(()=>input.focus(),0) }
  function close() { wrap.style.display = 'none' }

  document.addEventListener('keydown', (e) => {
    const cmd = e.metaKey || e.ctrlKey
    if (cmd && e.key.toLowerCase() === 'k') { e.preventDefault(); open() }
    if (wrap.style.display === 'flex') {
      const visible = [...list.querySelectorAll('li[data-idx]')]
      if (e.key === 'Escape')      { close() }
      if (e.key === 'ArrowDown')   { active = Math.min(active+1, visible.length-1); render(input.value); e.preventDefault() }
      if (e.key === 'ArrowUp')     { active = Math.max(active-1, 0); render(input.value); e.preventDefault() }
      if (e.key === 'Enter' && visible[active]) { window.location.href = visible[active].dataset.href }
    }
  })
  wrap.addEventListener('click', (e) => { if (e.target === wrap) close() })
  input.addEventListener('input', () => { active = 0; render(input.value) })
})()

// --- Tiny candle flicker decoration in sidebar -------------------------
;(() => {
  if (window.__codexCandleInit) return
  window.__codexCandleInit = true

  const SVG = `
  <span style="display:flex;align-items:center;gap:10px;padding:14px 18px;border-top:1px solid rgba(233,226,208,.08);font-family:'IBM Plex Mono',monospace;font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#BAB2A0;">
    <svg width="14" height="20" viewBox="0 0 14 20" aria-hidden="true">
      <rect x="3" y="8" width="8" height="10" fill="#E9E2D0" opacity=".75"/>
      <rect x="3" y="8" width="8" height="2" fill="#C4382C"/>
      <path d="M7 2 C 9 4 9 6 7 8 C 5 6 5 4 7 2 Z" fill="#E8C547">
        <animate attributeName="opacity" values="1;.7;1;.9;1" dur="2.2s" repeatCount="indefinite"/>
        <animateTransform attributeName="transform" type="scale" values="1 1;1.06 0.94;1 1;1.04 0.96;1 1" dur="2.2s" repeatCount="indefinite" additive="sum"/>
      </path>
      <line x1="7" y1="6" x2="7" y2="7.5" stroke="#C4382C" stroke-width="0.6"/>
    </svg>
    <span>Lit by candle · {{__codex_lit__}}</span>
  </span>
  `.replace('{{__codex_lit__}}', new Date().getFullYear())

  function inject() {
    const sidebar = document.querySelector('.fi-sidebar-nav')
    if (sidebar && !sidebar.dataset.codexCandle) {
      sidebar.dataset.codexCandle = '1'
      const div = document.createElement('div')
      div.innerHTML = SVG
      sidebar.appendChild(div)
    }
  }
  // Filament navigates with Livewire; observe DOM
  const mo = new MutationObserver(inject)
  mo.observe(document.body, { childList: true, subtree: true })
  setTimeout(inject, 400)
})()
