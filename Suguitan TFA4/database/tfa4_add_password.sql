-- ---------------------------------------------------------
-- TFA4 — add the password column and set a hashed password
-- for every existing user.
--
-- Run this if you already have the TFA3 database. If you are
-- starting fresh, import simple_pos.sql instead — it already
-- includes the column and the hashes.
--
-- The values below are bcrypt hashes produced by PHP's
-- password_hash($plain, PASSWORD_DEFAULT). The plain passwords
-- are listed in the comments so you can log in and test; in a
-- real system they would never be written down anywhere.
-- ---------------------------------------------------------

USE simple_pos;

ALTER TABLE users
    ADD COLUMN password VARCHAR(255) NOT NULL DEFAULT '' AFTER username;

-- admin01 / admin123
UPDATE users SET password = '$2y$12$pbhyIMRdjn9qKucm9hYUS.zQ0hGViSpMsKTAb8mChT9xE6uDBi7E.'
WHERE username = 'admin01';

-- cashier01 / cash123
UPDATE users SET password = '$2y$12$uGMzF.4grTeH93HXWGIPUOev4pvpw1.k5jJQ4MhqGgZuNLnnXeDHC'
WHERE username = 'cashier01';

-- cashier02 / cash456
UPDATE users SET password = '$2y$12$RQtJVKJcAlPaRa1/5vAE2ezabkm.XN.b48gM2rszJPkOwCCmeDBNC'
WHERE username = 'cashier02';

-- stock01 / stock123
UPDATE users SET password = '$2y$12$q.LFLJXEvNNPp23pbR5C5ORHeZlAiMu.0b8eEc8eAVJVSliXG.FkC'
WHERE username = 'stock01';

-- manager01 / mgr123
UPDATE users SET password = '$2y$12$t05Fbq8NQ8dzvaZtyPVx3eWtG2ECtIaHyBc9NAWmkcVBbxEwg8sIW'
WHERE username = 'manager01';

-- Any row still holding an empty password cannot log in, because
-- password_verify() never matches an empty hash.
SELECT id, username, full_name,
       CASE WHEN password = '' THEN 'NO PASSWORD SET' ELSE 'hashed' END AS password_status
FROM users;
