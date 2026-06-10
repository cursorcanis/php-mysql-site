# Project Changelog

Running log of all infrastructure, configuration, and code changes for this IONOS PHP/MySQL playground.

Format: dated entries, newest at the top. Each entry: what changed, where, why.

---

## 2026-06-10 — Laravel 13 rewrite started (branch `laravel-migration`)

Decision: migrate the flat-PHP Vault to **Laravel 13** (full rewrite), scaffolded
into the `vault/` subfolder. The live flat app at the repo root is left untouched
during the migration. Deploy target stays IONOS; code transfer via FileZilla/SFTP,
but `composer install` + `php artisan migrate` to be run over **SSH** on the server.

### Local toolchain setup (was missing)
- WinGet PHP 8.5 had no `php.ini` → created one enabling `pdo_mysql`, `pdo_sqlite`,
  `mbstring`, `openssl`, `curl`, `fileinfo`, `gd` (tokenizer is compiled-in).
- Installed Composer (`composer.phar` + `composer.bat` shim on PATH).

### Laravel app (`vault/`)
- Migrations: `files` (ports `images` + Phase 1 `file_type`), `tags`, `image_tags` pivot.
- Models `File`/`Tag` with `belongsToMany`; `TagSeeder` seeds the 5 starter tags.
- Controllers: Auth, Dashboard, File, Api (single `/api` dispatcher mirroring old
  `api.php`), Share. Single-user auth via `.env` (`VAULT_USER`/`VAULT_PASS_HASH`) +
  `VaultAuth` middleware. No `users` table / Breeze.
- **Security upgrade:** uploads now live in private `storage/` and are served only
  through access-controlled routes keyed on the random filename — uploaded files can
  never be web-executed (retires the old `uploads/.htaccess` workaround).
- Blade views port the existing dark UI (login, dashboard, share); `<style>` blocks
  wrapped in `@verbatim` so `@media`/`@keyframes` don't parse as directives. PWA
  assets (`manifest.json`, `sw.js`, `icons/`) copied to `public/`.
- Local dev uses SQLite; production uses MySQL via `.env`. Dev login: `alfredo` / `labvault`.

### Verification
- 5 feature tests (`tests/Feature/VaultTest.php`) — login, upload, tag, search,
  share-token, file serving, delete, disallowed-type rejection — all passing (35 assertions).
- Real HTTP smoke test: login → 302 → dashboard 200 with gallery + upload form.

### Not yet done
- Deploy to IONOS (point `lab.alfredoalea.com` docroot → `vault/public`; production `.env`).
- Migrate live `images`/`tags` rows + upload files into the new structure.

---

## 2026-06-02 — Phase 1 deployed to live server (file type support)

### Database migration run on live DB
- Ran in IONOS phpMyAdmin against database `your_db_name`:
  `ALTER TABLE images ADD COLUMN file_type ENUM('image','document','file') NOT NULL DEFAULT 'image' AFTER mime_type;`
- First attempt failed with `#1046 - No database selected` (ran from server-level SQL tab); fixed by selecting the database first, then re-running. Succeeded (empty result set).

### Updated app files uploaded to /lab via FileZilla
- `config.php` (1,729 B) — MAX_FILE_SIZE 10 MB → 100 MB, added `.txt`/`.docx`/`.doc` MIME types + extensions, `get_file_type()` helper.
- `dashboard.php` (37,699 B) — multi-type upload, stores `file_type`, inline previews (img / `<pre>` for txt / Google Docs Viewer for docx).
- Verified both transferred byte-for-byte (remote sizes match local).

### Server hardening
- Added `.htaccess` to block web access to `.md`, `.sql`, `.log`, `.ini`, dotfiles, and `migrate-*.php`/`debug.php`, and disabled directory indexing. Prompted by `CHANGELOG.md`/`README.md` being publicly reachable at `lab.alfredoalea.com/CHANGELOG.md` (leaked DB name, contract, infra notes).
- `debug.php` and `migrate-phase1.php` confirmed NOT present on server `/lab`.
- TODO: delete `CHANGELOG.md` and `README.md` from server `/lab`; consider removing web-readable `error.log`.

### Still pending
- End-to-end test on live: upload image / .txt / .docx and confirm previews, tags, sharing, downloads.

---

## 2026-05-25 — Initial setup

### Hosting environment audited
- Confirmed plan: **IONOS Expert** (contract `14911267`)
- Plan includes: unlimited storage (1.08 GB used / 262,144 file cap, 39k used), unlimited MySQL Standard databases, SSH/SFTP, multi-version PHP, cron jobs, Performance Level 5/5, 2 free SSL slots.
- Identified add-ons currently active: **PHP Extended Support** (paid, only needed for legacy PHP — candidate for cancellation if no site needs it).
- Identified add-ons NOT active and not needed: IONOS CDN (use Cloudflare direct), MyDefender backups, SiteAnalytics, Domain Guard.

### Domain inventory documented
4 owned domains, all included free with contract:
| Domain | Points to | Expires |
|---|---|---|
| alfredoalea.com | Webspace `/bootstrap` | **2026-07-10** ⚠️ check auto-renew |
| alfredoaleasculpture.com | Webspace `/app558467615` (no SSL!) | 2027-05-22 |
| alfredoaleasculpture.org | IONOS Click and Build + Mail | 2027-05-22 |
| pavlinaalea.com | IONOS Website Builder | 2027-03-27 |

### Webspace folder structure documented
Existing folders in webspace root:
- `alfredo_alea_HTML5_portfolio` — legacy
- `app558467615` — alfredoaleasculpture.com
- `app829410247` — **unknown, investigate later**
- `bootstrap` — alfredoalea.com
- `clickandbuilds` — alfredoaleasculpture.org
- `Dreamweaver_Site_Access` — legacy
- `pavlinaalea` — pavlinaalea.com Website Builder
- `.opcache`, `logs` — system folders

### Created subdomain: lab.alfredoalea.com
- Created via Domains & SSL → alfredoalea.com → Subdomains tab
- Created `/lab` folder via Webspace Explorer (had to create folder first; IONOS UI doesn't auto-create on Adjust Destination)
- Pointed `lab.alfredoalea.com` → Webspace → `/lab`
- Absolute filesystem path: `/kunden/homepages/5/d210362041/htdocs/lab`

### Deployed hello-world test
- Uploaded `index.php` (with `phpinfo()` block) to `/lab` via Webspace Explorer
- Verified live at http://lab.alfredoalea.com/
- Confirmed runtime:
  - PHP **8.4.21** (current stable)
  - Server API: **CGI/FastCGI**
  - memory_limit: **unlimited (-1)**
  - max_execution_time: **50000s**
  - upload_max_filesize / post_max_size: **64M**
  - MySQL: mysqli + PDO_MySQL with mysqlnd
  - Modern extensions present: GD (with webp+avif), sodium, argon2, intl, curl, openssl, zip, soap, ldap, imap

---

## Open items / TODO

- [ ] **Verify auto-renewal is ON for alfredoalea.com** (expires 2026-07-10) — if it lapses, the lab subdomain dies with it
- [ ] **Activate SSL for lab.alfredoalea.com** — check whether IONOS offers wildcard `*.alfredoalea.com` (preferred, covers future subdomains in 1 slot) or only per-host
- [ ] **Activate SSL for alfredoalea.com and alfredoaleasculpture.com** — both currently flagged "insecure" by browsers
- [ ] **Review PHP Extended Support add-on** — cancel if no site needs legacy PHP
- [ ] **Investigate `/app829410247` folder** — no domain points here, may be orphaned and reclaimable
- [ ] **Investigate Site Scan warnings** ("Vulnerable websites" flagged on hosting overview)
- [ ] **Set up SSH access** — enable in SFTP & SSH panel, set password for user `u45747260`, test from PowerShell. Needed for any non-trivial deploy workflow (Composer, git pulls).
- [ ] **Enable per-app error logging** — drop a `.user.ini` in each app folder with `log_errors=1` and `error_log=./error.log` (production defaults have log_errors=Off, so silent failures are the default — bad)
- [ ] **Decide what to actually build in /lab** — currently in exploration mode

---

## Conventions for this project

- One subdomain = one app = one webspace directory. Don't mix.
- Local working copy lives at `c:\Users\alfre\Desktop\_desktop\_projects\_php_mysql_app`. Always edit locally, upload to server. Never edit live files directly via the web file manager (no version control, easy to lose work).
- Until SSH is set up, deployment = Webspace Explorer upload. After SSH is set up, deployment = `rsync` or `git pull`.
- Add an entry to this changelog for any structural change (new subdomain, new folder, SSL activation, account-level setting change).
