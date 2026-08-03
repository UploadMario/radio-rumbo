<?php

declare(strict_types=1);

namespace App\Core;

use InvalidArgumentException;
use RuntimeException;

final class View
{
    /**
     * @param array<string, mixed> $data
     */
    public static function render(string $view, array $data = []): void
    {
        if (!preg_match('/^[A-Za-z0-9_-]+(?:\.[A-Za-z0-9_-]+)*$/', $view)) {
            throw new InvalidArgumentException('El nombre de la vista no es valido.');
        }

        $basePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';
        $relativePath = str_replace('.', DIRECTORY_SEPARATOR, $view) . '.php';
        $viewPath = $basePath . DIRECTORY_SEPARATOR . $relativePath;

        $realBasePath = realpath($basePath);

        if ($realBasePath === false) {
            throw new RuntimeException('El directorio de vistas no esta disponible.');
        }

        $realViewPath = realpath($viewPath);

        if (
            $realViewPath === false
            || !is_file($realViewPath)
            || !str_starts_with($realViewPath, $realBasePath . DIRECTORY_SEPARATOR)
        ) {
            throw new InvalidArgumentException('La vista solicitada no existe.');
        }

        extract($data, EXTR_SKIP);
        require $realViewPath;
    }
}
