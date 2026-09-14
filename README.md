# Laravel Audit

**Who changed what, when, and from where.**

Audit logging for Laravel models, plus a login-attempt log, with an admin API to browse both. Logging is explicit: you decide which operations are worth an audit row and call `log()` there.

## Install

```bash
composer require innoboxrr/laravel-audit
php artisan vendor:publish --tag=laravel-audit-config   # optional
php artisan migrate
```

The host application is expected to provide:

- **Laravel Sanctum.** Every endpoint uses the `auth:sanctum` middleware, so a SPA with a Sanctum cookie session works as-is.
- **`isAdmin()` on the user model** (optional). Admins may use every endpoint. Other users are checked with `isAllowTo($ability, $model)` when the user model has it, and denied otherwise. Without either method the answer is a plain `403`.
- **`maatwebsite/excel`** (optional), only for the export endpoints. Without it they answer `501` with a message saying so.

## Recording audits

Add `Innoboxrr\LaravelAudit\Support\Traits\Auditable` to any model and call `log()` with the type of operation:

```php
use Innoboxrr\LaravelAudit\Support\Traits\Auditable;

class Invoice extends Model
{
    use Auditable;
}
```

```php
// Update: fill first, log, then save. `before` is the stored row and
// `after` the attributes about to be written.
$invoice->fill($request->validated());
$invoice->log('update');
$invoice->save();

// Create or delete: log once the model has an id.
$invoice = Invoice::create($data);
$invoice->log('create');

$invoice->audits; // morphMany of Innoboxrr\LaravelAudit\Models\Audit
```

Each row stores `before` and `after` (JSON), the request URL, IP and user agent, the authenticated user and an `Action` (`type`, and the model it applies to).

`log()` only writes inside an HTTP request with an authenticated user; in console commands, queued jobs and guest requests it does nothing and returns `null`.

## Recording login attempts

Add `Innoboxrr\LaravelAudit\Support\Traits\LoginAttempts` to the user model and call it from your login flow:

```php
use Innoboxrr\LaravelAudit\Support\Traits\LoginAttempts;

class User extends Authenticatable
{
    use LoginAttempts;
}

$user = User::where('email', $request->email)->first();

if ($user) {
    $user->trackLoginAttempt(Auth::attempt($credentials)); // true or false
}

$user->loginAttempts; // hasMany, matched by email
```

## Endpoints

Three resources, `audit`, `action` and `login_attempt`, each under `/api/innoboxrr/laravel-audit/{resource}/` with route names `api.innoboxrr.laravel.audit.{resource}.*`:

| Method | Endpoint | |
|---|---|---|
| `GET` | `policies`, `policy` | What the current user may do. `policy=index` returns `{"index": true}`. |
| `GET` | `index` | Paginated list through `innoboxrr/search-surge`. `paginate=0` returns every row. |
| `GET` | `show` | `audit_id`, `action_id` or `login_attempt_id`. |
| `POST` `PUT` `DELETE` | `create`, `update`, `delete`, `restore`, `force-delete` | Standard operations, subject to the same policies. |
| `POST` | `export` | Builds an `.xlsx` on `export_disk` and notifies the user. Needs `maatwebsite/excel`. |

## Configuration

| Key | Default | |
|---|---|---|
| `db_prefix` | `''` | Prefix for the `audits`, `actions` and `login_attempts` tables. |
| `user_class` | `App\Models\User` | Model behind `Audit::user()`. |
| `export_disk` | `env('LARAVEL_AUDIT_EXPORT_DISK', 'local')` | Disk for exports. |
| `notification_via` | `['mail', 'database']` | Channels for the export notification. `database` needs Laravel's `notifications` table. |

## Related

- [`innoboxrr/audit-pkg`](https://github.com/innoboxrr/audit-pkg) — operation and failed-access auditing for surfacing brute-force attempts.

## Built by

[Innobox R&R](https://github.com/innoboxrr) — extracted from production systems. Part of a catalogue of 52 open-source packages on Packagist and npm.

**[innobox.systems](https://innobox.systems)**

## Documentación / Documentation

Documentación completa del ecosistema, en español y en inglés / Full ecosystem documentation, in Spanish and English: <https://innoboxrr.github.io/docs/paquetes/laravel-audit>
