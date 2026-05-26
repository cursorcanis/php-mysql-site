-- Alea Lab Vault — Database Setup (v2 Mobile)
-- Run this in phpMyAdmin. Safe to run on a fresh database.

CREATE TABLE IF NOT EXISTS `images` (
  `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `filename`      VARCHAR(255)    NOT NULL,
  `original_name` VARCHAR(255)    NOT NULL,
  `mime_type`     VARCHAR(100)    NOT NULL,
  `file_size`     INT UNSIGNED    NOT NULL,
  `uploaded_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title`         VARCHAR(255)    DEFAULT NULL,
  `notes`         TEXT            DEFAULT NULL,
  `share_token`   VARCHAR(64)     DEFAULT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tags` (
  `id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`  VARCHAR(100) NOT NULL,
  `color` VARCHAR(7)   NOT NULL DEFAULT '#00e5ff',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `image_tags` (
  `image_id` INT UNSIGNED NOT NULL,
  `tag_id`   INT UNSIGNED NOT NULL,
  PRIMARY KEY (`image_id`, `tag_id`),
  FOREIGN KEY (`image_id`) REFERENCES `images`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`tag_id`)   REFERENCES `tags`(`id`)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed starter tags
INSERT IGNORE INTO `tags` (`name`, `color`) VALUES
  ('comfyui',    '#00e5ff'),
  ('render',     '#ffe066'),
  ('reference',  '#a78bfa'),
  ('screenshot', '#34d399'),
  ('personal',   '#ff3d71');
