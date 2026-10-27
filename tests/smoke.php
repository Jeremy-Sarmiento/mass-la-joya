<?php

declare(strict_types=1);

/**
 * Pruebas de humo sin dependencias externas.
 *
 * Uso: php tests/smoke.php
 */

define('MASS_APP', true);

$tempStorage = sys_get_temp_dir() . '/mass-smoke-' . bin2hex(random_bytes(4));
putenv('STORAGE_PATH=' . $tempStorage);

require __DIR__ . '/../app/bootstrap.php';

use App\Core\ValidationException;
use App\Models\Category;
use App\Models\Claim;
use App\Models\Coupon;
use App\Models\DeliveryZone;
use App\Models\JobApplication;
use App\Models\LocalOffer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\User;

$failures = [];

function check(string $label, bool $condition): void
{
    global $failures;

    if ($condition) {
        echo "  ✓ {$label}\n";
        return;
    }

    $failures[] = $label;
    echo "  ✗ {$label}\n";
}

echo "Catálogo\n";
check('58 productos cargados', count(Product::all()) === 58);
check('8 productos destacados', count(Product::featured()) === 8);
check('6 productos lácteos', count(Product::filter('lacteos')) === 6);
check('búsqueda "arroz" devuelve resultados', count(Product::filter('todos', 'arroz')) >= 2);
check('orden por precio ascendente', Product::filter('todos', '', 'price-asc')[0]['price'] <= Product::filter('todos', '', 'price-asc')[1]['price']);
$categories = Category::withCounts();
$categorySum = 0;
foreach ($categories as $category) {
    if ($category['id'] !== 'todos') {
        $categorySum += (int) $category['count'];
    }
}
check('11 categorías', count($categories) === 11);
check('los contadores suman 58', $categorySum === 58);
check('5 zonas de entrega', count(DeliveryZone::all()) === 5);
check('3 cupones', count(Coupon::all()) === 3);
check('3 testimonios', count(Testimonial::all()) === 3);

echo "Cuentas\n";
$customer = User::register([
    'name' => 'Carmen Rojas',
    'email' => 'Carmen.Rojas@Example.com',
    'phone' => '987654321',
    'password' => 'Mass2026segura',
    'password_confirmation' => 'Mass2026segura',
    'terms' => '1',
]);
check('cuenta de cliente creada', ($customer['email'] ?? '') === 'carmen.rojas@example.com');
check('el hash de la contraseña no se expone', !isset($customer['password_hash']));
$storedUser = User::findByEmail('carmen.rojas@example.com');
check('contraseña guardada con hash', isset($storedUser['password_hash']) && password_verify('Mass2026segura', (string) $storedUser['password_hash']));
check('inicio de sesión con contraseña correcta', User::attempt('CARMEN.ROJAS@example.com', 'Mass2026segura') !== null);
check('rechaza contraseña incorrecta', User::attempt('carmen.rojas@example.com', 'incorrecta') === null);

$threw = false;
try {
    User::register([
        'name' => 'Carmen Rojas',
        'email' => 'carmen.rojas@example.com',
        'password' => 'OtraClave2026',
        'password_confirmation' => 'OtraClave2026',
        'terms' => '1',
    ]);
} catch (ValidationException) {
    $threw = true;
}
check('rechaza correos duplicados', $threw);

$threw = false;
try {
    User::register([
        'name' => 'Clave Corta',
        'email' => 'corta@example.com',
        'password' => '123',
        'password_confirmation' => '123',
        'terms' => '1',
    ]);
} catch (ValidationException) {
    $threw = true;
}
check('rechaza contraseñas débiles', $threw);

echo "Pedidos\n";
$order = Order::place([
    'order_id' => 'MASS-LJ-123456',
    'name' => 'Carmen Rojas',
    'phone' => '987654321',
    'address' => 'Av. Paz Soldán 123',
    'notes' => 'Timbre blanco',
    'zone' => 'centro',
    'pay_method' => 'Yape / Plin',
    'coupon' => 'MASSJOYERO',
    'user_id' => $customer['id'],
    'items' => [
        ['id' => 'prod-001', 'quantity' => 2],
        ['id' => 'prod-019', 'quantity' => 1],
    ],
]);

check('ID de pedido respetado', $order['id'] === 'MASS-LJ-123456');
check('subtotal recalculado con precios del servidor', $order['subtotal'] === 18.1);
check('delivery de La Joya Centro', $order['delivery'] === 3.0);
check('descuento del 10%', $order['discount'] === 1.81);
check('total final', $order['total'] === 19.29);
check('pedido persistido', count(Order::all()) === 1);
check('pedido asociado a la cuenta', ($order['user_id'] ?? null) === $customer['id']);
check('pedidos de la cuenta recuperados', count(Order::forUser($customer['id'])) === 1);
check('otra cuenta no ve esos pedidos', Order::forUser('USR-OTRA0000') === []);

$threw = false;
try {
    Order::place([
        'name' => 'Sin carrito',
        'zone' => 'centro',
        'pay_method' => 'Yape / Plin',
        'items' => [],
    ]);
} catch (ValidationException) {
    $threw = true;
}
check('rechaza pedidos sin productos', $threw);

$threw = false;
try {
    Order::place([
        'name' => 'Cupón inválido',
        'zone' => 'centro',
        'pay_method' => 'Yape / Plin',
        'coupon' => 'NOEXISTE',
        'items' => [['id' => 'prod-001', 'quantity' => 1]],
    ]);
} catch (ValidationException) {
    $threw = true;
}
check('rechaza cupones inexistentes', $threw);

echo "Formularios\n";
$claim = Claim::register([
    'name' => 'Ana Torres',
    'dni' => '12345678',
    'phone' => '987654321',
    'email' => 'ana@example.com',
    'address' => 'Av. Paz Soldán 456',
    'type' => 'reclamo',
    'detail' => 'El producto llegó en mal estado y solicito el cambio.',
]);
check('código de reclamación generado', preg_match('/^REC-LJ-\d{6}$/', $claim['code']) === 1);

JobApplication::register([
    'name' => 'Luis Quispe',
    'dni' => '87654321',
    'age' => '25',
    'phone' => '912345678',
    'zone' => 'El Triunfo',
    'position' => 'Atención al Cliente y Caja',
]);
check('postulación registrada', count(JobApplication::all()) === 1);

LocalOffer::register([
    'name' => 'Juan Pérez',
    'phone' => '998877665',
    'area' => '180',
    'address' => 'Av. Panamericana Sur Km 980',
]);
check('oferta de local registrada', count(LocalOffer::all()) === 1);

$storageFile = $tempStorage . '/orders.php';
$raw = (string) file_get_contents($storageFile);
check('los datos están protegidos por una guarda PHP', str_starts_with($raw, '<?php http_response_code(403); exit; ?>'));

// Limpieza de los archivos temporales de la prueba.
foreach (glob($tempStorage . '/*') ?: [] as $file) {
    @unlink($file);
}
@rmdir($tempStorage);

if ($failures !== []) {
    fwrite(STDERR, "\nFallaron " . count($failures) . " comprobaciones.\n");
    exit(1);
}

echo "\nTodo correcto: la aplicación responde como se espera.\n";
