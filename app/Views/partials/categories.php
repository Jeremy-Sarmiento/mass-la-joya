<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= CATEGORIES SECTION ================= -->
	  <section class="py-4">
	    <div class="container">
	      <div class="section-title-wrap">
	        <span class="section-badge">Explora por Departamentos</span>
	        <h2 class="section-heading">¿QUÉ ESTÁS BUSCANDO HOY?</h2>
	        <p class="section-subtitle">Selecciona una categoría para filtrar al instante todos los productos disponibles en tu tienda Mass de La Joya.</p>
	      </div>

	      <div class="row g-3" id="category-pills-container">
	        <?php foreach ($categories as $category): ?>
	          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
	            <div class="category-pill-card <?= $category['id'] === $filters['category'] ? 'active' : '' ?>" onclick="selectCategory('<?= e($category['id']) ?>')">
	              <div class="category-icon-circle">
	                <i class="fa-solid <?= e($category['icon']) ?>"></i>
	              </div>
	              <span class="category-name"><?= e($category['name']) ?></span>
	            </div>
	          </div>
	        <?php endforeach; ?>
	      </div>
	    </div>
	  </section>
