<?php

declare(strict_types=1);

namespace App\Core;

use InvalidArgumentException;

final class View
{
    /**
     * @param array<string, mixed> $data
     */
    public static function render(string $view, array $data = []): void
    {
        $basePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';
        $viewPath = $basePath . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $view) . '.php';

        if (!is_file($viewPath)) {
            throw new InvalidArgumentException(sprintf('La vista "%s" no existe.', $view));
        }

        extract($data, EXTR_SKIP);
        require $viewPath;
    }
}
