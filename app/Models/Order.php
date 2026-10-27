<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\ValidationException;
use App\Core\Validator;

/**
 * Pedidos generados desde el carrito.
 *
 * El total SIEMPRE se recalcula aquí con los precios del catálogo y las
 * zonas de entrega del servidor: nunca se confía en los importes que
 * envía el navegador.
 */
final class Order extends JsonRecord
{
    protected const FILE = 'orders';

    public static function place(array $input): array
    {
        $paymentMethods = (array) config('store.payment_methods', []);

        $validator = Validator::make($input, [
            'name' => 'required|string|min:2|max:120',
            'phone' => 'string|max:20|phone',
            'address' => 'string|max:200',
            'notes' => 'string|max:300',
            'zone' => 'required|string|in:' . implode(',', DeliveryZone::ids()),
            'pay_method' => 'required|string|in:' . implode(',', $paymentMethods),
            'coupon' => 'string|max:30',
            'order_id' => 'string|max:20',
        ]);

        $errors = $validator->errors();
        $items = self::normalizeItems($input['items'] ?? null, $errors);

        $coupon = null;
        $couponCode = strtoupper(trim((string) ($input['coupon'] ?? '')));
        if ($couponCode !== '') {
            $coupon = Coupon::find($couponCode);
            if ($coupon === null) {
                $errors['coupon'] = 'El cupón no existe o ya venció.';
            }
        }

        if ($errors !== []) {
            throw new ValidationException($errors);
        }

        $zone = DeliveryZone::find((string) $input['zone']) ?? DeliveryZone::default();

        $subtotal = 0.0;
        foreach ($items as &$item) {
            $item['line_total'] = round($item['price'] * $item['quantity'], 2);
            $subtotal += $item['line_total'];
        }
        unset($item);

        $subtotal = round($subtotal, 2);
        $delivery = round((float) $zone['cost'], 2);
        $percent = $coupon !== null ? (float) $coupon['percent'] : 0.0;
        $discount = round($subtotal * $percent / 100, 2);
        $total = round(max(0, $subtotal + $delivery - $discount), 2);

        $requestedId = trim((string) ($input['order_id'] ?? ''));
        $orderId = preg_match('/^MASS-LJ-\d{6}$/', $requestedId) === 1
            ? $requestedId
            : self::generateId();

        $record = [
            'id' => $orderId,
            'created_at' => date('c'),
            'created_at_local' => self::now(),
            'status' => 'pendiente',
            'customer' => [
                'name' => trim((string) $input['name']),
                'phone' => trim((string) ($input['phone'] ?? '')),
                'address' => trim((string) ($input['address'] ?? '')),
                'notes' => trim((string) ($input['notes'] ?? '')),
            ],
            'zone' => [
                'id' => $zone['id'],
                'name' => $zone['name'],
                'cost' => $delivery,
            ],
            'payment_method' => trim((string) $input['pay_method']),
            'coupon' => $coupon !== null
                ? ['code' => $coupon['code'], 'percent' => $percent]
                : null,
            'items' => array_values($items),
            'subtotal' => $subtotal,
            'delivery' => $delivery,
            'discount' => $discount,
            'total' => $total,
            'ip' => (string) ($_SERVER['REMOTE_ADDR'] ?? ''),
            'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
        ];

        return self::persist($record);
    }

    /**
     * @param mixed $rawItems
     * @param array<string, string> $errors
     * @return array<string, array<string, mixed>>
     */
    private static function normalizeItems(mixed $rawItems, array &$errors): array
    {
        if (!is_array($rawItems) || $rawItems === []) {
            $errors['items'] = 'El carrito está vacío. Agrega productos antes de pedir.';

            return [];
        }

        $lines = [];
        $totalQuantity = 0;

        foreach ($rawItems as $rawItem) {
            if (!is_array($rawItem)) {
                continue;
            }

            $id = trim((string) ($rawItem['id'] ?? ''));
            $quantity = filter_var($rawItem['quantity'] ?? null, FILTER_VALIDATE_INT);

            if ($quantity === false || $quantity < 1 || $quantity > 99) {
                $errors['items'] = 'Cada producto debe tener una cantidad entre 1 y 99.';
                continue;
            }

            $product = Product::find($id);
            if ($product === null) {
                $errors['items'] = 'Uno de los productos ya no está disponible en el catálogo.';
                continue;
            }

            if (empty($product['inStock'])) {
                $errors['items'] = 'El producto "' . $product['name'] . '" está agotado.';
                continue;
            }

            $totalQuantity += $quantity;
            if ($totalQuantity > 200) {
                $errors['items'] = 'El pedido supera el máximo de 200 unidades.';
                break;
            }

            if (isset($lines[$id])) {
                $lines[$id]['quantity'] += $quantity;
                continue;
            }

            $lines[$id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'unit' => $product['unit'],
                'price' => (float) $product['price'],
                'quantity' => $quantity,
                'line_total' => 0.0,
            ];
        }

        if ($lines === [] && !isset($errors['items'])) {
            $errors['items'] = 'El carrito está vacío. Agrega productos antes de pedir.';
        }

        if (count($lines) > 30) {
            $errors['items'] = 'El pedido admite como máximo 30 productos distintos.';
        }

        return $lines;
    }

    private static function generateId(): string
    {
        return 'MASS-LJ-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
