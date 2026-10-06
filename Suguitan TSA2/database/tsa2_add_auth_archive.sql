-- ---------------------------------------------------------
-- TSA2 — upgrade an existing TSA1 database.
--
-- Adds the soft-delete flag to tasks and a hashed password to the
-- demo user. Run this only if you already have the TSA1 database.
-- Starting fresh? Import tasks_today.sql instead — it has everything.
-- ---------------------------------------------------------

USE tasks_today;

-- 1. The soft-delete flag. Existing rows default to 0, meaning visible.
ALTER TABLE tasks
    ADD COLUMN is_archived TINYINT(1) NOT NULL DEFAULT 0 AFTER task_date;

-- 2. The password column.
ALTER TABLE users
    ADD COLUMN password VARCHAR(255) NOT NULL DEFAULT '' AFTER username;

-- 3. Set the demo user's password. This is the bcrypt hash of 'demo1234',
--    produced by PHP's password_hash($plain, PASSWORD_DEFAULT).
UPDATE users
SET password = '$2y$12$VPAvzi3S1xEr/1Bfgme9iubiAABkM1/KbZvrJh0AgdGduH4sGdOMy'
WHERE username = 'demo_user';

-- Check: any row still holding an empty password cannot log in, because
-- password_verify() never matches an empty hash.
SELECT id, username, full_name,
       CASE WHEN password = '' THEN 'NO PASSWORD SET' ELSE 'hashed' END AS password_status
FROM users;
