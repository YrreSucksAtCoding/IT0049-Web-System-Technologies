# TSA3 — Tasks for Today Management System (Laravel)

**Yrre Suguitan** · IT0049 — Web System Technologies

TSA1 rebuilt in Laravel. Same four pages, same data, but the raw SQL and
Query Builder are replaced by migrations, a seeder and Eloquent models.

| Page | URL | What it shows | Query |
|---|---|---|---|
| Welcome | `/` | **only today's tasks** | `Task::today()->ordered()->get()` |
| Task List | `/tasks` | every task, by date | `Task::ordered()->get()` |
| Profile | `/profile` | the single user record | `User::first()` |
| About | `/about` | static — developer info | none |

---

## The part that matters: `Task::today()`

The Welcome page filters by date. That filter is a **query scope** on the
model, not a `where` clause written inside the controller:

```php
// app/Models/Task.php
public function scopeToday(Builder $query): Builder
{
    return $query->whereDate('task_date', today());
}
```

```php
// app/Http/Controllers/TaskController.php
$tasks = Task::today()->ordered()->get();
```

Two reasons it lives on the model:

1. **One definition of "today".** If the rule ever changes — say it should
   include overdue tasks — it changes in one file, not in every controller
   that needs it.
2. **The controller reads like the requirement.** `Task::today()` says what
   the page is; `->whereDate('task_date', today())` says how it's done.

`whereDate()` compares only the date part, so it keeps working if the
column is ever changed to a datetime.

---

## Setup

### 1. Create the Laravel project

```bash
cd "/Applications/MAMP/htdocs/IT0049 Web System Technologies"
composer create-project laravel/laravel "Suguitan TSA3"
```

### 2. Delete Laravel's own users migration

**Before anything else**, or step 6 fails with "table users already exists":

```bash
cd "Suguitan TSA3"
rm database/migrations/0001_01_01_000000_create_users_table.php
```

### 3. Copy my files in

```bash
cd "/Applications/MAMP/htdocs/IT0049 Web System Technologies"
cp -R "Suguitan TSA3 files/app" \
      "Suguitan TSA3 files/database" \
      "Suguitan TSA3 files/routes" \
      "Suguitan TSA3 files/resources" \
      "Suguitan TSA3/"
```

This overwrites Laravel's default `app/Models/User.php` and
`resources/views/welcome.blade.php` is left unused — `/` now goes to
`TaskController`.

### 4. Create the database

Start MAMP, open phpMyAdmin, create an empty database named:

```
tsa3_tasks
```

### 5. Edit `.env`

In the project root (hidden file — `open -e .env` from Terminal). Use the
block in `env-settings-for-MAMP.txt`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=tsa3_tasks
DB_USERNAME=root
DB_PASSWORD=root

SESSION_DRIVER=file
APP_TIMEZONE=Asia/Manila
```

`127.0.0.1`, not `localhost`. And `APP_TIMEZONE` matters more here than in
any previous activity — the whole Welcome page is a date comparison, and on
the default UTC the day rolls over 8 hours early.

### 6. Build and fill the tables

```bash
cd "Suguitan TSA3"
php artisan migrate --seed
```

### 7. Run it

```bash
php artisan serve
```

Open **http://127.0.0.1:8000** — port 8000.

---

## Testing it

| Check | Expected |
|---|---|
| `/` | exactly **4 tasks**, all dated today, with "1 of 4 done" |
| `/tasks` | **10 tasks** under 4 date headings, labelled Yesterday / Today / Tomorrow |
| `/profile` | one record — yrre / Yrre Suguitan / email / registered date |
| `/about` | static page naming you as developer |
| Nav | current page is highlighted on all four |

**The proof that the date filter works:** open `tasks` in phpMyAdmin, change
one of today's tasks to next week, refresh `/`. It disappears from the
Welcome page but is still on `/tasks`. Nothing else demonstrates the scope
as clearly — this is the screenshot worth taking.

Also worth trying: `php artisan tinker`, then

```php
Task::today()->count();     // 4
Task::count();              // 10
Task::today()->toSql();     // see the SQL the scope generates
```

---

## Files

```
database/migrations/
  ..._create_tasks_table.php    title, status default 'pending', task_date, timestamps
  ..._create_users_table.php    username unique, full_name, email

database/seeders/
  TaskSeeder.php                10 tasks across 4 dates, computed from today()
  UserSeeder.php                exactly one user
  DatabaseSeeder.php            calls both

database/tsa3_tasks.sql         SQL export, dates relative to CURDATE()

app/Models/
  Task.php                      scopeToday(), scopeOrdered(), statusClass()
  User.php                      plain Model — nothing logs in

app/Http/Controllers/
  TaskController.php            today() and index()
  ProfileController.php         index()
  PageController.php            about()

resources/views/
  layouts/app.blade.php         nav + styling, shared by all four pages
  tasks/today.blade.php         Welcome
  tasks/index.blade.php         Task List, grouped by date
  tasks/partials/row.blade.php  one task row, @included by both task pages
  profile/index.blade.php
  pages/about.blade.php

routes/web.php                  the four routes, each with a name
```

### Notes on a few decisions

- **`tasks/partials/row.blade.php`** exists so a task looks identical on both
  pages and only one file changes if that stops being true. The rubric asks
  for "multiple distinct Blade views from a shared data layer" — the shared
  partial is the same idea one level down.
- **`$byDate` is grouped in the controller, not in SQL.** The rows are
  already loaded, so grouping them in PHP costs nothing and avoids a second
  query.
- **`email` is unique** even though the activity only requires it on
  `username`. It costs nothing and two accounts sharing an email is never
  wanted.
- **No password column.** Nothing authenticates here, so storing a hash that
  nothing checks would be clutter. Adding auth later means a new migration
  and changing `User` to extend `Authenticatable`.

---

## Pushing to GitHub

```bash
cd "Suguitan TSA3"
git init
git add .
git commit -m "TSA3 - Tasks for Today Management System in Laravel"
git remote add origin https://github.com/YrreSucksAtCoding/IT0049-Web-System-Technologies.git
```

Laravel's `.gitignore` already excludes `/vendor` and `.env` — correct,
since `.env` holds the database password. The migrations, seeders and
`database/tsa3_tasks.sql` all get committed, which covers the submission
requirement either way.

If you're committing into the existing course repo as a subfolder, don't run
`git init` inside it — copy the project folder into your clone and commit
from the repo root, same as TSA1.

---

## Hosting

Same shape as TFA6. Laravel on InfinityFree needs the web root pointed at
`public/`, and there's no command line, so step 5 replaces `artisan migrate`:

1. `composer install --no-dev --optimize-autoloader` locally so `vendor/` exists
2. Upload everything except `public/` into a folder inside `htdocs`
   (e.g. `htdocs/laravel_app/`) with a `Require all denied` `.htaccess`
3. Put the contents of `public/` directly in `htdocs`
4. Edit `htdocs/index.php` so its two `require` paths point at `laravel_app/`
5. Create the MySQL database in vPanel and **import `database/tsa3_tasks.sql`**
6. Set `.env` on the server: vPanel credentials, `APP_ENV=production`,
   `APP_DEBUG=false`, `APP_TIMEZONE=Asia/Manila`, and a real `APP_KEY`
   (`php artisan key:generate --show` locally, then paste it)

Step 6's timezone line is not optional for this project — on a server set to
UTC the Welcome page shows the wrong day's tasks for part of every day.

Ask and I'll write the hosting out step by step like the Midterm.
