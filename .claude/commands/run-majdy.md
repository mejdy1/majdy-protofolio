---
description: Run the Majdy portfolio (Laravel backend + Vue frontend) from the main project folder
allowed-tools: Bash, Read
---

Run the Majdy portfolio dev servers. The project root is **`/home/husain/majdy-protofolio`** — always operate there with absolute paths, regardless of the current working directory. `dev.sh` boots Laravel (API + Filament admin) on **:8770** and Vite (Vue site) on **:5273**.

## Procedure

1. **Free the ports** so a previous run doesn't collide. Do NOT `pkill -f` with a pattern like `vite` or `artisan serve` — the pattern can match this very shell command and kill it. Instead kill by listening PID:

   ```bash
   for p in 8770 5273; do
     pid=$( (ss -ltnp 2>/dev/null || true) | grep ":$p " | grep -o 'pid=[0-9]*' | head -1 | cut -d= -f2)
     [ -n "$pid" ] && kill "$pid" 2>/dev/null || true
   done
   sleep 1
   ```

2. **Dependency guard** (normally a no-op — deps are already installed; only act if something is missing):
   - `/home/husain/majdy-protofolio/backend/vendor` absent → `composer install` in `backend/`.
   - `/home/husain/majdy-protofolio/frontend/node_modules` absent → `npm install` in `frontend/`.
   - `backend/.env` or `backend/database/database.sqlite` absent → follow the **First‑time setup** section of `/home/husain/majdy-protofolio/README.md` (cp .env.example, key:generate, touch sqlite, migrate:fresh --seed, storage:link, npm build).

3. **Launch in the background** with a single Bash call using `run_in_background: true` so both servers keep running across turns:

   ```bash
   cd /home/husain/majdy-protofolio && ./dev.sh
   ```

4. **Verify.** Wait ~5s (`sleep 5`), then check both servers are up:
   - `curl -s -o /dev/null -w "%{http_code}" http://localhost:5273` → expect `200`
   - `curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8770/up` → expect `200`

   If either isn't `200` yet, `sleep 4` and retry once — the first Vite/Laravel boot can be slow.

5. **Report** briefly. Lead with the fact that it's running, then the two URLs:
   - **Site → http://localhost:5273**
   - **Admin → http://127.0.0.1:8770/admin** (login `majdy@portfolio.test` / `password`)
   - To stop or restart: re-run `/run-majdy` (it frees the ports first), or kill by PID as in step 1.

   Note: the API caches responses for ~60s, so admin edits can take up to a minute to appear on the public site.

Keep the final message short.
