<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
<?php
/**
 * Tarjeta de producto reutilizable.
 * Variable: $product (array del catálogo).
 */
$product = $product ?? [];
?>
<div class="col-6 col-md-4 col-lg-3">
    <div class="product-card" data-product-id="<?= e($product['id'] ?? '') ?>">
        <?php if (!empty($product['badge'])): ?>
            <span class="product-badge <?= e($product['badgeColor'] ?? '') ?>"><?= e($product['badge']) ?></span>
        <?php endif; ?>

        <div class="product-img-wrapper" onclick="openQuickView('<?= e($product['id'] ?? '') ?>')">
            <img src="<?= e($product['image'] ?? '') ?>" alt="<?= e($product['name'] ?? '') ?>" loading="lazy">
        </div>

        <span class="product-brand"><?= e($product['brand'] ?? '') ?></span>
        <h3 class="product-title" onclick="openQuickView('<?= e($product['id'] ?? '') ?>')" title="<?= e($product['name'] ?? '') ?>"><?= e($product['name'] ?? '') ?></h3>
        <span class="product-unit"><?= e($product['unit'] ?? '') ?></span>

        <div class="price-container">
            <span class="current-price"><?= e(money($product['price'] ?? 0)) ?></span>
            <?php if (!empty($product['oldPrice'])): ?>
                <span class="old-price"><?= e(money($product['oldPrice'])) ?></span>
            <?php endif; ?>
        </div>

        <div class="product-actions mt-auto" data-product-actions="<?= e($product['id'] ?? '') ?>">
            <button class="btn-add-cart" onclick="addToCart('<?= e($product['id'] ?? '') ?>')">
                <i class="fa-solid fa-cart-plus"></i> Agregar
            </button>
        </div>
    </div>
</div>
