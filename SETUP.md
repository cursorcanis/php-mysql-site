# Alea Lab Vault — Setup Instructions

## Files to upload to /lab on IONOS

```
index.php       → Login page
dashboard.php   → Main gallery + upload interface
auth.php        → Session & DB helpers
config.php      → YOUR SETTINGS (edit before uploading)
logout.php      → Logout handler
setup.sql       → Run once in phpMyAdmin
uploads/        → Upload this folder (images stored here)
uploads/.htaccess → Security rules for uploads dir
```

---

## Step 1 — Create MySQL database on IONOS

1. Go to my.ionos.com → Hosting → Databases → Create Database
2. Note down: host, database name, username, password

---

## Step 2 — Run setup.sql

1. Open phpMyAdmin (from IONOS panel)
2. Select your database
3. Click "SQL" tab
4. Paste contents of setup.sql and run it

---

## Step 3 — Edit config.php

Fill in:
- DB_HOST, DB_NAME, DB_USER, DB_PASS  (from Step 1)
- VAULT_USER  — your login username
- VAULT_PASS_HASH — generate with:
    php -r "echo password_hash('yourpassword', PASSWORD_BCRYPT);"
  Or use an online bcrypt generator.

---

## Step 4 — Upload via SFTP

Use your IONOS FTP account (u45747260-a9836711) to upload
all files to: /kunden/homepages/5/d210362041/htdocs/lab/

Make sure the uploads/ folder is writable (chmod 755).

---

## Step 5 — Visit http://lab.alfredoalea.com

Login with your credentials from config.php.

---

## Security notes

- HTTPS: Enable free SSL on IONOS for lab.alfredoalea.com (Control Panel → SSL)
- The uploads/.htaccess prevents PHP execution in the upload folder
- Passwords are bcrypt-hashed, never stored in plain text
- CSRF tokens protect all forms
- Sessions auto-expire after 1 hour of inactivity
