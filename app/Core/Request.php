<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Encapsula la petición HTTP actual.
 *
 * Combina $_GET, $_POST y cuerpos JSON para que los controladores
 * trabajen con una sola interfaz.
 */
final class Request
{
    private array $input;

    public function __construct()
    {
        $data = $_POST;

        $contentType = strtolower((string) ($_SERVER['CONTENT_TYPE'] ?? ''));
        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input');
            $decoded = json_decode($raw ?: '[]', true);
            if (is_array($decoded)) {
                $data = array_merge($data, $decoded);
            }
        }

        $this->input = $data;
    }

    public function method(): string
    {
        return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function all(): array
    {
        return $this->input;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->input[$key] ?? $default;
    }

    public function string(string $key, string $default = ''): string
    {
        $value = $this->input[$key] ?? $default;

        return is_scalar($value) ? trim((string) $value) : $default;
    }

    public function query(string $key, string $default = ''): string
    {
        $value = $_GET[$key] ?? $default;

        return is_scalar($value) ? trim((string) $value) : $default;
    }

    public function header(string $name, string $default = ''): string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));

        return (string) ($_SERVER[$key] ?? $default);
    }

    public function ip(): string
    {
        return (string) ($_SERVER['REMOTE_ADDR'] ?? '');
    }

    public function userAgent(): string
    {
        return substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);
    }

    /**
     * ¿El cliente espera una respuesta JSON? fetch() envía estos
     * encabezados; un formulario HTML normal recibe un redirect con flash.
     */
    public function wantsJson(): bool
    {
        if (str_contains(strtolower($this->header('Accept')), 'application/json')) {
            return true;
        }

        return strtolower($this->header('X-Requested-With')) === 'xmlhttprequest';
    }
}
