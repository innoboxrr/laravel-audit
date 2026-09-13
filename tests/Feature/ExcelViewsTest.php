<?php

namespace Innoboxrr\LaravelAudit\Tests\Feature;

use Innoboxrr\LaravelAudit\Models\Action;
use Innoboxrr\LaravelAudit\Models\Audit;
use Innoboxrr\LaravelAudit\Models\LoginAttempt;
use Innoboxrr\LaravelAudit\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Cada export pasa a su vista una variable con nombre propio (audits,
 * actions, login_attempts). Las vistas de audit y action la tenian cruzada y
 * la exportacion moria con "Undefined variable".
 */
final class ExcelViewsTest extends TestCase
{
    #[Test]
    public function la_vista_de_audits_usa_la_variable_que_pasa_el_export(): void
    {
        $html = view('innoboxrrlaravelaudit::excel.audit', [
            'audits' => collect([new Audit(['route' => 'https://app.test/ruta-auditada'])]),
        ])->render();

        $this->assertStringContainsString('https://app.test/ruta-auditada', $html);
    }

    #[Test]
    public function la_vista_de_actions_usa_la_variable_que_pasa_el_export(): void
    {
        $html = view('innoboxrrlaravelaudit::excel.action', [
            'actions' => collect([new Action(['description' => 'Accion exportada'])]),
        ])->render();

        $this->assertStringContainsString('Accion exportada', $html);
    }

    #[Test]
    public function la_vista_de_login_attempts_usa_la_variable_que_pasa_el_export(): void
    {
        $html = view('innoboxrrlaravelaudit::excel.login_attempt', [
            'login_attempts' => collect([new LoginAttempt(['email' => 'ana@example.com'])]),
        ])->render();

        $this->assertStringContainsString('ana@example.com', $html);
    }
}
