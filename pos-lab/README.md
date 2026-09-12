# Simple POS System — CodeIgniter 4 (IT0049 TFA1)

A four-page CodeIgniter 4 application built to practice routing, controllers, and views.
No database yet — the Customer Accounts and User Accounts pages read from static PHP arrays.

## Pages

| URL | Controller method | What it shows |
|-----|-------------------|----------------|
| `/` | `Pages::index` | Landing page |
| `/about` | `Pages::about` | Description of the project |
| `/customers` | `Customers::index` | 5 customer records (name, email, phone) |
| `/users` | `Users::index` | 5 staff records (username, name, role) |

## Requirements

- PHP 8.1 or higher
- Composer
- PHP extensions: `intl`, `mbstring` (enable them in `php.ini` if XAMPP has them off)

## How to set it up

**1. Create the CodeIgniter project**

```bash
composer create-project codeigniter4/appstarter simple-pos
cd simple-pos
```

**2. Create the `.env` file**

CodeIgniter ships with `env` (no dot). Copy it and rename it:

```bash
cp env .env
```

Then open `.env` and set these two lines (remove the `#` in front of them):

```
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'
```

`development` turns on error messages, which makes debugging much easier.

**3. Copy in the project files**

Place the files from this folder into your new project, replacing what's there:

```
app/Config/Routes.php
app/Controllers/Pages.php
app/Controllers/Customers.php
app/Controllers/Users.php
app/Views/layouts/main.php
app/Views/pages/home.php
app/Views/pages/about.php
app/Views/customers/index.php
app/Views/users/index.php
```

You can delete the default `app/Views/welcome_message.php` and `app/Controllers/Home.php` — nothing points to them anymore.

## How to run it

From the project root:

```bash
php spark serve
```

Then open **http://localhost:8080** in your browser and click through the four nav links.

To use a different port:

```bash
php spark serve --port 9000
```

Stop the server with `Ctrl + C`.

### If you are using XAMPP instead

Put the project in `htdocs`, set `app.baseURL = 'http://localhost/simple-pos/public/'` in `.env`,
then open `http://localhost/simple-pos/public/`. Start Apache from the XAMPP control panel.

## Checks if something breaks

| Problem | Likely cause |
|---------|--------------|
| 404 on a page | The route is missing in `app/Config/Routes.php`, or the URL has a typo |
| "Class not found" | Controller filename and class name don't match, or `namespace App\Controllers;` is missing |
| Blank page | The controller called `view()` without `return` |
| "Undefined variable" in a view | The key wasn't included in the array passed to `view()` |
| Nav links go to the wrong place | `app.baseURL` in `.env` is wrong |

## How the MVC flow works here

1. Browser requests `/customers`.
2. `app/Config/Routes.php` matches it and points to `Customers::index`.
3. `Customers::index()` builds the static `$customers` array and returns `view('customers/index', $data)`.
4. `app/Views/customers/index.php` loops through `$customers` with a `foreach` and prints a table row for each record.
5. That HTML goes back to the browser as the response.

## Project structure

```
app/
├── Config/
│   └── Routes.php              all four URLs
├── Controllers/
│   ├── Pages.php               landing + about
│   ├── Customers.php           customer static array
│   └── Users.php               user static array
└── Views/
    ├── layouts/main.php        shared header, nav, footer, styles
    ├── pages/home.php
    ├── pages/about.php
    ├── customers/index.php     foreach table
    └── users/index.php         foreach table
```
