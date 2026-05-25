# php-mysql-site

Playground PHP/MySQL app hosted on IONOS at **https://lab.alfredoalea.com/**.

## Stack
- PHP 8.4 (IONOS Expert shared hosting, CGI/FastCGI)
- MySQL (IONOS Standard, unlimited DBs)
- No framework yet — plain PHP
- No Node.js available server-side; any frontend build runs locally

## Layout
```
.
├── index.php       # entry point — deployed to /lab/index.php on IONOS
├── CHANGELOG.md    # dated record of infra + code changes
├── README.md       # this file
└── .gitignore
```

## Workflow
1. Edit locally in VSCode
2. `git add` → `git commit` → `git push`
3. Deploy: SSH into IONOS, `cd /lab`, `git pull`

See [CHANGELOG.md](CHANGELOG.md) for full setup history and open items.
