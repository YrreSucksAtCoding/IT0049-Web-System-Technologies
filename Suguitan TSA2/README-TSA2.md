# Tasks for Today Management System — TSA2: Full CRUD and Authentication

Extends the TSA1 application with validated create/update workflows, a login
requirement for managing tasks, and soft deletion.

IT0049 — Web System Technologies.

## What this activity added

| Requirement | Where it lives |
|---|---|
| `password` column on `users`, hashed for the demo user | `database/tsa2_add_auth_archive.sql` |
| Login page and logout action | `app/Controllers/Auth.php`, `app/Views/auth/login.php` |
| Welcome, Task List, Profile and About stay public | routes outside the filter group |
| Creating / editing / deleting a task requires login | `app/Filters/AuthFilter.php` + the `auth` route group |
| New Task form at `/tasks/new`, title and date required | `Tasks::create` / `Tasks::store` |
| Edit and update pages | `Tasks::edit` / `Tasks::update` |
| `is_archived` column, delete sets the flag | `TaskModel::archive()`, `Tasks::delete()` |
| Archived tasks excluded from Welcome and Task List | `where('is_archived', 0)` in `TaskModel` |

## Demo login

| Username | Password |
|---|---|
| `demo_user` | `demo1234` |

Only the bcrypt hash is stored. The plain password appears here and in the SQL
comments purely so the project can be marked.

## Public vs protected

**Public — no login needed:**

```
GET  /                  Welcome, today's tasks
GET  /tasks             full task list
GET  /profile           the demo user
GET  /about             static page
GET  /login             the login form
```

**Protected — redirects to /login when logged out:**

```
GET  /tasks/new             POST /tasks/store
GET  /tasks/edit/{id}       POST /tasks/update/{id}
POST /tasks/delete/{id}     (archives, does not remove)
GET  /tasks/archived        POST /tasks/restore/{id}
```

The guard is a filter applied to a route group in `app/Config/Routes.php`, not
an `if` repeated at the top of every method. A new protected action is protected
the moment its route goes inside the group — there is no per-page check to
forget.

## Soft deletion

The Delete button does **not** remove the row:

```php
// TaskModel
public function archive(int $id)
{
    return $this->update($id, ['is_archived' => 1]);
}
```

Every query that feeds a listing excludes archived rows:

```php
public function getAllTasks()
{
    return $this->where('is_archived', 0)
                ->orderBy('task_date', 'ASC')
                ->findAll();
}
```

That condition lives in the model rather than in each controller, so there is
one place where "archived means hidden" is decided.

An **Archived** page (login required) lists the hidden rows with a Restore
button, which is what makes the difference between archiving and deleting
visible — the data is still there.

Delete and Restore are POST forms with CSRF tokens, not links. A plain link can
be triggered by anything that merely loads the URL.

## Setup

**1. Create the database**

Starting fresh — import `database/tasks_today.sql`. It creates both tables, 11
tasks (one already archived) and the demo user with a hashed password.

Already have the TSA1 database — import `database/tsa2_add_auth_archive.sql`
instead. It adds the two columns and sets the demo user's password.

phpMyAdmin → **Import** → choose the file → **Go**.

**2. Check `.env`**

```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

database.default.hostname = 127.0.0.1
database.default.database = tasks_today
database.default.username = root
database.default.password = root
database.default.DBDriver = MySQLi
database.default.port = 8889
```

Use `127.0.0.1`, not `localhost` — with `localhost`, PHP ignores the port and
looks for a unix socket, which fails on MAMP. Port 8889 and password `root` are
MAMP's defaults; XAMPP uses 3306 with a blank password.

**3. Run**

```bash
php spark serve
```

Open <http://localhost:8080>.

## Testing access control

The activity asks you to test this the same way as TFA4.

| Step | Expected |
|---|---|
| Logged out, open `/` and `/tasks` | Both load normally — reading is public |
| Logged out, open `/tasks/new` directly | Redirected to `/login` with "Please log in to continue." |
| Logged out, open `/tasks/edit/1` directly | Also redirected |
| Logged out, look at the Task List | No New / Edit / Delete buttons are shown |
| Log in as `demo_user` / `demo1234` | Lands on the page you originally asked for; the buttons appear |
| Submit the New Task form with an empty title | Rejected with an error, and the other fields keep what you typed |
| Delete a task | It vanishes from Task List and Welcome |
| Open **Archived** | The deleted task is listed, with a Restore button |
| Check the `tasks` table in phpMyAdmin | The row is still there with `is_archived = 1` |
| Log out, press Back | The protected page does not come back |

The last two are the ones worth screenshotting — together they prove the delete
is soft rather than permanent.

## Project structure

```
app/
├── Config/
│   ├── Routes.php              public routes + the 'auth' filtered group
│   └── Filters.php             'auth' alias registered
├── Controllers/
│   ├── Home.php                Welcome — today's tasks
│   ├── Tasks.php               list, create, store, edit, update, delete, archived, restore
│   ├── Profile.php             the demo user
│   ├── Pages.php               about
│   └── Auth.php                login, attempt, logout
├── Filters/
│   └── AuthFilter.php          the login check, written once
├── Models/
│   ├── TaskModel.php           archived rows excluded; archive() and restore()
│   └── UserModel.php           getByUsername() for the login check
└── Views/
    ├── layouts/main.php        nav, session bar, flash messages
    ├── auth/login.php
    ├── tasks/today.php         Welcome
    ├── tasks/index.php         full list with Edit and Delete
    ├── tasks/form.php          shared add/edit form
    ├── tasks/archived.php      soft-deleted rows, with Restore
    ├── profile/index.php
    └── pages/about.php

database/
├── tasks_today.sql             full export: schema + sample data
└── tsa2_add_auth_archive.sql   ALTER statements for an existing TSA1 database
```

## Troubleshooting

| Problem | Likely cause |
|---|---|
| "Unknown column 'is_archived'" | The SQL file has not been imported |
| "Unknown column 'password'" | Same — or only part of the file ran |
| Correct password rejected | The user row still has an empty password; re-run the UPDATE |
| Protected page loads while logged out | The `auth` alias is missing from `app/Config/Filters.php`, or the route sits outside the group |
| Redirect loop on `/login` | The login route was placed inside the filtered group by mistake |
| Deleted task still shows in the list | A query is missing `where('is_archived', 0)` |
| Welcome page empty but All Tasks works | The date filter found nothing — re-import so the `CURDATE()` rows are current |
| "Unable to connect… No such file or directory" | `hostname` is `localhost`; change it to `127.0.0.1` |
| "Whoops! We seem to have hit a snag" | `CI_ENVIRONMENT` is `production`; set it to `development` to read the real error |

## Before submitting

- [ ] Add your section and professor to the About page and below
- [ ] Commit `database/tasks_today.sql` — it is the required database export
- [ ] Include screenshots of the logged-out redirect and the archived row still in the table
- [ ] Confirm the hosted link also blocks the protected actions

## Student

| | |
|---|---|
| Name | Yrre Suguitan |
| Section | |
| Professor | |
