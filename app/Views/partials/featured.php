<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= FEATURED: PRECIOS MÁS MASS ================= -->
	  <section id="seccion-ofertas" class="py-5 bg-white">
	    <div class="container">
	      <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
	        <div>
	          <span class="section-badge bg-danger text-white">¡Ahorro Máximo Diario!</span>
	          <h2 class="section-heading text-primary">PRECIOS MÁS MASS</h2>
	          <p class="text-muted mb-0">Las mejores ofertas seleccionadas especialmente para los hogares de La Joya.</p>
	        </div>
	        <div class="mt-3 mt-md-0">
	          <a href="#seccion-catalogo" class="btn-mass-pill btn-mass-yellow">
	            Ver todo el catálogo <i class="fa-solid fa-arrow-right"></i>
	          </a>
	        </div>
	      </div>

	      <div class="row g-3" id="featured-products-grid">
	        <?php foreach ($featured as $product): ?>
	          <?php partial('partials/product-card', ['product' => $product]); ?>
	        <?php endforeach; ?>
	      </div>
	    </div>
	  </section>
