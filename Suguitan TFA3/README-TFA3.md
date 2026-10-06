# Simple POS System — TFA3: Forms, Validation, and File Upload

Extends the TFA2 POS application with create and edit forms for customer and
user accounts, server-side validation, and avatar upload for user accounts.

IT0049 — Web System Technologies.

## What this activity added

| Requirement | Where it lives |
|---|---|
| New Customer form at `/customers/new`, validated before insert | `Customers::create` / `Customers::store`, `Views/customers/form.php` |
| New User form at `/users/new`, username required and unique | `Users::create` / `Users::store`, `Views/users/form.php` |
| Edit pages for both, pre-filled and updating on submit | `Customers::edit` / `update`, `Users::edit` / `update` |
| `avatar` column on `users` | `database/tfa3_add_avatar.sql` |
| Avatar upload: JPG/PNG, max 2 MB, resized, filename saved | `Users::saveAvatar()` |
| Avatar shown on the listing, placeholder when none | `Views/users/index.php` |

## Pages

| URL | Method | What it does |
|---|---|---|
| `/customers` | GET | Customer list, with New and Edit buttons |
| `/customers/new` | GET | Blank customer form |
| `/customers/store` | POST | Validates, inserts, redirects |
| `/customers/edit/{id}` | GET | Form pre-filled with that record |
| `/customers/update/{id}` | POST | Validates, updates, redirects |
| `/users` | GET | User list with avatars |
| `/users/new` | GET | Blank user form |
| `/users/store` | POST | Validates, saves avatar, inserts |
| `/users/edit/{id}` | GET | Form pre-filled, shows current avatar |
| `/users/update/{id}` | POST | Validates, replaces avatar if a new file was chosen |

## Setup

**1. Update the database**

If you already have the TFA2 database:

```sql
USE simple_pos;
ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL AFTER full_name;
```

That statement is in `database/tfa3_add_avatar.sql`. Starting fresh instead?
Import `database/simple_pos.sql`, which already includes the column.

**2. Make the upload folder writable**

```bash
mkdir -p public/uploads/avatars
chmod -R 775 public/uploads
```

**3. Check `.env`**

Unchanged from TFA2. On MAMP:

```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

database.default.hostname = 127.0.0.1
database.default.database = simple_pos
database.default.username = root
database.default.password = root
database.default.DBDriver = MySQLi
database.default.port = 8889
```

`app.baseURL` matters more than before — `base_url()` builds the avatar image
URLs, so if it is wrong the pictures will not load even though the files exist.

**4. Run**

```bash
php spark serve
```

Then visit <http://localhost:8080/users>, click Edit on a user, and upload a
picture.

## How the upload works

1. The form carries `enctype="multipart/form-data"`. Without it, PHP receives
   only the filename as text and `getFile('avatar')` comes back empty.
2. Validation runs first: `is_image`, `mime_in`, `ext_in`, `max_size[avatar,2048]`.
   `mime_in` matters because `ext_in` only checks the filename — a file can be
   renamed, so the real content type has to be checked too.
3. `getRandomName()` generates a new filename. The name the user gave is never
   trusted or reused.
4. The file moves to `public/uploads/avatars/`.
5. CodeIgniter's Image service makes a display-ready 200×200 square with
   `fit(200, 200, 'center')`, overwriting the moved file.
6. Only the generated filename goes into the `avatar` column. The view adds the
   folder back with `base_url('uploads/avatars/' . $user['avatar'])`.
7. When an avatar is replaced, the old file is deleted so they do not pile up.

Leaving the file input empty on an edit keeps the existing picture — the file
rules are only applied when a file was actually chosen.

## How validation errors come back

```php
if (! $this->validate($rules)) {
    return redirect()->back()
        ->withInput()
        ->with('errors', $this->validator->getErrors());
}
```

`withInput()` is what preserves the user's entries. The form reads them with
`old('full_name', $customer['full_name'] ?? '')` — the typed value if validation
just failed, otherwise the stored value, otherwise blank. Without it a rejected
form wipes everything the user typed.

On the edit form the unique rule becomes
`is_unique[customers.email,id,{id}]`. Plain `is_unique` makes a record collide
with itself: you would open a customer, change nothing, save, and be told the
email is already taken.

## Files in this activity

```
app/
├── Config/Routes.php              + new/store/edit/update routes
├── Controllers/
│   ├── Customers.php              index, create, store, edit, update
│   ├── Users.php                  same + saveAvatar()
│   └── Pages.php                  home, about
├── Models/
│   ├── CustomerModel.php
│   └── UserModel.php              avatar added to $allowedFields
└── Views/
    ├── layouts/main.php           + form, button, alert and avatar styles
    ├── customers/form.php         shared add/edit form
    ├── customers/index.php        + New and Edit buttons
    ├── users/form.php             shared add/edit form with file input
    ├── users/index.php            + avatar column
    └── pages/home.php, about.php

public/
├── img/avatar-placeholder.png     fallback when no avatar is uploaded
└── uploads/avatars/               where uploaded files are stored

database/
├── simple_pos.sql                 full export, avatar column included
└── tfa3_add_avatar.sql            ALTER statement only
```

## Troubleshooting

| Problem | Likely cause |
|---|---|
| Avatars show as broken images | `app.baseURL` is wrong, or `public/uploads/avatars` is not writable |
| "The avatar field is not a valid image" on a real photo | The file is a different format than its extension claims — try re-saving as JPG or PNG |
| Upload silently does nothing | The form is missing `enctype="multipart/form-data"` |
| "Unable to write file" | `chmod -R 775 public/uploads` |
| Large photo rejected before validation runs | PHP's own `upload_max_filesize` / `post_max_size` in php.ini are below 2 MB |
| Edit saves a duplicate instead of updating | `update($id, $data)` is being called without the id |

## Before submitting

- [ ] Add your section and professor below
- [ ] Commit `database/simple_pos.sql` — it is the required database export
- [ ] Do **not** commit uploaded avatar files; keep `public/uploads/avatars/.gitkeep`
- [ ] Confirm the hosted link shows the forms and the avatars

## Student

| | |
|---|---|
| Name | Yrre Suguitan |
| Section | |
| Professor | |
