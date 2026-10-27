<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
<?php
/**
 * Tarjeta de producto reutilizable.
 * Variable: $product (array del catálogo).
 */
$product = $product ?? [];
$productId = (string) ($product['id'] ?? '');
$productName = (string) ($product['name'] ?? '');
$price = (float) ($product['price'] ?? 0);
$oldPrice = (float) ($product['oldPrice'] ?? 0);
$discount = ($oldPrice > $price && $oldPrice > 0)
    ? (int) round((1 - ($price / $oldPrice)) * 100)
    : 0;
$rating = (float) ($product['rating'] ?? 0);
$reviews = (int) ($product['reviewsCount'] ?? 0);
?>
<div class="col-6 col-md-4 col-lg-3">
    <article class="product-card" data-product-id="<?= e($productId) ?>">
        <div class="product-media">
            <?php if (!empty($product['badge'])): ?>
                <span class="product-badge <?= e($product['badgeColor'] ?? '') ?>"><?= e($product['badge']) ?></span>
            <?php endif; ?>
            <?php if ($discount > 0): ?>
                <span class="product-discount" aria-label="Descuento del <?= e((string) $discount) ?> por ciento">-<?= e((string) $discount) ?>%</span>
            <?php endif; ?>

            <button type="button" class="product-img-wrapper" onclick="openQuickView('<?= e($productId) ?>')" aria-label="Ver detalles de <?= e($productName) ?>">
                <img src="<?= e($product['image'] ?? '') ?>" alt="<?= e($productName) ?>" loading="lazy" decoding="async">
                <span class="product-quick-hint" aria-hidden="true"><i class="fa-solid fa-eye"></i> Ver detalles</span>
            </button>
        </div>

        <div class="product-meta-row">
            <span class="product-brand"><?= e($product['brand'] ?? '') ?></span>
            <?php if ($rating > 0): ?>
                <span class="product-rating" title="<?= e(number_format($rating, 1)) ?> de 5 según <?= e((string) $reviews) ?> opiniones">
                    <i class="fa-solid fa-star" aria-hidden="true"></i> <?= e(number_format($rating, 1)) ?>
                    <span class="product-rating-count">(<?= e((string) $reviews) ?>)</span>
                </span>
            <?php endif; ?>
        </div>

        <h3 class="product-title-wrap">
            <button type="button" class="product-title" onclick="openQuickView('<?= e($productId) ?>')" title="<?= e($productName) ?>"><?= e($productName) ?></button>
        </h3>
        <span class="product-unit"><?= e($product['unit'] ?? '') ?></span>

        <div class="price-container">
            <span class="current-price"><?= e(money($price)) ?></span>
            <?php if ($oldPrice > $price): ?>
                <span class="old-price"><?= e(money($oldPrice)) ?></span>
            <?php endif; ?>
        </div>

        <div class="product-actions mt-auto" data-product-actions="<?= e($productId) ?>">
            <button class="btn-add-cart" onclick="addToCart('<?= e($productId) ?>')" aria-label="Agregar <?= e($productName) ?> al carrito">
                <i class="fa-solid fa-cart-plus" aria-hidden="true"></i> Agregar
            </button>
        </div>
    </article>
</div>
