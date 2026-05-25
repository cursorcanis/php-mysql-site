# Project Changelog

Running log of all infrastructure, configuration, and code changes for this IONOS PHP/MySQL playground.

Format: dated entries, newest at the top. Each entry: what changed, where, why.

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
