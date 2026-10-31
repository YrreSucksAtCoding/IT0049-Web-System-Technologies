-- ---------------------------------------------------------------------
-- tfa6_pos.sql
--
-- A plain SQL export of exactly what `php artisan migrate --seed` builds.
-- Included for two reasons:
--   1. the activity asks for a database export in the repository, and
--   2. shared hosting has no command line, so the live site is set up by
--      importing this file in phpMyAdmin instead of running artisan.
--
-- Import into an EMPTY database.
-- ---------------------------------------------------------------------

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------ customers

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name`  VARCHAR(100) NOT NULL,
  `email`      VARCHAR(100) NOT NULL,
  `phone`      VARCHAR(20)  NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `customers` (`full_name`, `email`, `phone`, `created_at`, `updated_at`) VALUES
('Ana Reyes',       'ana.reyes@email.com',    '0917-111-2233', NOW(), NOW()),
('Mark Villanueva', 'mark.v@email.com',       '0918-222-3344', NOW(), NOW()),
('Jenny Cruz',      'jenny.cruz@email.com',   '0919-333-4455', NOW(), NOW()),
('Paolo Santos',    'paolo.santos@email.com', '0920-444-5566', NOW(), NOW()),
('Liza Domingo',    'liza.domingo@email.com', '0921-555-6677', NOW(), NOW());

-- ---------------------------------------------------------------- users

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(50)  NOT NULL,
  `password`   VARCHAR(255) NOT NULL,
  `full_name`  VARCHAR(100) NOT NULL,
  `role`       VARCHAR(30)  NOT NULL DEFAULT 'Cashier',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Passwords are bcrypt hashes, the same thing Hash::make() produces.
-- Plain-text equivalents, for testing only:
--   admin01 / admin123      cashier01 / cash123     cashier02 / cash456
--   stock01 / stock123      manager01 / mgr123
INSERT INTO `users` (`username`, `password`, `full_name`, `role`, `created_at`, `updated_at`) VALUES
('admin01',   '$2y$12$bzZ0DaEnnv0JuLaNcBdOleRZCx7DpyIqIxKzdE3m9QfWSp2qL/DCS', 'Yrre Suguitan', 'Administrator', NOW(), NOW()),
('cashier01', '$2y$12$qPaAEjNjySAYOQgc0QBFjOdjfcJFQhyaKtwlHAauQi5AFvDwS4TDK', 'Bea Lopez',     'Cashier',       NOW(), NOW()),
('cashier02', '$2y$12$VJzDt/wQwqhtJMKbUU5MdunhdQj3LkHBv/fIEHQ9xOYQtP3tpmT2S', 'Ken Tolentino', 'Cashier',       NOW(), NOW()),
('stock01',   '$2y$12$XLxX4iw5V/TGWAYO.kqA8.x2J4YbNW1a.uLVV4tG2RTMOBbx01B3y', 'Rina Bautista', 'Stock Clerk',   NOW(), NOW()),
('manager01', '$2y$12$5DDNDYJEIvDq6VYMxNsyw.IhAR./Qz6fHiz4k/ERyves49sRZ9M2i', 'Carlo Mendoza', 'Manager',       NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
