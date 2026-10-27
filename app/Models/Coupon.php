<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Cupones de descuento. El servidor recalcula el total del pedido
 * con estos porcentajes; el navegador solo los usa para mostrar la vista previa.
 */
final class Coupon
{
    public static function all(): array
    {
        $path = BASE_PATH . '/app/Data/coupons.json';
        $raw = file_get_contents($path);
        $coupons = $raw === false ? null : json_decode($raw, true);

        if (!is_array($coupons)) {
            return [];
        }

        return array_map(static function (array $coupon): array {
            $coupon['percent'] = (float) $coupon['percent'];

            return $coupon;
        }, $coupons);
    }

    public static function find(string $code): ?array
    {
        $code = strtoupper(trim($code));
        if ($code === '') {
            return null;
        }

        foreach (self::all() as $coupon) {
            if (strtoupper((string) $coupon['code']) === $code) {
                return $coupon;
            }
        }

        return null;
    }
}
