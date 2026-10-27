<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Persistencia en archivos JSON con bloqueo de escritura.
 *
 * Cada archivo empieza con una guarda PHP: si alguien solicita
 * storage/orders.php directamente, el servidor responde 403 y no
 * expone los datos. No requiere base de datos para funcionar.
 */
final class Storage
{
    private const GUARD = "<?php http_response_code(403); exit; ?>\n";

    /** @return array<int, array<string, mixed>> */
    public static function all(string $name): array
    {
        $path = self::path($name);
        if (!is_file($path)) {
            return [];
        }

        $raw = file_get_contents($path);
        if ($raw === false) {
            return [];
        }

        if (str_starts_with($raw, self::GUARD)) {
            $raw = substr($raw, strlen(self::GUARD));
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    /** @param array<string, mixed> $record */
    public static function append(string $name, array $record): void
    {
        $path = self::path($name);
        $handle = fopen($path, 'c+');
        if ($handle === false) {
            throw new \RuntimeException('No se pudo abrir el almacenamiento de datos.');
        }

        try {
            if (!flock($handle, LOCK_EX)) {
                throw new \RuntimeException('No se pudo bloquear el almacenamiento de datos.');
            }

            $raw = stream_get_contents($handle);
            if ($raw === false || $raw === '') {
                $records = [];
            } else {
                if (str_starts_with($raw, self::GUARD)) {
                    $raw = substr($raw, strlen(self::GUARD));
                }
                $decoded = json_decode($raw, true);
                $records = is_array($decoded) ? $decoded : [];
            }

            $records[] = $record;

            $json = self::GUARD . json_encode(
                $records,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ) . "\n";

            rewind($handle);
            ftruncate($handle, 0);
            fwrite($handle, $json);
            fflush($handle);
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    public static function count(string $name): int
    {
        return count(self::all($name));
    }

    private static function path(string $name): string
    {
        $directory = (string) config('storage.path', BASE_PATH . '/storage');
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        return rtrim($directory, '/') . '/' . basename($name) . '.php';
    }
}
