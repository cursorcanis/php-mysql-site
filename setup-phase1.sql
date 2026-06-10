-- Alea Lab Vault — Phase 1: File Type Support
-- Run this in phpMyAdmin or via SSH to extend the vault for document/text support

ALTER TABLE `images` ADD COLUMN `file_type` ENUM('image', 'document', 'file') NOT NULL DEFAULT 'image' AFTER `mime_type`;

-- Optional: Update existing records (images are already image type, so no need unless re-running)
-- UPDATE `images` SET `file_type` = 'image' WHERE `mime_type` LIKE 'image/%';
