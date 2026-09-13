<?php

namespace Innoboxrr\LaravelAudit\Tests\Package;

use Illuminate\Support\Facades\Schema;
use Innoboxrr\LaravelAudit\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Las migraciones crean las tablas con db_prefix; el rollback tiene que
 * borrar esas mismas tablas y no las de nombre sin prefijo.
 */
final class MigrationsPrefixTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        $app['config']->set('laravel-audit.db_prefix', 'aud_');
    }

    #[Test]
    public function el_rollback_respeta_db_prefix(): void
    {
        $tables = ['aud_audits', 'aud_actions', 'aud_login_attempts'];

        $this->artisan('migrate')->assertSuccessful();

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "{$table} no se creo.");
        }

        $this->artisan('migrate:rollback')->assertSuccessful();

        foreach ($tables as $table) {
            $this->assertFalse(Schema::hasTable($table), "{$table} sobrevivio al rollback.");
        }
    }
}
