<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Categorías / departamentos de la tienda.
 */
final class Category
{
    public static function all(): array
    {
        $path = BASE_PATH . '/app/Data/categories.json';
        $raw = file_get_contents($path);
        $categories = $raw === false ? null : json_decode($raw, true);

        return is_array($categories) ? $categories : [];
    }

    /**
     * Categorías con el contador calculado en el servidor, para no
     * mantener números a mano en el JSON.
     */
    public static function withCounts(): array
    {
        $counts = Product::categoryCounts();
        $total = count(Product::all());
        $categories = self::all();

        foreach ($categories as &$category) {
            $category['count'] = $category['id'] === 'todos'
                ? $total
                : ($counts[$category['id']] ?? 0);
        }
        unset($category);

        return $categories;
    }

    public static function exists(string $id): bool
    {
        foreach (self::all() as $category) {
            if ($category['id'] === $id) {
                return true;
            }
        }

        return false;
    }
}
