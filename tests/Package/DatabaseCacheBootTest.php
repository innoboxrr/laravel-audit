<?php

namespace Innoboxrr\LaravelAudit\Tests\Package;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Innoboxrr\LaravelAudit\Models\Audit;
use Innoboxrr\LaravelAudit\Policies\AuditPolicy;
use Innoboxrr\LaravelAudit\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Una aplicacion nueva de Laravel 13 trae CACHE_STORE=database, y la tabla
 * cache no existe hasta que corre la primera migracion. Si un proveedor lee
 * la cache al arrancar, `php artisan migrate` falla antes de poder crearla.
 */
final class DatabaseCacheBootTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        $app['config']->set('cache.default', 'database');
    }

    #[Test]
    public function arranca_y_migra_con_la_cache_en_base_sin_tabla(): void
    {
        $this->assertFalse(Schema::hasTable('cache'));

        $this->assertInstanceOf(AuditPolicy::class, Gate::getPolicyFor(Audit::class));

        $this->artisan('migrate')->assertSuccessful();

        $this->assertTrue(Schema::hasTable('audits'));
    }
}
