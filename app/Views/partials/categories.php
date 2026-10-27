<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= CATEGORIES SECTION ================= -->
	  <section class="py-4" data-reveal>
	    <div class="container">
	      <div class="section-title-wrap">
	        <span class="section-badge">Explora por Departamentos</span>
	        <h2 class="section-heading">¿QUÉ ESTÁS BUSCANDO HOY?</h2>
	        <p class="section-subtitle">Selecciona una categoría para filtrar al instante todos los productos disponibles en tu tienda Mass de La Joya.</p>
	      </div>

	      <div class="row g-3" id="category-pills-container">
	        <?php foreach ($categories as $category): ?>
	          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
	            <button type="button" class="category-pill-card <?= $category['id'] === $filters['category'] ? 'active' : '' ?>" onclick="selectCategory('<?= e($category['id']) ?>')" aria-pressed="<?= $category['id'] === $filters['category'] ? 'true' : 'false' ?>">
	              <span class="category-icon-circle">
	                <i class="fa-solid <?= e($category['icon']) ?>" aria-hidden="true"></i>
	              </span>
	              <span class="category-name"><?= e($category['name']) ?></span>
	              <span class="category-count"><?= e((string) ($category['count'] ?? 0)) ?> productos</span>
	            </button>
	          </div>
	        <?php endforeach; ?>
	      </div>
	    </div>
	  </section>
