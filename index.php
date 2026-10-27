<?php

/**
 * Front controller de Mass de La Joya.
 *
 * Funciona en la raíz del dominio y en subdirectorios como
 * https://ejemplo.com/mass-la-joya/ sin configurar mod_rewrite.
 */

declare(strict_types=1);

define('MASS_APP', true);

require __DIR__ . '/app/bootstrap.php';

use App\Controllers\ApiController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Core\Router;

$router = new Router();

// Página principal (con filtros opcionales: categoria, q, orden, zona)
$router->get('/', [HomeController::class, 'index']);
$router->get('/home', [HomeController::class, 'index']);

// API de formularios y pedidos
$router->get('/api/health', [ApiController::class, 'health']);
$router->post('/api/orders', [ApiController::class, 'order']);
$router->post('/api/claims', [ApiController::class, 'claim']);
$router->post('/api/job-applications', [ApiController::class, 'jobApplication']);
$router->post('/api/local-offers', [ApiController::class, 'localOffer']);

// Cuenta de cliente
$router->post('/api/auth/login', [AuthController::class, 'login']);
$router->post('/api/auth/register', [AuthController::class, 'register']);
$router->post('/api/auth/logout', [AuthController::class, 'logout']);
$router->get('/api/auth/account', [AuthController::class, 'account']);

try {
    $router->dispatch();
} catch (Throwable $exception) {
    error_log('[Mass La Joya] ' . $exception->getMessage() . ' en ' . $exception->getFile() . ':' . $exception->getLine());

    http_response_code(500);
    $debug = (bool) config('app.debug', false);

    if ($debug) {
        header('Content-Type: text/plain; charset=utf-8');
        echo $exception->getMessage() . "\n\n" . $exception->getTraceAsString();
    } else {
        require __DIR__ . '/app/Views/pages/error.php';
    }
}
