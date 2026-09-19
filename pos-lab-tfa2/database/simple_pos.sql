-- ---------------------------------------------------------
-- Simple POS System — database export
-- IT0049 Web System Technologies, TFA2
--
-- Import this file to create the database, the two tables,
-- and the sample records.
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
    full_name  VARCHAR(100) NOT NULL,
    created_at DATETIME     NOT NULL
);

INSERT INTO users (username, full_name, created_at) VALUES
('admin01',   'Yrre Suguitan', '2026-01-05 08:00:00'),
('cashier01', 'Bea Lopez',     '2026-01-05 08:15:00'),
('cashier02', 'Ken Tolentino', '2026-01-12 09:30:00'),
('stock01',   'Rina Bautista', '2026-02-01 13:45:00'),
('manager01', 'Carlo Mendoza', '2026-02-10 07:55:00');
