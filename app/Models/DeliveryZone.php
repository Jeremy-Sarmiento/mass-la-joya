<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Zonas de entrega y costo de delivery en La Joya.
 */
final class DeliveryZone
{
    public const DEFAULT = 'centro';

    public static function all(): array
    {
        $path = BASE_PATH . '/app/Data/delivery_zones.json';
        $raw = file_get_contents($path);
        $zones = $raw === false ? null : json_decode($raw, true);

        if (!is_array($zones)) {
            return [];
        }

        return array_map(static function (array $zone): array {
            $zone['cost'] = (float) $zone['cost'];

            return $zone;
        }, $zones);
    }

    public static function find(string $id): ?array
    {
        foreach (self::all() as $zone) {
            if ($zone['id'] === $id) {
                return $zone;
            }
        }

        return null;
    }

    public static function default(): array
    {
        return self::find(self::DEFAULT) ?? [
            'id' => 'centro',
            'name' => 'La Joya Centro y alrededores',
            'cost' => 3.00,
            'time' => '30-45 min',
        ];
    }

    /** @return array<int, string> */
    public static function ids(): array
    {
        return array_column(self::all(), 'id');
    }
}
