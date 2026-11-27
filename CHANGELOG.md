# Changelog

Todas las modificaciones notables a este proyecto serán documentadas en este archivo.

El formato se basa en [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
y este proyecto sigue [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

### Changed

### Removed

### Fixed

## [2.1.2] - 2026-11-27

### Cambiado

- **Se desarrolla con `larapack-generator` 9.** La dependencia de desarrollo
  pedía `^7.0` y pasa a `^9.0`, la línea base del ecosistema. Para quien
  instala el paquete no cambia nada: `require-dev` no viaja a la aplicación.
  Cambia lo que se instala para desarrollarlo y probarlo: `./builder` genera con
  las plantillas de la 9 —claves ajenas en `restrict`, `managed` forzado en el
  IndexRequest, `$appendable` en el Resource— y la CI audita contra la línea
  base de hoy, no contra la que traía la 7.10.3.

  El paquete no usa ninguna clase del generador, sólo sus comandos, y el
  `composer.json` del generador es el mismo de la 7.10.3 a la 9.1.0: el resto
  de lo que se resuelve no cambia. Se fija `^9.0` en vez de ampliar a
  `^7.0 || ^8.0 || ^9.0` porque en desarrollo no hay consumidor al que dejarle
  la 7, y el rango ancho sólo serviría para que la CI resolviera un generador
  viejo.

## [2.1.1] - 2026-09-13

Lo que el paquete le hacía a la aplicación de Laravel 11+ que lo instala al
arrancar.

### Corregido

- La aplicación cargaba sus `routes/web.php` y `api.php` una vez más por este
  paquete: `RouteServiceProvider` heredaba del de Foundation, que vuelve a
  ejecutar el cargador de `withRouting()`. Ahora hereda de
  `Illuminate\Support\ServiceProvider` y registra las rutas en `boot()`, salvo
  con las rutas cacheadas.
- Cada usuario nuevo recibía repetido el correo de verificación:
  `EventServiceProvider` heredaba del de Foundation, que agrega otro
  `SendEmailVerificationNotification` para `Registered`.
- `AuthServiceProvider` nunca registraba `AuditPolicy`, `ActionPolicy` ni
  `LoginAttemptPolicy`: armaba mal el nombre del modelo, y las policies solo
  se aplicaban por el adivinador de nombres de Laravel. Ahora se registran con
  `Gate::policy()`.

URIs, nombres de ruta, middleware y respuestas no cambian.

## [2.1.0] - 2026-09-13

Lo que hacía falta para que el paquete funcione en una aplicación nueva de
Laravel 13 que lo instala por defecto.

### Añadido

- `export_disk` se lee de `LARAVEL_AUDIT_EXPORT_DISK`.
- La config se publica con `--tag=laravel-audit-config` (o `--tag=config`);
  antes la publicación estaba comentada y el comando del README no hacía nada.
- `innoboxrr/search-surge ^3.0` e `innoboxrr/traits ^2.1` en `require`: el
  paquete los usa en tiempo de ejecución y no los declaraba.
- Suite de pruebas contra las rutas reales de `audit`, `action` y
  `login_attempt`: invitado 401, usuario sin permisos 403, admin lista y ve,
  `paginate=0` trae todo. También prueba `log()`, `trackLoginAttempt()`, el
  rollback con prefijo y el arranque con `CACHE_STORE=database` sin tabla
  `cache`; los proveedores no leen la cache al arrancar.

### Cambiado

- **El disco por defecto de las exportaciones pasa de `s3` a `local`.** Una
  aplicación que exporta a S3 sin haber publicado la config debe fijar
  `LARAVEL_AUDIT_EXPORT_DISK=s3`.
- Exportar sin `maatwebsite/excel` (sugerido, no requerido) responde 501 con
  un mensaje que dice qué instalar, en lugar de un 500 al notificar.
- Las policies comprueban `isAdmin()` e `isAllowTo()` con `method_exists`: un
  usuario de host sin esos métodos recibe 403 y no un error fatal. Las firmas
  usan `Illuminate\Contracts\Auth\Authenticatable` y no `App\Models\User`.

### Eliminado

- `doctrine/dbal`: Laravel 11+ modifica columnas sin él y nada del paquete lo
  usa.

### Corregido

- `config/laravel-audit.php` llamaba a `root_path()`, que solo existe en
  larapack-generator (dependencia de desarrollo): con `composer install
  --no-dev` fallaba cada petición. Ahora usa `base_path()`.
- `GET .../{recurso}/policy` llamaba a `user()`, una función global que no
  existe: respondía 500 en los tres recursos.
- `index` de `action` y `login_attempt` y las tres exportaciones pasaban la
  petición a search-surge 3, que recibe un arreglo: TypeError.
- Las vistas de Excel de `audit` y `action` iteraban la variable de la otra y
  la exportación moría con "Undefined variable".
- El rollback de las tres migraciones ignoraba `db_prefix`.
- `log()` y `trackLoginAttempt()` fallaban con un cliente sin `User-Agent`
  (columna no nula).
- `ActionFactory` escribía una columna `template` que no existe.
- Los tests de endpoints llamaban a `/api/itecschool/auditpkg//...` y no
  corrían en la suite.
- README: el trait es `Innoboxrr\LaravelAudit\Support\Traits\Auditable`, el
  registro es manual con `$model->log('update')` y el de logins con
  `$user->trackLoginAttempt()`; ya no habla de registro automático ni de
  `$auditInclude`.

Rutas, nombres de ruta, tablas y claves de config existentes no cambian.

## [1.0.0] - 10/03/2023

### Added

### Changed

### Removed

### Fixed
