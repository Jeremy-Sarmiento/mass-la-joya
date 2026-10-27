<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Catálogo de productos (58 referencias de Mass de La Joya).
 */
final class Product
{
    /** @var array<int, array<string, mixed>>|null */
    private static ?array $items = null;

    /** @return array<int, array<string, mixed>> */
    public static function all(): array
    {
        if (self::$items === null) {
            $raw = self::readJson('products.json');
            self::$items = array_map(static function (array $product): array {
                $product['price'] = (float) $product['price'];
                $product['oldPrice'] = isset($product['oldPrice']) ? (float) $product['oldPrice'] : null;

                return $product;
            }, $raw);
        }

        return self::$items;
    }

    /** @return array<string, mixed>|null */
    public static function find(string $id): ?array
    {
        foreach (self::all() as $product) {
            if ($product['id'] === $id) {
                return $product;
            }
        }

        return null;
    }

    /** @return array<int, array<string, mixed>> */
    public static function featured(int $limit = 8): array
    {
        $featured = array_values(array_filter(
            self::all(),
            static fn (array $product): bool => !empty($product['isFeatured'])
        ));

        return array_slice($featured, 0, $limit);
    }

    /** @return array<int, array<string, mixed>> */
    public static function filter(string $category = 'todos', string $search = '', string $sort = 'default'): array
    {
        $products = self::all();

        if ($category !== '' && $category !== 'todos') {
            $products = array_values(array_filter(
                $products,
                static fn (array $product): bool => $product['category'] === $category
            ));
        }

        $search = mb_strtolower(trim($search));
        if ($search !== '') {
            $products = array_values(array_filter(
                $products,
                static function (array $product) use ($search): bool {
                    $haystack = mb_strtolower(implode(' ', [
                        $product['name'],
                        $product['brand'],
                        $product['description'],
                        $product['category'],
                    ]));

                    return str_contains($haystack, $search);
                }
            ));
        }

        $products = match ($sort) {
            'price-asc' => self::sortBy($products, 'price', SORT_ASC),
            'price-desc' => self::sortBy($products, 'price', SORT_DESC),
            'name' => self::sortByName($products),
            'discount' => self::sortByDiscount($products),
            default => $products,
        };

        return $products;
    }

    public static function categoryCounts(): array
    {
        $counts = [];
        foreach (self::all() as $product) {
            $counts[$product['category']] = ($counts[$product['category']] ?? 0) + 1;
        }

        return $counts;
    }

    /** @return array<int, string> */
    public static function ids(): array
    {
        return array_column(self::all(), 'id');
    }

    /** @return array<int, array<string, mixed>> */
    private static function sortBy(array $products, string $key, int $direction): array
    {
        usort($products, static fn (array $a, array $b): int => ($a[$key] <=> $b[$key]) * $direction);

        return $products;
    }

    /** @return array<int, array<string, mixed>> */
    private static function sortByName(array $products): array
    {
        usort($products, static fn (array $a, array $b): int => strcasecmp($a['name'], $b['name']));

        return $products;
    }

    /** @return array<int, array<string, mixed>> */
    private static function sortByDiscount(array $products): array
    {
        usort($products, static function (array $a, array $b): int {
            $discountA = $a['oldPrice'] ? ($a['oldPrice'] - $a['price']) : 0.0;
            $discountB = $b['oldPrice'] ? ($b['oldPrice'] - $b['price']) : 0.0;

            return $discountB <=> $discountA;
        });

        return $products;
    }

    /** @return array<int, array<string, mixed>> */
    private static function readJson(string $file): array
    {
        $path = BASE_PATH . '/app/Data/' . $file;
        $raw = file_get_contents($path);
        $data = $raw === false ? null : json_decode($raw, true);

        return is_array($data) ? $data : [];
    }
}
