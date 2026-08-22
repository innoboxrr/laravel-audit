# Laravel Audit

**Who changed what, when, and from where.**

Model-level audit logging for Laravel. Add one trait and every create, update and delete on that model is recorded with the full before/after diff, the acting user, the IP and the request context.

```php
use Innoboxrr\LaravelAudit\Traits\Auditable;

class Invoice extends Model
{
    use Auditable;
}
```

That is the whole integration.

---

## What it records

| Field | Detail |
|---|---|
| **Event** | created · updated · deleted · restored |
| **Diff** | Old and new values, per attribute — not a blob of the whole row |
| **Actor** | The authenticated user, or the console command that caused it |
| **Context** | IP address, user agent, route and request ID |
| **Timestamp** | When it happened, in UTC |

## Why you want it before you need it

Audit logging is the feature nobody asks for until an invoice total changes and nobody can explain why. Adding it retroactively means the history you actually need does not exist. This package is small enough to add on day one.

**Compliance-friendly.** Append-only by design: audit rows are never updated, and deletion is deliberate rather than incidental.

---

## Install

```bash
composer require innoboxrr/laravel-audit
php artisan vendor:publish --tag=laravel-audit-config
php artisan migrate
```

Control which attributes are recorded or excluded per model with `$auditInclude` and `$auditExclude`.

---

## Related

- [`innoboxrr/audit-pkg`](https://github.com/innoboxrr/audit-pkg) — operation and failed-access auditing for surfacing brute-force attempts.

## Built by

[Innobox R&R](https://github.com/innoboxrr) — extracted from production systems. Part of a catalogue of 52 open-source packages on Packagist and npm.

**[innobox.systems](https://innobox.systems)**
