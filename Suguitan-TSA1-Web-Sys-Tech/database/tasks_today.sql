-- ---------------------------------------------------------
-- Tasks for Today Management System — database export
-- IT0049 Web System Technologies, TSA1
--
-- Import this file to create the database, both tables,
-- and the sample records.
--
-- Note on dates: the task_date values are written relative to
-- CURDATE(), so the "today" tasks are always today's date no
-- matter when this file is imported. That keeps the Welcome
-- page working for whoever runs the project.
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
    id         INT AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(150) NOT NULL,
    status     VARCHAR(20)  NOT NULL DEFAULT 'pending',
    task_date  DATE         NOT NULL,
    created_at DATETIME     NOT NULL
);

-- 10 tasks across 4 different dates: 2 days ago, yesterday, today, tomorrow.
INSERT INTO tasks (title, status, task_date, created_at) VALUES
-- two days ago
('Draft the weekly inventory report',      'done',        DATE_SUB(CURDATE(), INTERVAL 2 DAY), NOW()),
('Back up the sales database',             'done',        DATE_SUB(CURDATE(), INTERVAL 2 DAY), NOW()),
-- yesterday
('Restock the counter display',            'done',        DATE_SUB(CURDATE(), INTERVAL 1 DAY), NOW()),
('Follow up on supplier invoice #2291',    'pending',     DATE_SUB(CURDATE(), INTERVAL 1 DAY), NOW()),
-- today
('Open the store and count the cash float','done',        CURDATE(), NOW()),
('Update product prices for the promo',    'in_progress', CURDATE(), NOW()),
('Call the courier about the late parcel', 'pending',     CURDATE(), NOW()),
('Prepare the end-of-day sales summary',   'pending',     CURDATE(), NOW()),
-- tomorrow
('Schedule the staff meeting',             'pending',     DATE_ADD(CURDATE(), INTERVAL 1 DAY), NOW()),
('Clean up the stockroom shelves',         'pending',     DATE_ADD(CURDATE(), INTERVAL 1 DAY), NOW());

-- ------------------------------
-- Table: users
-- ------------------------------
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    full_name  VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    created_at DATETIME     NOT NULL
);

-- Exactly one demo user, as the activity requires.
INSERT INTO users (username, full_name, email, created_at) VALUES
('demo_user', 'Yrre Suguitan', 'yrresuguitan00@gmail.com', NOW());
