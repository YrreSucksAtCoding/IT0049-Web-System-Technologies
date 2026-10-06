-- ---------------------------------------------------------
-- Tasks for Today Management System — database export (TSA2)
-- IT0049 Web System Technologies
--
-- Adds to the TSA1 schema:
--   tasks.is_archived  the soft-delete flag (0 = visible, 1 = archived)
--   users.password     a bcrypt HASH from password_hash()
--
-- task_date values are written relative to CURDATE(), so the "today"
-- tasks are always today's date no matter when this file is imported.
--
-- Demo login:  demo_user / demo1234
-- The plain password appears here only so the project can be tested.
-- ---------------------------------------------------------

CREATE DATABASE IF NOT EXISTS tasks_today
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE tasks_today;

-- ------------------------------
-- Table: tasks
-- ------------------------------
DROP TABLE IF EXISTS tasks;

CREATE TABLE tasks (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(150) NOT NULL,
    status      VARCHAR(20)  NOT NULL DEFAULT 'pending',
    task_date   DATE         NOT NULL,
    is_archived TINYINT(1)   NOT NULL DEFAULT 0,
    created_at  DATETIME     NOT NULL
);

-- 11 tasks across 4 dates. One starts archived so the Archived page has
-- something in it straight away and the soft delete is visible on import.
INSERT INTO tasks (title, status, task_date, is_archived, created_at) VALUES
-- two days ago
('Draft the weekly inventory report',       'done',        DATE_SUB(CURDATE(), INTERVAL 2 DAY), 0, NOW()),
('Back up the sales database',              'done',        DATE_SUB(CURDATE(), INTERVAL 2 DAY), 0, NOW()),
('Cancelled: reorder the old price tags',   'pending',     DATE_SUB(CURDATE(), INTERVAL 2 DAY), 1, NOW()),
-- yesterday
('Restock the counter display',             'done',        DATE_SUB(CURDATE(), INTERVAL 1 DAY), 0, NOW()),
('Follow up on supplier invoice #2291',     'pending',     DATE_SUB(CURDATE(), INTERVAL 1 DAY), 0, NOW()),
-- today
('Open the store and count the cash float', 'done',        CURDATE(), 0, NOW()),
('Update product prices for the promo',     'in_progress', CURDATE(), 0, NOW()),
('Call the courier about the late parcel',  'pending',     CURDATE(), 0, NOW()),
('Prepare the end-of-day sales summary',    'pending',     CURDATE(), 0, NOW()),
-- tomorrow
('Schedule the staff meeting',              'pending',     DATE_ADD(CURDATE(), INTERVAL 1 DAY), 0, NOW()),
('Clean up the stockroom shelves',          'pending',     DATE_ADD(CURDATE(), INTERVAL 1 DAY), 0, NOW());

-- ------------------------------
-- Table: users
-- ------------------------------
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    full_name  VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    created_at DATETIME     NOT NULL
);

-- password holds a bcrypt hash. Login with: demo_user / demo1234
INSERT INTO users (username, password, full_name, email, created_at) VALUES
('demo_user',
 '$2y$12$VPAvzi3S1xEr/1Bfgme9iubiAABkM1/KbZvrJh0AgdGduH4sGdOMy',
 'Yrre Suguitan',
 'yrresuguitan00@gmail.com',
 NOW());
