<?php

namespace Innoboxrr\LaravelAudit\Tests\Feature;

use Innoboxrr\LaravelAudit\Models\Action;
use Innoboxrr\LaravelAudit\Models\Audit;
use Innoboxrr\LaravelAudit\Models\LoginAttempt;
use Innoboxrr\LaravelAudit\Tests\FeatureTestCase;
use Innoboxrr\LaravelAudit\Tests\Fixtures\User;
use PHPUnit\Framework\Attributes\Test;

/**
 * Lo que documenta el README: el registro es manual, con $model->log() dentro
 * de una peticion autenticada, y $user->trackLoginAttempt() en el login.
 */
final class AuditableTest extends FeatureTestCase
{
    protected function defineRoutes($router): void
    {
        $router->middleware('api')->put('/pruebas/usuarios/{id}', function ($id) {
            $user = User::findOrFail($id);
            $user->fill(['name' => request('name')]);

            $audit = $user->log('update');
            $user->save();

            return response()->json(['audit_id' => $audit?->id]);
        });

        $router->middleware('api')->post('/pruebas/login', function () {
            $user = User::where('email', request('email'))->firstOrFail();

            // Un cliente sin User-Agent: la columna no admite null.
            request()->headers->remove('User-Agent');

            $user->trackLoginAttempt((bool) request('status'));

            return response()->json(['ok' => true]);
        });
    }

    #[Test]
    public function log_guarda_antes_y_despues_el_usuario_y_la_accion(): void
    {
        $this->simulateHttp();

        $editor = $this->makeUser(['is_admin' => true]);
        $target = $this->makeUser(['name' => 'Ana']);

        $this->signIn($editor, 'web');

        $response = $this->putJson("/pruebas/usuarios/{$target->id}", ['name' => 'Ana María'])->assertOk();

        $audit = Audit::findOrFail($response->json('audit_id'));

        $this->assertSame('Ana', json_decode($audit->before, true)['name']);
        $this->assertSame('Ana María', json_decode($audit->after, true)['name']);
        $this->assertSame($editor->id, (int) $audit->user_id);
        $this->assertSame($target->id, (int) $audit->loggable_id);
        $this->assertSame(User::class, $audit->loggable_type);
        $this->assertSame('127.0.0.1', $audit->ip_address);
        $this->assertStringEndsWith("/pruebas/usuarios/{$target->id}", $audit->route);

        $action = Action::findOrFail($audit->action_id);
        $this->assertSame('update', $action->type);
        $this->assertSame(1, $target->audits()->count());
    }

    #[Test]
    public function log_no_registra_nada_sin_usuario_autenticado(): void
    {
        $this->simulateHttp();

        $target = $this->makeUser(['name' => 'Ana']);

        $this->putJson("/pruebas/usuarios/{$target->id}", ['name' => 'Otra'])
            ->assertOk()
            ->assertJsonPath('audit_id', null);

        $this->assertSame(0, Audit::count());
    }

    #[Test]
    public function log_no_registra_nada_en_consola(): void
    {
        $target = $this->makeUser(['name' => 'Ana']);
        $this->signIn($this->makeUser(), 'web');

        $target->name = 'Otra';

        $this->assertNull($target->log('update'));
        $this->assertSame(0, Audit::count());
    }

    #[Test]
    public function track_login_attempt_registra_el_intento_aun_sin_user_agent(): void
    {
        $user = $this->makeUser(['email' => 'ana@example.com']);

        $this->postJson('/pruebas/login', ['email' => 'ana@example.com', 'status' => 0])->assertOk();

        $attempt = LoginAttempt::sole();

        $this->assertSame('ana@example.com', $attempt->email);
        $this->assertFalse($attempt->status);
        $this->assertSame('127.0.0.1', $attempt->ip_address);
        $this->assertSame('', $attempt->user_agent);
        $this->assertSame(1, $user->loginAttempts()->count());
    }

    /**
     * log() no registra en consola, y PHPUnit corre en consola. Se marca la
     * aplicacion como fuera de consola para probar el camino de una peticion.
     */
    private function simulateHttp(): void
    {
        (fn () => $this->isRunningInConsole = false)->call($this->app);
    }
}
