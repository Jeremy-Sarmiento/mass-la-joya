<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Storage;

/**
 * Base para modelos que guardan registros en storage/<archivo>.php.
 */
abstract class JsonRecord
{
    protected const FILE = '';

    /** @return array<int, array<string, mixed>> */
    public static function all(): array
    {
        return Storage::all(static::FILE);
    }

    public static function count(): int
    {
        return Storage::count(static::FILE);
    }

    /** @param array<string, mixed> $record */
    protected static function persist(array $record): array
    {
        Storage::append(static::FILE, $record);

        return $record;
    }

    protected static function now(): string
    {
        return date('Y-m-d H:i:s');
    }
}
