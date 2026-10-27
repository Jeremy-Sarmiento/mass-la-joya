<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= FULL PRODUCT CATALOG SECTION ================= -->
	  <section id="seccion-catalogo" class="py-5 bg-light" data-reveal>
	    <div class="container">
	      <div class="section-title-wrap">
	        <span class="section-badge">Catálogo en Vivo</span>
	        <h2 class="section-heading">TODOS LOS PRODUCTOS MASS</h2>
	        <p class="section-subtitle" id="products-count-label" aria-live="polite"><?= (int) $productsCount === (int) $totalProducts ? 'Mostrando los ' . (int) $totalProducts . ' productos de la tienda' : 'Mostrando ' . (int) $productsCount . ' de ' . (int) $totalProducts . ' productos' ?></p>
	      </div>

	      <?php
	      $hasFilters = ($filters['category'] ?? 'todos') !== 'todos'
	          || ($filters['search'] ?? '') !== ''
	          || ($filters['sort'] ?? 'default') !== 'default';

	      // Chips de filtros activos renderizados en el servidor (también sin JS).
	      $sortLabels = [
	          'price-asc' => 'Precio: menor a mayor',
	          'price-desc' => 'Precio: mayor a menor',
	          'discount' => 'Mayor descuento',
	          'name' => 'Nombre A-Z',
	      ];
	      $activeChips = [];

	      if (($filters['category'] ?? 'todos') !== 'todos') {
	          $categoryName = $filters['category'];
	          foreach ($categories as $category) {
	              if ($category['id'] === $filters['category']) {
	                  $categoryName = $category['name'];
	                  break;
	              }
	          }
	          $activeChips[] = ['type' => 'category', 'icon' => 'fa-tag', 'label' => $categoryName];
	      }

	      if (($filters['search'] ?? '') !== '') {
	          $activeChips[] = ['type' => 'search', 'icon' => 'fa-magnifying-glass', 'label' => '“' . $filters['search'] . '”'];
	      }

	      if (($filters['sort'] ?? 'default') !== 'default' && isset($sortLabels[$filters['sort']])) {
	          $activeChips[] = ['type' => 'sort', 'icon' => 'fa-arrow-down-short-wide', 'label' => $sortLabels[$filters['sort']]];
	      }
	      ?>

	      <!-- Controls: Zone, Sort, Search Filters -->
	      <div class="catalog-controls p-3 bg-white rounded-4 shadow-sm border mb-3">
	        <div class="row g-3 align-items-center">
	          <div class="col-12 col-md-5">
	            <div class="d-flex align-items-center gap-2">
	              <span class="text-primary fw-bold small text-nowrap"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Entrega:</span>
	              <select class="form-select form-select-sm delivery-zone-select rounded-pill border-primary" aria-label="Zona de entrega">
	                <?php foreach ($deliveryZones as $zone): ?>
	                  <option value="<?= e($zone['id']) ?>" <?= $zone['id'] === $filters['zone'] ? 'selected' : '' ?>>
	                    <?= e($zone['name']) ?> <?= $zone['cost'] == 0 ? '(Gratis)' : '(+ ' . money($zone['cost']) . ')' ?>
	                  </option>
	                <?php endforeach; ?>
	              </select>
	            </div>
	          </div>

	          <div class="col-6 col-md-4">
	            <div class="d-flex align-items-center gap-2">
	              <span class="text-muted small text-nowrap"><i class="fa-solid fa-arrow-down-short-wide" aria-hidden="true"></i> Ordenar:</span>
	              <select id="sort-products-select" class="form-select form-select-sm rounded-pill" aria-label="Ordenar productos">
	                <option value="default" <?= $filters['sort'] === 'default' ? 'selected' : '' ?>>Recomendados</option>
	                <option value="price-asc" <?= $filters['sort'] === 'price-asc' ? 'selected' : '' ?>>Precio: Menor a Mayor</option>
	                <option value="price-desc" <?= $filters['sort'] === 'price-desc' ? 'selected' : '' ?>>Precio: Mayor a Menor</option>
	                <option value="discount" <?= $filters['sort'] === 'discount' ? 'selected' : '' ?>>Mayor Descuento %</option>
	                <option value="name" <?= $filters['sort'] === 'name' ? 'selected' : '' ?>>Nombre A-Z</option>
	              </select>
	            </div>
	          </div>

	          <div class="col-6 col-md-3 text-end">
	            <button id="reset-filters-btn" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="resetFilters()" <?= $hasFilters ? '' : 'hidden' ?>>
	              <i class="fa-solid fa-filter-circle-xmark" aria-hidden="true"></i> Limpiar filtros
	            </button>
	          </div>
	        </div>
	      </div>

	      <!-- Filtros activos (JavaScript los mantiene sincronizados) -->
	      <div id="active-filters" class="active-filters" aria-live="polite" <?= $activeChips === [] ? 'hidden' : '' ?>>
	        <?php if ($activeChips !== []): ?>
	          <span class="active-filters-label">Filtros activos:</span>
	          <?php foreach ($activeChips as $chip): ?>
	            <span class="filter-chip">
	              <i class="fa-solid <?= e($chip['icon']) ?>" aria-hidden="true"></i>
	              <span class="filter-chip-label"><?= e($chip['label']) ?></span>
	              <button type="button" class="filter-chip-remove" onclick="removeFilter('<?= e($chip['type']) ?>')" aria-label="Quitar filtro: <?= e($chip['label']) ?>">
	                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
	              </button>
	            </span>
	          <?php endforeach; ?>
	          <button type="button" class="filter-clear-all" onclick="resetFilters()">Limpiar todo</button>
	        <?php endif; ?>
	      </div>

	      <!-- Product Grid: renderizado por PHP; JavaScript lo actualiza al filtrar -->
	      <div class="row g-3" id="products-grid">
	        <?php if ($productsCount === 0): ?>
	          <div class="col-12">
	            <div class="catalog-empty p-5 bg-white rounded-4 shadow-sm text-center">
	              <i class="fa-solid fa-face-frown text-warning fa-3x mb-3" aria-hidden="true"></i>
	              <h4 class="text-primary fw-bold">No encontramos productos con ese filtro</h4>
	              <p class="text-muted mb-4">Prueba buscando con otra palabra o selecciona otra categoría.</p>
	              <button class="btn-mass-pill btn-mass-yellow" onclick="resetFilters()">
	                <i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i> Ver todos los productos
	              </button>
	            </div>
	          </div>
	        <?php else: ?>
	          <?php foreach ($products as $product): ?>
	            <?php partial('partials/product-card', ['product' => $product]); ?>
	          <?php endforeach; ?>
	        <?php endif; ?>
	      </div>
	    </div>
	  </section>
