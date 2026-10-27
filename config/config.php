<?php

declare(strict_types=1);

/**
 * Configuración central de la aplicación.
 *
 * Los valores sensibles se leen del archivo .env (nunca se versiona).
 * Ver .env.example para las variables disponibles.
 */
return [
    'app' => [
        'name' => 'Mass de La Joya',
        'env' => env('APP_ENV', 'production'),
        'debug' => filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN),
        'timezone' => 'America/Lima',
        'url' => rtrim((string) env('APP_URL', ''), '/'),
    ],

    'session' => [
        'name' => 'mass_session',
        'lifetime' => 60 * 60 * 4,
    ],

    'storage' => [
        'path' => (string) env('STORAGE_PATH', BASE_PATH . '/storage'),
    ],

    // ID del Meta Pixel. Se mantiene compatible con PUBLIC_META_PIXEL_ID.
    'meta_pixel_id' => (string) env('META_PIXEL_ID', env('PUBLIC_META_PIXEL_ID', '')),

    // Número de WhatsApp de la tienda (formato internacional, sin +).
    'whatsapp' => (string) env('STORE_WHATSAPP', '51997833866'),

    'store' => require BASE_PATH . '/app/Data/store.php',
];
