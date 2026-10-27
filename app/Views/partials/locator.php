<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= STORE LOCATOR: LA JOYA ================= -->
	  <section id="seccion-ubicame" class="py-5 bg-white" data-reveal>
	    <div class="container">
	      <div class="section-title-wrap">
	        <span class="section-badge">Visítanos en persona</span>
	        <h2 class="section-heading">UBÍCAME EN LA JOYA - AREQUIPA</h2>
	        <p class="section-subtitle">Estamos ubicados en una zona céntrica y de fácil acceso para todos los vecinos joyeros.</p>
	      </div>

	      <div class="store-locator-card">
	        <div class="row g-0">
	          <div class="col-12 col-lg-5 store-info-box">
	            <div class="store-badge-live mb-3">
	              Abierto ahora mismo
	            </div>
	            <h3 class="text-primary fw-bold mb-2">Tienda Mass La Joya</h3>
	            <p class="text-muted mb-4"><i class="fa-solid fa-location-dot text-danger"></i> <?= e($store['address']) ?></p>

	            <div class="mb-3">
	              <h6 class="fw-bold text-dark mb-1"><i class="fa-regular fa-clock text-primary"></i> Horario de Atención:</h6>
	              <p class="text-secondary small mb-0"><?= e($store['hours']) ?> (incluyendo feriados)</p>
	            </div>

	            <div class="mb-3">
	              <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-phone text-success"></i> Central y WhatsApp:</h6>
	              <p class="text-secondary small mb-0"><?= e($store['phone']) ?></p>
	            </div>

	            <div class="mb-4">
	              <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-credit-card text-info"></i> Servicios Disponibles:</h6>
	              <div class="d-flex flex-wrap gap-1 mt-1">
	                <span class="badge bg-light text-dark border">Yape & Plin</span>
	                <span class="badge bg-light text-dark border">POS Visa & Mastercard</span>
	                <span class="badge bg-light text-dark border">Agente Corresponsal</span>
	                <span class="badge bg-light text-dark border">Recargas Móviles</span>
	                <span class="badge bg-light text-dark border">Estacionamiento</span>
	              </div>
	            </div>

	            <div class="d-flex flex-wrap gap-2">
	              <a href="<?= e($store['maps_url']) ?>" target="_blank" class="btn-mass-pill btn-mass-blue">
	                <i class="fa-solid fa-diamond-turn-right"></i> Abrir en Google Maps
	              </a>
	              <a href="https://wa.me/<?= e((string) config('whatsapp')) ?>?text=Hola%20Mass%20La%20Joya,%20tengo%20una%20consulta" target="_blank" class="btn-mass-pill btn-mass-yellow">
	                <i class="fa-brands fa-whatsapp"></i> Escribir a Tienda
	              </a>
	            </div>
	          </div>

	          <!-- Mapa Interactivo en Tiempo Real -->
	          <div class="col-12 col-lg-7 p-3 bg-light">
	            <div class="footer-map h-100">
	              <div class="d-flex justify-content-between align-items-center mb-2">
	                <h3 class="footer-map-title h5 text-primary fw-bold m-0"><i class="fa-solid fa-map-location-dot me-1"></i> Ubicación Tienda Mass La Joya</h3>
	                <a href="<?= e($store['maps_url']) ?>" target="_blank" class="badge bg-primary text-warning text-decoration-none">
	                  <i class="fa-solid fa-arrow-up-right-from-square"></i> Ver en Maps
	                </a>
	              </div>
	              <iframe
	                class="mapa-embed rounded-4 shadow-sm w-100"
	                src="https://www.openstreetmap.org/export/embed.html?bbox=-71.8285%2C-16.4295%2C-71.8140%2C-16.4215&layer=mapnik&marker=-16.4256223%2C-71.8212998"
	                height="360"
	                style="border:0;"
	                allowfullscreen=""
	                loading="lazy"
	                title="Mapa de la tienda Mass de La Joya"></iframe>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>
	  </section>
