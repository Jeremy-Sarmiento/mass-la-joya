<?php

declare(strict_types=1);

namespace App\Core;

use InvalidArgumentException;

/**
 * Vistas PHP planas con un layout compartido.
 *
 * Las vistas reciben variables mediante extract() y se capturan en un
 * buffer para inyectarlas como $content dentro del layout.
 */
final class View
{
    public static function render(string $view, array $data = [], string $layout = 'layouts/main'): void
    {
        $content = self::capture($view, $data);

        $layoutFile = BASE_PATH . '/app/Views/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            throw new InvalidArgumentException("Layout no encontrado: {$layout}");
        }

        $data['content'] = $content;
        extract($data, EXTR_SKIP);

        require $layoutFile;
    }

    public static function partial(string $view, array $data = []): void
    {
        echo self::capture($view, $data);
    }

    private static function capture(string $view, array $data): string
    {
        $file = BASE_PATH . '/app/Views/' . $view . '.php';
        if (!is_file($file)) {
            throw new InvalidArgumentException("Vista no encontrada: {$view}");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $file;

        return (string) ob_get_clean();
    }
}
