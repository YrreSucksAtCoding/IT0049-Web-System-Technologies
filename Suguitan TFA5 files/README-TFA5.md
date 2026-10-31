# TFA5 — Laravel Routes, Controllers and Blade Views

**Yrre Suguitan** · IT0049 — Web System Technologies

The same four pages from TFA1, rebuilt in Laravel.

| Page | URL | Route name | Controller method |
|---|---|---|---|
| Home | `/` | `home` | `PageController::index()` |
| About | `/about` | `about` | `PageController::about()` |
| Customer Accounts | `/customers` | `customers` | `CustomerController::index()` |
| User Accounts | `/users` | `users` | `UserController::index()` |

---

## Setup (one time)

Laravel has to be installed by Composer first — these files are only the
app code, not the whole framework.

Open Terminal and run:

```bash
cd "/Applications/MAMP/htdocs/IT0049 Web System Technologies"
composer create-project laravel/laravel "Suguitan TFA5"
```

That takes a few minutes and creates the full Laravel project.

Then copy my files into it, overwriting the defaults:

```bash
cd "/Applications/MAMP/htdocs/IT0049 Web System Technologies"
cp -R "Suguitan TFA5 files/routes" "Suguitan TFA5/"
cp -R "Suguitan TFA5 files/app" "Suguitan TFA5/"
cp -R "Suguitan TFA5 files/resources" "Suguitan TFA5/"
```

(The default `resources/views/welcome.blade.php` can stay — nothing points
to it any more, because `/` now goes to `PageController`.)

---

## Running it

```bash
cd "/Applications/MAMP/htdocs/IT0049 Web System Technologies/Suguitan TFA5"
php artisan serve
```

Then open **http://127.0.0.1:8000**

Port **8000**, not 8080 — that is Laravel's built-in server.
Press `Control + C` in Terminal to stop it.

MAMP does not need to be running. There is no database in this activity,
so `php artisan serve` is all it takes.

### If `php artisan serve` complains about the PHP version

Laravel 12 needs PHP 8.2 or newer. Check with:

```bash
php -v
```

If the Mac's built-in PHP is too old, use MAMP's instead:

```bash
/Applications/MAMP/bin/php/php8.2.0/bin/php artisan serve
```

(replace `php8.2.0` with whichever version folder you actually have —
`ls /Applications/MAMP/bin/php/` shows them).

---

## What each file does

```
routes/web.php                              every URL of the app
app/Http/Controllers/PageController.php     home + about
app/Http/Controllers/CustomerController.php customer array → view
app/Http/Controllers/UserController.php     user array → view
resources/views/layouts/app.blade.php       shared header, nav, styling
resources/views/pages/home.blade.php
resources/views/pages/about.blade.php
resources/views/customers/index.blade.php   @foreach over $customers
resources/views/users/index.blade.php       @foreach over $users
```

The records are still **static PHP arrays** inside the controllers — no
database is connected, same as TFA1.

---

## Laravel vs CodeIgniter 4, in the parts I touched

| | CodeIgniter 4 | Laravel |
|---|---|---|
| Routes file | `app/Config/Routes.php` | `routes/web.php` |
| Route handler | `'Pages::about'` string | `[PageController::class, 'about']` array |
| Controllers | `app/Controllers/` | `app/Http/Controllers/` |
| Views | `app/Views/about.php` | `resources/views/pages/about.blade.php` |
| Printing a value | `<?= esc($title) ?>` | `{{ $title }}` (escapes on its own) |
| Looping | `<?php foreach (...): ?>` | `@foreach ... @endforeach` |
| Layout | `$this->extend()` / `renderSection()` | `@extends` / `@yield` |
| Row counter | own `$i++` | `$loop->iteration` |
| Dev server | `php spark serve` → port 8080 | `php artisan serve` → port 8000 |

The biggest practical difference: Laravel route **names**. Writing
`route('customers')` in a view means the nav links keep working even if I
change the URL later, and `request()->routeIs('customers')` is what
highlights the current page in the menu.

---

## What to submit

1. Screenshot of all four pages running at `127.0.0.1:8000`
2. `routes/web.php`
3. The three controllers
4. The five Blade views
