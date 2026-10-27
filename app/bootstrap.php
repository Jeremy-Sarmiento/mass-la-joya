<?php

declare(strict_types=1);

/**
 * Arranque de la aplicación: autoload, entorno, configuración y sesión.
 */

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/app/Support/helpers.php';
require BASE_PATH . '/app/Support/Env.php';

\App\Support\Env::load(BASE_PATH . '/.env');

/**
 * Autoload PSR-4 mínimo para el namespace App\.
 * Evita depender de Composer en servidores compartidos.
 */
spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relative) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

date_default_timezone_set((string) config('app.timezone', 'America/Lima'));

$debug = (bool) config('app.debug', false);
error_reporting(E_ALL);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('log_errors', '1');

if (!is_dir(BASE_PATH . '/storage')) {
    @mkdir(BASE_PATH . '/storage', 0775, true);
}

if (PHP_SAPI !== 'cli' && session_status() === PHP_SESSION_NONE) {
    $sessionConfig = (array) config('session', []);
    session_name((string) ($sessionConfig['name'] ?? 'mass_session'));
    session_set_cookie_params([
        'lifetime' => (int) ($sessionConfig['lifetime'] ?? 14400),
        'path' => base_path() === '' ? '/' : base_path() . '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    ]);
    session_start();
}
