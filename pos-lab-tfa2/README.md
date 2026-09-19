# Simple POS System — CodeIgniter 4 + MySQL (IT0049 TFA2)

A four-page CodeIgniter 4 application. The Customer Accounts and User Accounts pages
read their records from a MySQL database through CodeIgniter Models and Query Builder.

This is TFA2. In TFA1 the same two pages were fed by static PHP arrays — those are gone.

## Pages

| URL | Controller method | Data source |
|-----|-------------------|-------------|
| `/` | `Pages::index` | — |
| `/about` | `Pages::about` | — |
| `/customers` | `Customers::index` | `CustomerModel` → `customers` table |
| `/users` | `Users::index` | `UserModel` → `users` table |

## Requirements

- PHP 8.1 or higher, with the `intl`, `mbstring` and `mysqlnd` extensions
- Composer
- MySQL / MariaDB (XAMPP, MAMP, or a standalone install)

## Setup

**1. Get the project and its dependencies**

```bash
git clone <your-repo-url> simple-pos
cd simple-pos
composer install
```

`vendor/` is not committed, so `composer install` is required.

**2. Create the database**

Start MySQL first (XAMPP/MAMP control panel, or `brew services start mysql`), then:

```bash
mysql -u root -p < database/simple_pos.sql
```

That one file creates the `simple_pos` database, both tables, and 5 sample records each.

Using phpMyAdmin instead: open it, click **Import**, choose `database/simple_pos.sql`, click **Go**.

**3. Configure `.env`**

```bash
cp env .env
```

Open `.env` and set the app and database lines (see `env-database-settings.txt` for the
full block and the MAMP/XAMPP differences):

```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = simple_pos
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

**4. Run it**

```bash
php spark serve
```

Open <http://localhost:8080> and click through all four pages. The Customer Accounts
and User Accounts tables should each show 5 rows.

To confirm the connection separately:

```bash
php spark db:table customers
```

## Database schema

Exactly as given in the activity handout.

```sql
CREATE TABLE customers (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    full_name  VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    phone      VARCHAR(20),
    created_at DATETIME     NOT NULL
);

CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    full_name  VARCHAR(100) NOT NULL,
    created_at DATETIME     NOT NULL
);
```

Note: the `users` table in this schema has no `role` column, so the User Accounts page
shows username, full name, and date added. The TFA1 version displayed a role from the
static array; that field is not part of the provided schema.

## How the data flows now

1. Browser requests `/customers`.
2. `app/Config/Routes.php` points to `Customers::index`.
3. The controller creates a `CustomerModel` and calls `getAllCustomers()`.
4. The model runs `orderBy('created_at', 'DESC')->findAll()` — Query Builder, no raw SQL.
5. The rows come back as arrays and go to the view as `$customers`.
6. The view loops them with the same `foreach` it used for the static array.

Only step 3–4 changed between TFA1 and TFA2. The route, the view, and the loop are the same.

## Project structure

```
app/
├── Config/
│   └── Routes.php              all four URLs
├── Controllers/
│   ├── Pages.php               landing + about
│   ├── Customers.php           calls CustomerModel
│   └── Users.php               calls UserModel
├── Models/
│   ├── CustomerModel.php       wraps the customers table
│   └── UserModel.php           wraps the users table
└── Views/
    ├── layouts/main.php        shared header, nav, footer, styles
    ├── pages/home.php
    ├── pages/about.php
    ├── customers/index.php     foreach over database rows
    └── users/index.php         foreach over database rows

database/
└── simple_pos.sql              database export: schema + sample records
```

## Troubleshooting

| Problem | Likely cause |
|---------|--------------|
| `Unable to connect to the database` | MySQL isn't running, or the `.env` credentials/port are wrong (MAMP uses 8889) |
| `Table 'simple_pos.customers' doesn't exist` | `database/simple_pos.sql` was never imported |
| Page loads but the table is empty | Imported the schema without the INSERT statements — re-import the whole file |
| `Undefined array key "role"` | Leftover TFA1 view code; the provided schema has no `role` column |
| 404 on a page | Missing route in `app/Config/Routes.php` |
| Blank page | The controller called `view()` without `return` |

## Student

| | |
|---|---|
| Name | |
| Section | |
| Professor | |
