<?php

declare(strict_types=1);

/**
 * Funciones globales de apoyo a las vistas y controladores.
 */

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }

        return match (strtolower($value)) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'null', '(null)' => null,
            'empty', '(empty)' => '',
            default => $value,
        };
    }
}

if (!function_exists('config')) {
    function config(?string $key = null, mixed $default = null): mixed
    {
        static $config = null;

        if ($config === null) {
            $config = require BASE_PATH . '/config/config.php';
        }

        if ($key === null || $key === '') {
            return $config;
        }

        $value = $config;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}

if (!function_exists('base_path')) {
    /**
     * Ruta pública donde vive index.php, sin barra final.
     * Funciona tanto en la raíz del dominio como en /mass-la-joya/.
     */
    function base_path(): string
    {
        static $base = null;

        if ($base !== null) {
            return $base;
        }

        $script = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $dir = str_replace('\\', '/', dirname($script));
        $base = ($dir === '/' || $dir === '.' || $dir === '') ? '' : rtrim($dir, '/');

        return $base;
    }
}

if (!function_exists('url')) {
    /**
     * URL interna. Usa index.php?r=... para no depender de mod_rewrite.
     */
    function url(string $route = '', array $query = []): string
    {
        $route = trim($route, '/');
        if ($route === '') {
            return base_path() . '/';
        }

        $query = ['r' => $route] + $query;

        return base_path() . '/index.php?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
    }
}

if (!function_exists('absolute_url')) {
    function absolute_url(string $path = ''): string
    {
        $configured = (string) config('app.url', '');

        if ($configured !== '') {
            return rtrim($configured, '/') . '/' . ltrim($path, '/');
        }

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return $scheme . '://' . $host . base_path() . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    /** Ruta relativa: funciona en la raíz y en subdirectorios. */
    function asset(string $path): string
    {
        return 'assets/' . ltrim($path, '/');
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('money')) {
    function money(float|int|string $amount): string
    {
        return 'S/ ' . number_format((float) $amount, 2, '.', ',');
    }
}

if (!function_exists('json_for_js')) {
    function json_for_js(mixed $data): string
    {
        return (string) json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES
            | JSON_HEX_TAG
            | JSON_HEX_AMP
            | JSON_HEX_APOS
            | JSON_HEX_QUOT
        );
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf(): bool
    {
        $expected = $_SESSION['_csrf'] ?? '';
        if ($expected === '') {
            return false;
        }

        $provided = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['_token'] ?? '');
        if (!is_string($provided) || $provided === '') {
            // Algunos clientes envían el token dentro del JSON.
            $raw = file_get_contents('php://input');
            $decoded = json_decode($raw ?: '[]', true);
            $provided = is_array($decoded) ? ($decoded['_token'] ?? '') : '';
        }

        return is_string($provided) && hash_equals($expected, $provided);
    }
}

if (!function_exists('flash')) {
    function flash(string $type, string $message): void
    {
        $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
    }
}

if (!function_exists('flashes')) {
    /** Devuelve y limpia los mensajes flash de la sesión. */
    function flashes(): array
    {
        $messages = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);

        return is_array($messages) ? $messages : [];
    }
}

if (!function_exists('partial')) {
    function partial(string $view, array $data = []): void
    {
        \App\Core\View::partial($view, $data);
    }
}
