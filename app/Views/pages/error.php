<?php
/**
 * Página de error 500 autocontenida (se incluye desde index.php).
 */
if (!defined('MASS_APP')) {
    http_response_code(404);
    exit;
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error interno | Mass de La Joya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/global.css')) ?>">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="bg-white rounded-4 shadow-sm p-5 text-center">
            <h1 class="text-primary fw-bold">Ocurrió un error inesperado</h1>
            <p class="text-muted mb-4">Estamos trabajando para solucionarlo. Intenta de nuevo en unos minutos.</p>
            <a href="<?= e(url('')) ?>" class="btn-mass-pill btn-mass-yellow">Volver a la tienda</a>
        </div>
    </main>
</body>
</html>
