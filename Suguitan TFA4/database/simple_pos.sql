-- ---------------------------------------------------------
-- Simple POS System — database export (TFA4)
-- IT0049 Web System Technologies
--
-- customers: unchanged since TFA2.
-- users:     now carries a `password` column holding a bcrypt HASH,
--            and the `avatar` column added in TFA3 holding a filename.
--
-- The plain passwords are in the comments only so the project can be
-- tested. A real system would never record them.
-- ---------------------------------------------------------

CREATE DATABASE IF NOT EXISTS simple_pos
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE simple_pos;

-- ------------------------------
-- Table: customers
-- ------------------------------
DROP TABLE IF EXISTS customers;

CREATE TABLE customers (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    full_name  VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    phone      VARCHAR(20),
    created_at DATETIME     NOT NULL
);

INSERT INTO customers (full_name, email, phone, created_at) VALUES
('Ana Reyes',       'ana.reyes@email.com',    '0917-111-2233', '2026-01-15 09:12:00'),
('Mark Villanueva', 'mark.v@email.com',       '0918-222-3344', '2026-02-03 14:30:00'),
('Jenny Cruz',      'jenny.cruz@email.com',   '0919-333-4455', '2026-02-20 11:05:00'),
('Paolo Santos',    'paolo.santos@email.com', '0920-444-5566', '2026-03-11 16:47:00'),
('Liza Domingo',    'liza.domingo@email.com', '0921-555-6677', '2026-03-28 10:20:00');

-- ------------------------------
-- Table: users
-- ------------------------------
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    full_name  VARCHAR(100) NOT NULL,
    avatar     VARCHAR(255) NULL,
    created_at DATETIME     NOT NULL
);

-- password column holds bcrypt hashes from password_hash().
-- Test logins:  admin01/admin123  cashier01/cash123  cashier02/cash456
--               stock01/stock123  manager01/mgr123
INSERT INTO users (username, password, full_name, avatar, created_at) VALUES
('admin01',   '$2y$12$pbhyIMRdjn9qKucm9hYUS.zQ0hGViSpMsKTAb8mChT9xE6uDBi7E.', 'Yrre Suguitan', NULL, '2026-01-05 08:00:00'),
('cashier01', '$2y$12$uGMzF.4grTeH93HXWGIPUOev4pvpw1.k5jJQ4MhqGgZuNLnnXeDHC', 'Bea Lopez',     NULL, '2026-01-05 08:15:00'),
('cashier02', '$2y$12$RQtJVKJcAlPaRa1/5vAE2ezabkm.XN.b48gM2rszJPkOwCCmeDBNC', 'Ken Tolentino', NULL, '2026-01-12 09:30:00'),
('stock01',   '$2y$12$q.LFLJXEvNNPp23pbR5C5ORHeZlAiMu.0b8eEc8eAVJVSliXG.FkC', 'Rina Bautista', NULL, '2026-02-01 13:45:00'),
('manager01', '$2y$12$t05Fbq8NQ8dzvaZtyPVx3eWtG2ECtIaHyBc9NAWmkcVBbxEwg8sIW', 'Carlo Mendoza', NULL, '2026-02-10 07:55:00');
