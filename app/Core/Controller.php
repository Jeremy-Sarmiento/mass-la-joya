<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Controlador base: vistas, JSON, redirecciones y CSRF.
 */
abstract class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'layouts/main'): void
    {
        View::render($view, $data, $layout);
    }

    protected function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function redirect(string $route = '', array $query = []): never
    {
        header('Location: ' . url($route, $query), true, 303);
        exit;
    }

    protected function redirectTo(string $location): never
    {
        header('Location: ' . $location, true, 303);
        exit;
    }

    protected function requirePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->json(['ok' => false, 'message' => 'Método no permitido.'], 405);
        }
    }

    protected function requireCsrf(): void
    {
        if (!verify_csrf()) {
            $this->respond([
                'ok' => false,
                'message' => 'La sesión expiró o el token de seguridad no es válido. Recarga la página e inténtalo de nuevo.',
            ], 419);
        }
    }

    /**
     * Respuesta uniforme para los endpoints de formularios.
     * Con JavaScript responde JSON; sin JavaScript redirige con flash.
     */
    protected function respond(array $payload, int $status = 200, string $anchor = ''): never
    {
        $request = new Request();

        if ($request->wantsJson()) {
            $this->json($payload, $status);
        }

        flash($status < 400 ? 'success' : 'error', (string) ($payload['message'] ?? ''));
        $this->redirectTo(url('') . $anchor);
    }
}
