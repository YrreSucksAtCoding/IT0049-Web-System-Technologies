# Complete Point-of-Sale System

**Live site:** https://suguitanpossystem.infinityfreeapp.com
**Repository:** https://github.com/YrreSucksAtCoding/IT0049-Web-System-Technologies

CodeIgniter 4 + MySQL. IT0049 — Web System Technologies, Midterm Project.

Brings together Modules 1–4 — routing and MVC, a database, validated forms with
file upload, and authentication — and adds the sales workflow: recording a
transaction that decreases stock.

---

## Test accounts

Every page requires a login. Use any of these:

| Username | Password | Name |
|---|---|---|
| `admin01` | `admin123` | Yrre Suguitan |
| `cashier01` | `cash123` | Bea Lopez |
| `manager01` | `mgr123` | Carlo Mendoza |

Only bcrypt hashes are stored — check the `users` table and you will see
`$2y$12$...`, never these strings. They are written here so the project can be
marked.

## Suggested walkthrough

1. Sign in as `admin01` / `admin123` → **Dashboard**
2. **Record Sale** → *Scissors, 8 inch* → quantity **99** → refused, naming the
   stock actually on hand
3. Same product, quantity **2** → saves, and Products shows the stock reduced
4. **Sales History** → the sale appears with product, customer, staff and total
5. **Products** → Delete any product → it leaves the list, but the sale that
   names it still reads correctly → **Archived** → Restore
6. **Log out**, then open `/products` directly → redirected to the login page

---

## Features

| Area | What it does |
|---|---|
| **Products** | List, add, edit, archive, restore. Optional image, resized to 400×400. Low-stock and out-of-stock badges. |
| **Customers** | Full CRUD with a validated, unique email. |
| **Staff** | Full CRUD with hashed passwords and an avatar resized to 200×200. |
| **Authentication** | Every page except the login form requires a signed-in staff member. |
| **Record Sale** | Product, optional customer, quantity. Stock comes down; overselling is refused with the stock on hand named. |
| **Sales History** | Product, customer (or Walk-in), staff, quantity, total, date. |
| **Dashboard** | Counts, revenue, products running low, five most recent sales. |

## Pages

| URL | Access |
|---|---|
| `/login` | public |
| `/` | dashboard — login required |
| `/products`, `/products/new`, `/products/edit/{id}`, `/products/archived` | login required |
| `/customers`, `/customers/new`, `/customers/edit/{id}`, `/customers/archived` | login required |
| `/staff`, `/staff/new`, `/staff/edit/{id}`, `/staff/archived` | login required |
| `/sales` (history), `/sales/new` (record a sale) | login required |

---

## Running it locally

**1. Dependencies**

```bash
composer install
```

`vendor/` is not committed, so this is required after cloning.

**2. Database**

phpMyAdmin → **Import** → `database/pos_midterm.sql` → **Go**.

Creates all four tables with their foreign keys and seeds 9 products,
5 customers, 3 staff accounts and 6 sales.

**3. `.env`**

```bash
cp env .env
```

```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

database.default.hostname = 127.0.0.1
database.default.database = pos_midterm
database.default.username = root
database.default.password = root
database.default.DBDriver = MySQLi
database.default.port = 8889
```

Use `127.0.0.1`, not `localhost` — with `localhost`, PHP ignores the port and
looks for a unix socket, which fails on MAMP. Port 8889 / password `root` are
MAMP's defaults; XAMPP uses 3306 with a blank password.

**4. Upload folders**

```bash
chmod -R 775 public/uploads
```

**5. Run**

```bash
php spark serve
```

Open <http://localhost:8080>.

Requires **PHP 8.2+** with `intl`, `mbstring`, `mysqli` and `gd`. CodeIgniter
4.7 returns a 503 below 8.2, and `gd` is what resizes the uploaded images.

---

## Database design

```
products ──┐
customers ─┼──→ sales
users ─────┘
```

`sales` is the transaction table: three foreign keys, plus quantity and total.

**`customer_id` is nullable.** A walk-in buyer is a real case, so the column
allows NULL and the history query joins customers with a LEFT join. An inner
join would silently hide every walk-in sale.

**`total_price` is stored, not recalculated.** Price × quantity could be worked
out on display, but a product's price changes over time and an old receipt must
not change with it. The total is a fact about the moment of sale.

**InnoDB, not MyISAM.** MyISAM accepts a `FOREIGN KEY` clause and then ignores
it, which would let a sale point at a product that does not exist.

**Delete is soft — the `is_archived` flag.** This is an addition to the handout
schema; the relationships are unchanged. A product or staff member named on a
past sale *cannot* be removed without breaking the foreign key, and removing it
would erase what that sale was for. Archiving hides the row from the management
lists and the sale form while the history stays readable. Each section has an
**Archived** page with a Restore button.

## The sales workflow

`Sales::store()` checks cheapest-first, then writes:

1. **Validate** — `is_not_unique[products.id]` proves the id exists, so a
   hand-posted id cannot create a sale for a product that was never there.
2. **Check availability** — the product must exist and not be archived.
3. **Check stock** — `quantity > stock_quantity` is refused with a message
   naming the product and how many are left, so the cashier can adjust.
4. **Write both tables in a transaction:**

```php
$db->transStart();
$saleModel->insert([... 'sold_by' => session()->get('user_id') ...]);
$productModel->decreaseStock($productId, $quantity);
$db->transComplete();
```

Without the transaction, a failure between the two writes would leave stock
reduced with no sale to show for it.

`decreaseStock()` subtracts in SQL rather than reading into PHP and writing back:

```php
->set('stock_quantity', 'stock_quantity - ' . (int) $quantity, false)
->where('stock_quantity >=', $quantity)
```

Two sales at the same instant would otherwise both read the same starting number
and the second would overwrite the first. The `where` makes negative stock
impossible.

`sold_by` comes from the session, never the form — a posted field could
otherwise credit the sale to someone else.

---

## Security

| Concern | How it is handled |
|---|---|
| Passwords | `password_hash()` on save, `password_verify()` on login. Never stored or logged as typed. |
| Session fixation | `session()->regenerate(true)` fires *before* anything is written to the session. |
| Username enumeration | One message — "Invalid username or password" — for unknown username, archived account and wrong password alike. |
| Access control | An `AuthFilter` on a route group, so a new page is protected by adding its route, not by remembering an `if`. |
| CSRF | Every state-changing action is a POST form with `csrf_field()`. No deletes behind plain links. |
| Mass assignment | `$allowedFields` on every model. |
| SQL injection | Query Builder throughout; values are bound, not concatenated. |
| File upload | `mime_in` as well as `ext_in` (an extension is just a filename), `max_size`, `getRandomName()` so the user's filename is never reused, and only the filename goes in the database. |
| XSS | `esc()` on every value a view prints. |

---

## Project structure

```
app/
├── Config/
│   ├── Routes.php              login public; everything else in the auth group
│   └── Filters.php             'auth' alias registered
├── Controllers/
│   ├── Auth.php                login, attempt, logout
│   ├── Dashboard.php           totals, low stock, recent sales
│   ├── Products.php            CRUD + archive/restore
│   ├── Customers.php           CRUD + archive/restore
│   ├── Staff.php               CRUD + archive/restore, hashing, avatars
│   └── Sales.php               record sale, sales history
├── Filters/
│   └── AuthFilter.php          the login check, written once
├── Libraries/
│   └── ImageUploader.php       shared upload, resize, delete and URL logic
├── Models/
│   ├── ProductModel.php        getSellable(), getLowStock(), decreaseStock()
│   ├── CustomerModel.php
│   ├── UserModel.php           getByUsername() for the login
│   └── SaleModel.php           getHistory() with the three joins
└── Views/
    ├── layouts/main.php
    ├── auth/login.php
    ├── dashboard/index.php
    ├── products/   index, form, archived
    ├── customers/  index, form, archived
    ├── staff/      index, form, archived
    └── sales/      new, index

public/
├── img/            product-placeholder.png, avatar-placeholder.png
└── uploads/        products/, avatars/

database/
└── pos_midterm.sql    schema + seed data
```

Product images and avatars need identical handling — rename, move, resize,
delete the old file, build a URL. Rather than writing that twice,
`App\Libraries\ImageUploader` holds it and both controllers call it.

---

## Troubleshooting

| Problem | Likely cause |
|---|---|
| "Unable to connect… No such file or directory" | `hostname` is `localhost`; change it to `127.0.0.1` |
| "Unable to connect… Access denied" | Wrong MySQL password (MAMP uses `root`/`root`) |
| "Unknown database 'pos_midterm'" | `database/pos_midterm.sql` has not been imported |
| Broken image icons | `app.baseURL` is wrong, or `public/uploads` is not writable |
| "Cannot add or update a child row" | Imported into a MyISAM database, or a referenced row is missing |
| "Unable to write file" | `chmod -R 775 public/uploads` |
| Redirect loop on `/login` | The login route was placed inside the filtered group by mistake |
| Logged out right after signing in | `app.baseURL` does not match the address bar, so the session cookie is dropped |
| "Whoops! We seem to have hit a snag" | `CI_ENVIRONMENT` is `production`; set it to `development` to read the real error |

---

## Student

| | |
|---|---|
| Name | Yrre Suguitan |
| Section | |
| Professor | |
| Subject | IT0049 — Web System Technologies |
| Deliverable | Midterm Project |
