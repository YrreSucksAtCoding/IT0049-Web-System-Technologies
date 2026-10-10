-- =========================================================
-- Complete Point-of-Sale System — database export
-- IT0049 Web System Technologies — Midterm Project
--
-- Four tables. products, customers and users hold the records
-- the system manages; sales is the transaction table that ties
-- them together.
--
-- Two notes on the schema as given in the handout:
--
--  1. InnoDB is required, because sales carries foreign keys.
--     MyISAM silently ignores them.
--
--  2. Each of the three record tables gains an `is_archived`
--     flag. A product or staff member referenced by a sale
--     CANNOT be removed without breaking the foreign key, and
--     deleting it would also destroy the history of what was
--     sold. "Delete" therefore archives the row: it disappears
--     from the management lists but the sale that points at it
--     stays intact. Relationships are unchanged.
--
-- Staff logins:  admin01 / admin123
--                cashier01 / cash123
--                manager01 / mgr123
-- Only the bcrypt hashes are stored. The plain passwords appear
-- here so the project can be marked.
-- =========================================================

CREATE DATABASE IF NOT EXISTS pos_midterm
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE pos_midterm;

-- Drop children before parents.
DROP TABLE IF EXISTS sales;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS users;

-- ------------------------------
-- products
-- ------------------------------
CREATE TABLE products (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(100)   NOT NULL,
    price          DECIMAL(10,2)  NOT NULL,
    stock_quantity INT            NOT NULL DEFAULT 0,
    image          VARCHAR(255),
    is_archived    TINYINT(1)     NOT NULL DEFAULT 0,
    created_at     DATETIME       NOT NULL
) ENGINE=InnoDB;

-- image is NULL here, so every row starts on the placeholder.
-- Upload a picture through the product form to fill it in.
INSERT INTO products (name, price, stock_quantity, image, is_archived, created_at) VALUES
('Ballpen (black, box of 12)',  85.00,  40, NULL, 0, '2026-01-10 09:00:00'),
('Notebook A5, 80 leaves',      45.50,  25, NULL, 0, '2026-01-10 09:05:00'),
('Sticky Notes 3x3',            32.75,  60, NULL, 0, '2026-01-12 10:20:00'),
('Stapler, heavy duty',        249.00,  12, NULL, 0, '2026-01-15 11:40:00'),
('Bond Paper A4, ream',        265.00,   8, NULL, 0, '2026-01-18 08:30:00'),
('USB Flash Drive 32GB',       420.00,  15, NULL, 0, '2026-02-02 14:10:00'),
('Correction Tape',             38.00,  50, NULL, 0, '2026-02-05 16:25:00'),
('Scissors, 8 inch',           135.00,   4, NULL, 0, '2026-02-11 10:00:00'),
('Typewriter Ribbon',           95.00,   0, NULL, 1, '2026-01-05 08:00:00');

-- ------------------------------
-- customers
-- ------------------------------
CREATE TABLE customers (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    full_name   VARCHAR(100) NOT NULL,
    email       VARCHAR(100) NOT NULL,
    phone       VARCHAR(20),
    is_archived TINYINT(1)   NOT NULL DEFAULT 0,
    created_at  DATETIME     NOT NULL
) ENGINE=InnoDB;

INSERT INTO customers (full_name, email, phone, is_archived, created_at) VALUES
('Ana Reyes',       'ana.reyes@email.com',    '0917-111-2233', 0, '2026-01-15 09:12:00'),
('Mark Villanueva', 'mark.v@email.com',       '0918-222-3344', 0, '2026-02-03 14:30:00'),
('Jenny Cruz',      'jenny.cruz@email.com',   '0919-333-4455', 0, '2026-02-20 11:05:00'),
('Paolo Santos',    'paolo.santos@email.com', '0920-444-5566', 0, '2026-03-11 16:47:00'),
('Liza Domingo',    'liza.domingo@email.com', '0921-555-6677', 0, '2026-03-28 10:20:00');

-- ------------------------------
-- users (staff)
-- ------------------------------
CREATE TABLE users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    full_name   VARCHAR(100) NOT NULL,
    password    VARCHAR(255) NOT NULL,
    avatar      VARCHAR(255),
    is_archived TINYINT(1)   NOT NULL DEFAULT 0,
    created_at  DATETIME     NOT NULL
) ENGINE=InnoDB;

-- password holds a bcrypt hash from password_hash($plain, PASSWORD_DEFAULT).
INSERT INTO users (username, full_name, password, avatar, is_archived, created_at) VALUES
('admin01',   'Yrre Suguitan', '$2y$12$Odx5p/6o/pyPaneQ5c9SM.VecHqhoYPMLrlMlisPBYkCR0wjNGVNC', NULL, 0, '2026-01-05 08:00:00'),
('cashier01', 'Bea Lopez',     '$2y$12$4SYXS81La8oroWo2KgcRye5kiQJ0xfC6EvFOiRDMo0lyeOUkMD.LK', NULL, 0, '2026-01-05 08:15:00'),
('manager01', 'Carlo Mendoza', '$2y$12$DLCHYC958iPORzEgPzGuZ.dt93qA5KWjxl/TuHsZPI.J3QZIkI5Oi', NULL, 0, '2026-02-10 07:55:00');

-- ------------------------------
-- sales  (the transaction table)
-- ------------------------------
CREATE TABLE sales (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    product_id   INT           NOT NULL,
    customer_id  INT,                      -- nullable: walk-in sales have no customer
    sold_by      INT           NOT NULL,
    quantity     INT           NOT NULL,
    total_price  DECIMAL(10,2) NOT NULL,
    created_at   DATETIME      NOT NULL,
    FOREIGN KEY (product_id)  REFERENCES products(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (sold_by)     REFERENCES users(id)
) ENGINE=InnoDB;

-- total_price is stored rather than recalculated on display, because the
-- price of a product can change later and an old receipt must not change
-- with it. Row 3 has a NULL customer_id — a walk-in sale.
INSERT INTO sales (product_id, customer_id, sold_by, quantity, total_price, created_at) VALUES
(1, 1,    1, 2,  170.00, '2026-03-02 09:40:00'),
(2, 1,    2, 3,  136.50, '2026-03-02 09:41:00'),
(6, NULL, 2, 1,  420.00, '2026-03-05 15:12:00'),
(4, 3,    1, 1,  249.00, '2026-03-09 11:05:00'),
(5, 2,    3, 2,  530.00, '2026-03-14 13:27:00'),
(3, 5,    2, 4,  131.00, '2026-03-21 10:02:00');
