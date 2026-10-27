<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Router mínimo basado en index.php?r=ruta.
 *
 * También acepta PATH_INFO y rutas limpias si el servidor tiene
 * mod_rewrite configurado (ver .htaccess).
 */
final class Router
{
    /** @var array<int, array{method: string, path: string, handler: callable|array}> */
    private array $routes = [];

    public function get(string $path, callable|array $handler): self
    {
        return $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): self
    {
        return $this->add('POST', $path, $handler);
    }

    public function add(string $method, string $path, callable|array $handler): self
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $this->normalize($path),
            'handler' => $handler,
        ];

        return $this;
    }

    public function dispatch(): void
    {
        $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        if ($method === 'HEAD') {
            $method = 'GET';
        }

        $path = $this->currentPath();

        $allowedMethods = [];

        foreach ($this->routes as $route) {
            if ($route['path'] !== $path) {
                continue;
            }

            $allowedMethods[] = $route['method'];

            if ($route['method'] === $method) {
                $this->call($route['handler']);

                return;
            }
        }

        if ($allowedMethods !== []) {
            http_response_code(405);
            header('Allow: ' . implode(', ', array_unique($allowedMethods)));

            if ((new Request())->wantsJson()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'ok' => false,
                    'message' => 'Método no permitido para esta ruta.',
                ], JSON_UNESCAPED_UNICODE);
            } else {
                View::render('pages/not-found', [
                    'title' => 'Método no permitido | Mass de La Joya',
                    'heading' => '405 · Método no permitido',
                    'message' => 'Esta ruta no acepta el método HTTP utilizado.',
                ]);
            }

            return;
        }

        http_response_code(404);
        View::render('pages/not-found', [
            'title' => 'Página no encontrada | Mass de La Joya',
            'heading' => '404 · Página no encontrada',
            'message' => 'La dirección que buscas no existe o fue movida.',
        ]);
    }

    private function currentPath(): string
    {
        $path = '';

        if (isset($_GET['r']) && is_string($_GET['r'])) {
            $path = $_GET['r'];
        } elseif (!empty($_SERVER['PATH_INFO'])) {
            $path = (string) $_SERVER['PATH_INFO'];
        } elseif (!empty($_SERVER['REQUEST_URI'])) {
            $uri = parse_url((string) $_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
            $base = base_path();
            if ($base !== '' && str_starts_with($uri, $base)) {
                $uri = substr($uri, strlen($base));
            }
            $uri = preg_replace('#^/index\.php#', '', $uri) ?? $uri;
            $path = $uri;
        }

        $path = $this->normalize($path);

        return $path === '' ? '/' : $path;
    }

    private function normalize(string $path): string
    {
        $path = trim($path);
        $path = (string) (parse_url($path, PHP_URL_PATH) ?? '');
        $path = '/' . trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }

    private function call(callable|array $handler): void
    {
        if (is_array($handler) && isset($handler[0], $handler[1]) && is_string($handler[0])) {
            $controller = new $handler[0]();
            $method = (string) $handler[1];
            $controller->{$method}();

            return;
        }

        if (is_callable($handler)) {
            $handler();

            return;
        }

        throw new \RuntimeException('Manejador de ruta inválido.');
    }
}
