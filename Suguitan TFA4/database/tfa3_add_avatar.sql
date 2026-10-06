-- ---------------------------------------------------------
-- TFA3 — add the avatar column to the existing users table.
--
-- Run this if you already have the TFA2 database and do not
-- want to wipe it. If you are starting fresh, import
-- simple_pos.sql instead — it already includes the column.
-- ---------------------------------------------------------

USE simple_pos;

ALTER TABLE users
    ADD COLUMN avatar VARCHAR(255) NULL AFTER full_name;
