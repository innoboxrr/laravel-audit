<?php

namespace Innoboxrr\LaravelAudit\Tests\Package;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as AppEventServiceProvider;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as AppRouteServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Innoboxrr\LaravelAudit\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Lo que el paquete no debe hacerle a la aplicacion que lo instala.
 *
 * Se arma como bootstrap/app.php en Laravel 13: withRouting() deja el cargador
 * de rutas de la aplicacion en la clase base de Foundation y registra ese
 * proveedor, y withEvents(), que Application::configure() llama siempre,
 * registra el EventServiceProvider base. Asi se cuenta lo que el paquete agrega
 * encima de lo que la aplicacion ya tiene.
 */
final class HostApplicationIsolationTest extends TestCase
{
    public static int $appRouteLoads = 0;

    protected function defineEnvironment($app): void
    {
        self::$appRouteLoads = 0;

        AppRouteServiceProvider::loadRoutesUsing(function () {
            self::$appRouteLoads++;
        });

        $app->booting(function ($app) {
            $app->register(AppRouteServiceProvider::class, force: true);
            $app->register(AppEventServiceProvider::class);
        });
    }

    protected function tearDown(): void
    {
        AppRouteServiceProvider::loadRoutesUsing(null);

        parent::tearDown();
    }

    #[Test]
    public function la_aplicacion_carga_sus_rutas_una_sola_vez(): void
    {
        $this->assertSame(1, self::$appRouteLoads);
    }

    #[Test]
    public function el_correo_de_verificacion_se_registra_una_sola_vez(): void
    {
        $this->assertSame(
            [SendEmailVerificationNotification::class],
            Event::getRawListeners()[Registered::class] ?? []
        );
    }

    #[Test]
    public function las_rutas_del_paquete_conservan_nombre_uri_metodo_y_middleware(): void
    {
        $resources = [
            'action' => 'ActionController',
            'audit' => 'AuditController',
            'login_attempt' => 'LoginAttemptController',
        ];

        // nombre => [metodo, segmento de la URI, metodo del controlador]
        $endpoints = [
            'policies' => ['GET', 'policies', 'policies'],
            'policy' => ['GET', 'policy', 'policy'],
            'index' => ['GET', 'index', 'index'],
            'show' => ['GET', 'show', 'show'],
            'create' => ['POST', 'create', 'create'],
            'update' => ['PUT', 'update', 'update'],
            'delete' => ['DELETE', 'delete', 'delete'],
            'restore' => ['POST', 'restore', 'restore'],
            'force.delete' => ['DELETE', 'force-delete', 'forceDelete'],
            'export' => ['POST', 'export', 'export'],
        ];

        foreach ($resources as $resource => $controller) {
            foreach ($endpoints as $endpoint => [$method, $segment, $action]) {
                $name = "api.innoboxrr.laravel.audit.{$resource}.{$endpoint}";
                $route = Route::getRoutes()->getByName($name);

                $this->assertNotNull($route, "Falta la ruta {$name}.");
                $this->assertSame("api/innoboxrr/laravel-audit/{$resource}/{$segment}", $route->uri(), $name);
                $this->assertContains($method, $route->methods(), $name);
                $this->assertSame(['api'], $route->middleware(), $name);
                $this->assertSame("Innoboxrr\\LaravelAudit\\Http\\Controllers\\{$controller}@{$action}", $route->getAction('uses'), $name);
            }
        }

        $registered = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($route) => Str::startsWith((string) $route->getName(), 'api.innoboxrr.laravel.audit.'));

        $this->assertCount(count($resources) * count($endpoints), $registered);
    }
}
