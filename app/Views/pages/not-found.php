<?php
/**
 * Contenido de la página 404 (se renderiza dentro de layouts/main.php).
 */
if (!defined('MASS_APP')) {
    http_response_code(404);
    exit;
}
?>
<main class="container py-5">
    <div class="text-center py-5">
        <i class="fa-solid fa-face-frown text-warning fa-4x mb-3"></i>
        <h1 class="text-primary fw-bold"><?= e($heading ?? '404 · Página no encontrada') ?></h1>
        <p class="text-muted"><?= e($message ?? 'La dirección que buscas no existe o fue movida.') ?></p>
        <a href="<?= e(url('')) ?>" class="btn-mass-pill btn-mass-yellow mt-2">
            <i class="fa-solid fa-house"></i> Volver a la tienda
        </a>
    </div>
</main>
