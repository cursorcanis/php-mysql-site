# Phase 1 Deployment Guide: File Type Support

## What's Changed

### 1. config.php
- Increased MAX_FILE_SIZE from 10 MB to 100 MB
- Added MIME types: `text/plain`, `application/vnd.openxmlformats-officedocument.wordprocessingml.document`, `application/msword`
- Added extensions: `txt`, `docx`, `doc`
- Added `get_file_type()` helper function

### 2. dashboard.php
- Updated upload form to accept all file types (text, documents, images)
- Changed file input from `name="image"` to `name="file"`
- Modified upload handler to store `file_type` in database
- Added file preview system:
  - Images: displayed as `<img>`
  - Text (.txt): displayed in `<pre>` tag with syntax preservation
  - Documents (.docx): displayed via Google Docs Viewer iframe
- Updated detail modal to show appropriate preview based on file type

### 3. setup-phase1.sql
- Adds `file_type` ENUM column to `images` table
- Sets default value to 'image' for backward compatibility

## Deployment Steps

### Step 1: Run Database Migration
1. Go to IONOS portal → phpMyAdmin
2. Select database `your_db_name`
3. Copy and paste contents of `setup-phase1.sql` into SQL editor
4. Click Execute

**SQL to run:**
```sql
ALTER TABLE `images` ADD COLUMN `file_type` ENUM('image', 'document', 'file') DEFAULT 'image' AFTER `mime_type`;
```

### Step 2: Upload Updated Files
1. Upload via Filezilla to `/lab` folder:
   - `config.php` (updated)
   - `dashboard.php` (updated)

### Step 3: Delete debug.php
Delete or move `debug.php` from the server for security.

### Step 4: Test
1. Visit `http://lab.alfredoalea.com/`
2. Login with your credentials
3. Try uploading:
   - An image (should show as before)
   - A .txt file (should show plaintext preview)
   - A .docx file (should show document viewer)
4. Verify previews work correctly
5. Try tags, sharing, and downloads

## Notes
- Text file previews are limited to first 10,000 characters to avoid massive previews
- Document viewer uses Google Docs Viewer (requires public internet access)
- File type detection is automatic based on MIME type
- All existing data continues to work (file_type defaults to 'image')

## Next: Phase 2 & 3
Ready to implement Google Drive and GitHub integrations once Phase 1 is tested and confirmed working.
