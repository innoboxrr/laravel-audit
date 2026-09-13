<?php

namespace Innoboxrr\LaravelAudit\Tests\Feature\Models;

use Innoboxrr\LaravelAudit\Models\Action;
use Innoboxrr\LaravelAudit\Models\Audit;
use Innoboxrr\LaravelAudit\Models\LoginAttempt;
use Innoboxrr\LaravelAudit\Tests\FeatureTestCase;
use Innoboxrr\LaravelAudit\Tests\Fixtures\PlainUser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * Las rutas reales que consume el admin: /api/innoboxrr/laravel-audit/{recurso}/*
 * con nombres api.innoboxrr.laravel.audit.{recurso}.*.
 */
final class ResourceEndpointsTest extends FeatureTestCase
{
    /**
     * @return array<string, array{0: string, 1: class-string, 2: string}>
     */
    public static function resources(): array
    {
        return [
            'audit' => ['audit', Audit::class, 'audit_id'],
            'action' => ['action', Action::class, 'action_id'],
            'login_attempt' => ['login_attempt', LoginAttempt::class, 'login_attempt_id'],
        ];
    }

    #[Test]
    #[DataProvider('resources')]
    public function las_rutas_tienen_el_prefijo_y_el_nombre_del_contrato(string $resource, string $model, string $key): void
    {
        foreach (['policies', 'policy', 'index', 'show', 'export'] as $endpoint) {
            $this->assertSame(
                url("/api/innoboxrr/laravel-audit/{$resource}/{$endpoint}"),
                route("api.innoboxrr.laravel.audit.{$resource}.{$endpoint}")
            );
        }
    }

    #[Test]
    #[DataProvider('resources')]
    public function un_invitado_recibe_401(string $resource, string $model, string $key): void
    {
        $record = $model::factory()->create();

        $this->getJson($this->url($resource, 'index'))->assertUnauthorized();
        $this->getJson($this->url($resource, 'show', [$key => $record->id]))->assertUnauthorized();
        $this->getJson($this->url($resource, 'policy', ['policy' => 'index']))->assertUnauthorized();
        $this->postJson($this->url($resource, 'export'))->assertUnauthorized();
    }

    #[Test]
    #[DataProvider('resources')]
    public function un_usuario_que_no_es_admin_no_puede_listar_ni_ver(string $resource, string $model, string $key): void
    {
        $record = $model::factory()->create();

        $this->signIn($this->makeUser());

        $this->getJson($this->url($resource, 'index'))->assertForbidden();
        $this->getJson($this->url($resource, 'show', [$key => $record->id]))->assertForbidden();
        $this->postJson($this->url($resource, 'export'))->assertForbidden();
    }

    #[Test]
    #[DataProvider('resources')]
    public function un_usuario_sin_is_admin_recibe_403_y_no_un_error(string $resource, string $model, string $key): void
    {
        $record = $model::factory()->create();

        $this->signIn(PlainUser::findOrFail($this->makeUser()->id));

        $this->getJson($this->url($resource, 'index'))->assertForbidden();
        $this->getJson($this->url($resource, 'show', [$key => $record->id]))->assertForbidden();
        $this->getJson($this->url($resource, 'policy', ['policy' => 'index']))
            ->assertOk()
            ->assertExactJson(['index' => false]);
    }

    #[Test]
    #[DataProvider('resources')]
    public function un_admin_lista_y_ve(string $resource, string $model, string $key): void
    {
        $records = $model::factory()->count(3)->create();

        $this->signIn($this->makeUser(['is_admin' => true]));

        $this->getJson($this->url($resource, 'index'))
            ->assertOk()
            ->assertJsonCount(3, 'data');

        $this->getJson($this->url($resource, 'show', [$key => $records[1]->id]))
            ->assertOk()
            ->assertJsonPath('data.id', $records[1]->id);

        $this->getJson($this->url($resource, 'policy', ['policy' => 'index']))
            ->assertOk()
            ->assertExactJson(['index' => true]);
    }

    #[Test]
    #[DataProvider('resources')]
    public function paginate_0_devuelve_todo(string $resource, string $model, string $key): void
    {
        $model::factory()->count(12)->create();

        $this->signIn($this->makeUser(['is_admin' => true]));

        $this->getJson($this->url($resource, 'index'))
            ->assertOk()
            ->assertJsonCount(10, 'data');

        $this->getJson($this->url($resource, 'index', ['paginate' => 0]))
            ->assertOk()
            ->assertJsonCount(12, 'data');
    }

    /**
     * maatwebsite/excel es sugerido y no esta instalado aqui, como en una
     * aplicacion nueva: la exportacion tiene que decirlo, no responder 500.
     */
    #[Test]
    #[DataProvider('resources')]
    public function exportar_sin_excel_responde_un_error_claro(string $resource, string $model, string $key): void
    {
        $this->assertFalse(class_exists(\Maatwebsite\Excel\Facades\Excel::class));

        $this->signIn($this->makeUser(['is_admin' => true]));

        $this->postJson($this->url($resource, 'export'))
            ->assertStatus(501)
            ->assertJsonPath('status', false)
            ->assertJsonPath('message', fn (string $message) => str_contains($message, 'maatwebsite/excel'));
    }

    private function url(string $resource, string $endpoint, array $query = []): string
    {
        return route("api.innoboxrr.laravel.audit.{$resource}.{$endpoint}", $query);
    }
}
