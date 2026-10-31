-- ---------------------------------------------------------------------
-- tsa3_tasks.sql
--
-- A plain SQL export of what `php artisan migrate --seed` builds.
-- Included because the activity asks for a database export, and because
-- shared hosting has no command line to run artisan on.
--
-- The task dates are written relative to CURDATE() rather than typed in,
-- so "today's tasks" is still correct whenever this file is imported.
--
-- Import into an EMPTY database.
-- ---------------------------------------------------------------------

SET NAMES utf8mb4;

-- ---------------------------------------------------------------- users

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(50)  NOT NULL,
  `full_name`  VARCHAR(100) NOT NULL,
  `email`      VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`username`, `full_name`, `email`, `created_at`, `updated_at`) VALUES
('yrre', 'Yrre Suguitan', 'yrresuguitan00@gmail.com', NOW(), NOW());

-- ---------------------------------------------------------------- tasks

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`      VARCHAR(150) NOT NULL,
  `status`     VARCHAR(20)  NOT NULL DEFAULT 'pending',
  `task_date`  DATE NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_task_date_index` (`task_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10 tasks across 4 dates. The four CURDATE() rows are what the
-- Welcome page shows.
INSERT INTO `tasks` (`title`, `status`, `task_date`, `created_at`, `updated_at`) VALUES
('Finish the TSA3 Laravel rebuild',       'in progress', CURDATE(),                            NOW(), NOW()),
('Read the Laravel docs on query scopes', 'completed',   CURDATE(),                            NOW(), NOW()),
('Submit the IT0049 activity online',     'pending',     CURDATE(),                            NOW(), NOW()),
('Push the project to GitHub',            'pending',     CURDATE(),                            NOW(), NOW()),
('Create the tsa3_tasks database',        'completed',   CURDATE() - INTERVAL 1 DAY,           NOW(), NOW()),
('Plan the tasks table schema',           'completed',   CURDATE() - INTERVAL 1 DAY,           NOW(), NOW()),
('Set up hosting for the project',        'pending',     CURDATE() + INTERVAL 1 DAY,           NOW(), NOW()),
('Review for the IT0049 quiz',            'pending',     CURDATE() + INTERVAL 1 DAY,           NOW(), NOW()),
('Check the group capstone timeline',     'pending',     CURDATE() + INTERVAL 1 DAY,           NOW(), NOW()),
('Write the README for the repository',   'pending',     CURDATE() + INTERVAL 2 DAY,           NOW(), NOW());
