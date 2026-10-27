<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= FULL PRODUCT CATALOG SECTION ================= -->
	  <section id="seccion-catalogo" class="py-5 bg-light">
	    <div class="container">
	      <div class="section-title-wrap">
	        <span class="section-badge">Catálogo en Vivo</span>
	        <h2 class="section-heading">TODOS LOS PRODUCTOS MASS</h2>
	        <p class="section-subtitle" id="products-count-label">Mostrando <?= (int) $productsCount ?> productos en Mass de La Joya</p>
	      </div>

	      <!-- Controls: Zone, Sort, Search Filters -->
	      <div class="p-3 bg-white rounded-4 shadow-sm border mb-4">
	        <div class="row g-3 align-items-center">
	          <div class="col-12 col-md-5">
	            <div class="d-flex align-items-center gap-2">
	              <span class="text-primary fw-bold small text-nowrap"><i class="fa-solid fa-location-dot"></i> Entrega:</span>
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
	              <span class="text-muted small text-nowrap"><i class="fa-solid fa-arrow-down-short-wide"></i> Ordenar:</span>
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
	            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="resetFilters()">
	              <i class="fa-solid fa-filter-circle-xmark"></i> Limpiar Filtros
	            </button>
	          </div>
	        </div>
	      </div>

	      <!-- Product Grid: renderizado por PHP; JavaScript lo actualiza al filtrar -->
	      <div class="row g-3" id="products-grid">
	        <?php if ($productsCount === 0): ?>
	          <div class="col-12 text-center py-5">
	            <div class="p-5 bg-white rounded-4 shadow-sm">
	              <i class="fa-solid fa-face-frown text-warning fa-3x mb-3"></i>
	              <h4 class="text-primary fw-bold">No encontramos productos con ese filtro</h4>
	              <p class="text-muted">Prueba buscando con otra palabra o selecciona otra categoría.</p>
	              <button class="btn-mass-pill btn-mass-yellow mt-2" onclick="resetFilters()">
	                <i class="fa-solid fa-arrows-rotate"></i> Ver todos los productos
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
