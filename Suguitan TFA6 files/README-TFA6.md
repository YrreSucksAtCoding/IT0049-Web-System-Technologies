# TFA6 — From Arrays to Eloquent

**Yrre Suguitan** · IT0049 — Web System Technologies

TFA5's four pages, with the static PHP arrays replaced by a real MySQL
database: migrations build the tables, a seeder fills them, and Eloquent
models fetch the records.

| Page | URL | Where the data comes from |
|---|---|---|
| Home | `/` | — |
| About | `/about` | — |
| Customer Accounts | `/customers` | `Customer::orderBy('full_name')->get()` |
| User Accounts | `/users` | `User::orderBy('username')->get()` |

---

## What changed from TFA5

| | TFA5 | TFA6 |
|---|---|---|
| Data source | static array in the controller | `customers` / `users` tables |
| Controller | built the array by hand | asks an Eloquent model |
| Schema | none | 2 migration files |
| Sample data | typed into the controller | 2 seeder classes |
| View syntax | `$customer['full_name']` | `$customer->full_name` |
| Routes | unchanged | unchanged |
| Layout | unchanged | unchanged |

The views are the point: the `@foreach`, the table markup and the counter
are identical. Only the way one field is read changed, because a record is
now an object instead of an array.

---

## Setup

### 1. Create the Laravel project

```bash
cd "/Applications/MAMP/htdocs/IT0049 Web System Technologies"
composer create-project laravel/laravel "Suguitan TFA6"
```

### 2. Delete Laravel's own users migration

**Do this before anything else or `migrate` will fail.** Laravel ships with
a migration that creates its own `users` table, which collides with mine:

```bash
cd "Suguitan TFA6"
rm database/migrations/0001_01_01_000000_create_users_table.php
```

### 3. Copy my files in

```bash
cd "/Applications/MAMP/htdocs/IT0049 Web System Technologies"
cp -R "Suguitan TFA6 files/app" \
      "Suguitan TFA6 files/database" \
      "Suguitan TFA6 files/routes" \
      "Suguitan TFA6 files/resources" \
      "Suguitan TFA6/"
```

This overwrites Laravel's default `app/Models/User.php`, which is intended —
mine uses `username` and `full_name` instead of `name` and `email`.

### 4. Create the database

Start MAMP, open **phpMyAdmin**, create a database named:

```
tfa6_pos
```

Leave it empty — the migrations build the tables.

### 5. Point Laravel at it

Open `.env` in the project root and replace the database block with the one
in `env-settings-for-MAMP.txt`. The short version:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=tfa6_pos
DB_USERNAME=root
DB_PASSWORD=root

SESSION_DRIVER=file
```

`127.0.0.1`, not `localhost` — with `localhost` PHP ignores the port and
looks for a unix socket, which is the exact error I hit back in TFA2.

### 6. Build the tables and fill them

```bash
cd "Suguitan TFA6"
php artisan migrate --seed
```

Expected output:

```
INFO  Running migrations.
  2026_10_31_000001_create_customers_table ......... DONE
  2026_10_31_000002_create_users_table ............. DONE
INFO  Seeding database.
  Database\Seeders\CustomerSeeder .................. RUNNING
  Database\Seeders\UserSeeder ...................... RUNNING
```

### 7. Run it

```bash
php artisan serve
```

Open **http://127.0.0.1:8000** — port 8000, not 8080.

---

## Testing it

1. `/customers` shows 5 rows and "Total customers: 5"
2. `/users` shows 5 rows and "Total users: 5" — and **no password column**
3. Open `tfa6_pos` in phpMyAdmin: both tables exist, with `created_at` and
   `updated_at` filled in, and the `password` column holding `$2y$12$...`
   hashes rather than readable text
4. Add a row in phpMyAdmin and refresh `/customers` — it appears. That is the
   proof the page is reading the database and not an array
5. `php artisan migrate:rollback` drops both tables; `php artisan migrate --seed`
   rebuilds them. Nothing is lost that is not in a file

### Useful commands

```bash
php artisan migrate:fresh --seed   # drop everything and rebuild
php artisan db:seed                # re-run seeders only
php artisan migrate:status         # which migrations have run
php artisan tinker                 # then: Customer::count()
```

`migrate:fresh` drops **every** table. Fine here, never on anything real.

---

## Files

```
database/migrations/
  2026_10_31_000001_create_customers_table.php   full_name, email (unique), phone
  2026_10_31_000002_create_users_table.php       username (unique), password, full_name, role

database/seeders/
  CustomerSeeder.php     5 customers, seeded through the model
  UserSeeder.php         5 staff, passwords through Hash::make()
  DatabaseSeeder.php     calls both in order

database/tfa6_pos.sql    plain SQL export of the same thing, for hosting

app/Models/
  Customer.php           $table, $fillable
  User.php               plus $hidden so the hash never reaches a view

app/Http/Controllers/
  CustomerController.php  Customer::orderBy('full_name')->get()
  UserController.php      User::orderBy('username')->get()
  PageController.php      unchanged from TFA5

resources/views/          only the two list views changed, [] -> ->
routes/web.php            unchanged from TFA5
```

### Why the users table has more than the handout asks for

The activity names `username` (unique) and `full_name`. I kept `role`
because my TFA5 view has a Role column and I did not want to remove a
column from a working page, and `password` because a staff table without
one is not a POS staff table — and it sets up the login flow directly.

---

## Test accounts

| Username | Password | Role |
|---|---|---|
| admin01 | admin123 | Administrator |
| cashier01 | cash123 | Cashier |
| cashier02 | cash456 | Cashier |
| stock01 | stock123 | Stock Clerk |
| manager01 | mgr123 | Manager |

Nothing logs in yet — these are what the seeder writes, hashed.

---

## Pushing to GitHub

```bash
cd "Suguitan TFA6"
git init
git add .
git commit -m "TFA6 - From Arrays to Eloquent"
git remote add origin https://github.com/YrreSucksAtCoding/IT0049-Web-System-Technologies.git
```

Laravel's `.gitignore` already excludes `/vendor` and `.env`, which is
correct — `.env` has the database password in it. The migrations, seeders
and `database/tfa6_pos.sql` all get committed, so the repository has
everything the submission asks for.

If you are committing into the existing course repo as a subfolder, don't
run `git init` inside it — copy the project folder into your clone and
commit from the repo root instead, the same way as TSA1.

---

## Hosting

Laravel on InfinityFree takes more setup than CodeIgniter did, because the
web root has to be `public/` and there is no command line to run `migrate`.
The outline:

1. `composer install --no-dev --optimize-autoloader` locally, so `vendor/`
   exists before uploading
2. Upload the project so everything except `public/` sits in a folder inside
   `htdocs` (for example `htdocs/laravel_app/`), with a
   `Require all denied` `.htaccess` inside it
3. Put the contents of `public/` directly in `htdocs`
4. Edit `htdocs/index.php` so its two `require` paths point at
   `laravel_app/` instead of `../`
5. Create the MySQL database in vPanel and **import `database/tfa6_pos.sql`**
   through phpMyAdmin — this replaces `php artisan migrate --seed`
6. Set `.env` on the server: the vPanel database credentials, plus
   `APP_ENV=production`, `APP_DEBUG=false` and a real `APP_KEY`
   (`php artisan key:generate --show` locally, then paste it)

Say the word and I'll write this out step by step the way we did for the
Midterm.
