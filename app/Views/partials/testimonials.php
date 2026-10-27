<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= TESTIMONIALS OF CASER@S ================= -->
	  <section class="py-5 bg-light" data-reveal>
	    <div class="container">
	      <div class="section-title-wrap">
	        <span class="section-badge">La opinión de nuestros vecinos</span>
	        <h2 class="section-heading">LO QUE DICEN NUESTROS CASER@S</h2>
	        <p class="section-subtitle">Familias de La Joya que disfrutan de precios bajos todos los días.</p>
	      </div>

	      <div class="row" id="testimonials-container">
	        <?php foreach ($testimonials as $testimonial): ?>
	          <?php $rating = max(0, min(5, (int) ($testimonial['rating'] ?? 5))); ?>
	          <div class="col-md-4 mb-4">
	            <div class="p-4 bg-white rounded-4 shadow-sm border h-100 d-flex flex-column testimonial-card">
	              <div class="text-warning mb-2" role="img" aria-label="<?= e((string) $rating) ?> de 5 estrellas">
	                <?= str_repeat('★', $rating) ?>
	              </div>
	              <p class="text-secondary small fst-italic flex-grow-1">"<?= e($testimonial['comment'] ?? '') ?>"</p>
	              <div class="border-top pt-2 mt-2">
	                <h6 class="fw-bold text-primary mb-0"><?= e($testimonial['name'] ?? '') ?></h6>
	                <span class="text-muted small"><?= e($testimonial['zone'] ?? '') ?> • <?= e($testimonial['date'] ?? '') ?></span>
	              </div>
	            </div>
	          </div>
	        <?php endforeach; ?>
	      </div>
	    </div>
	  </section>
