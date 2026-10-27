<?php
/**
 * Página principal.
 */
if (!defined('MASS_APP')) {
    http_response_code(404);
    exit;
}
?>
<?php partial('partials/header', ['store' => $store, 'filters' => $filters, 'user' => $user]); ?>

<main id="contenido-principal" tabindex="-1">
    <?php partial('partials/hero'); ?>

    <?php partial('partials/benefits'); ?>

    <?php partial('partials/categories', ['categories' => $categories, 'filters' => $filters]); ?>

    <?php partial('partials/featured', ['featured' => $featured]); ?>

    <?php partial('partials/catalog-banner'); ?>

    <?php partial('partials/catalog', [
        'categories' => $categories,
        'products' => $products,
        'deliveryZones' => $deliveryZones,
        'filters' => $filters,
        'productsCount' => $productsCount,
        'totalProducts' => $totalProducts,
    ]); ?>

    <?php partial('partials/encarte-banner'); ?>

    <?php partial('partials/locator', ['store' => $store]); ?>

    <?php partial('partials/jobs'); ?>

    <?php partial('partials/local-offer'); ?>

    <?php partial('partials/testimonials', ['testimonials' => $testimonials]); ?>
</main>

<?php partial('partials/footer', ['store' => $store]); ?>

<?php partial('partials/floating', ['whatsapp' => $whatsapp]); ?>

<?php partial('partials/cart-drawer', ['deliveryZones' => $deliveryZones, 'filters' => $filters]); ?>

<?php partial('partials/quick-view-modal'); ?>

<?php partial('partials/encarte-modal'); ?>

<?php partial('partials/order-modal'); ?>

<?php partial('partials/account-modal', [
    'user' => $user,
    'userOrders' => $userOrders,
    'csrfToken' => $csrfToken,
    'whatsapp' => $whatsapp,
]); ?>

<?php partial('partials/policies-modal'); ?>

<?php partial('partials/claims-modal'); ?>

<?php partial('partials/privacy-modal'); ?>

<?php partial('partials/jobs-modal', ['positions' => $positions]); ?>
