<?php

namespace Innoboxrr\LaravelAudit\Tests\Package;

use Innoboxrr\LaravelAudit\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Con `composer install --no-dev` no existe larapack-generator, y con el
 * tampoco sus helpers globales. La suite corre con las dependencias de
 * desarrollo instaladas, asi que el fallo no aparece al ejecutar: se busca en
 * el codigo que carga el paquete en produccion.
 */
final class RuntimeWithoutDevDependenciesTest extends TestCase
{
    /** Helpers globales que solo definen dependencias de desarrollo. */
    private const DEV_ONLY_FUNCTIONS = ['root_path', 'user'];

    #[Test]
    public function la_config_usa_base_path(): void
    {
        $this->assertStringStartsWith(base_path(), config('laravel-audit.builder.basePath'));
    }

    #[Test]
    public function el_codigo_de_produccion_no_llama_helpers_de_desarrollo(): void
    {
        $root = dirname(__DIR__, 2);
        $failures = [];

        foreach (['config', 'src', 'database', 'routes'] as $directory) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator("{$root}/{$directory}", \FilesystemIterator::SKIP_DOTS));

            foreach ($files as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                foreach (self::globalCalls((string) file_get_contents($file->getPathname())) as $function) {
                    if (in_array($function, self::DEV_ONLY_FUNCTIONS, true)) {
                        $failures[] = "{$directory}/" . substr(str_replace('\\', '/', $file->getPathname()), strlen(str_replace('\\', '/', "{$root}/{$directory}")) + 1) . " llama a {$function}().";
                    }
                }
            }
        }

        $this->assertSame([], $failures);
    }

    /**
     * Llamadas a funciones sueltas: un nombre seguido de "(" que no viene
     * despues de ->, ::, new ni function.
     *
     * @return array<int, string>
     */
    private static function globalCalls(string $code): array
    {
        $tokens = array_values(array_filter(
            token_get_all($code),
            fn ($token) => ! is_array($token) || ! in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)
        ));

        $calls = [];

        foreach ($tokens as $i => $token) {
            if (! is_array($token) || $token[0] !== T_STRING || ($tokens[$i + 1] ?? null) !== '(') {
                continue;
            }

            $previous = $tokens[$i - 1] ?? null;

            if (is_array($previous) && in_array($previous[0], [T_OBJECT_OPERATOR, T_NULLSAFE_OBJECT_OPERATOR, T_DOUBLE_COLON, T_NEW, T_FUNCTION], true)) {
                continue;
            }

            $calls[] = $token[1];
        }

        return $calls;
    }
}
