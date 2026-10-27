<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= OFRECE TU LOCAL ================= -->
	  <section id="seccion-ofrece-local" class="py-5 bg-white">
	    <div class="container">
	      <div class="p-4 p-md-5 bg-light rounded-4 border shadow-sm">
	        <div class="row align-items-center">
	          <div class="col-12 col-lg-6 mb-4 mb-lg-0">
	            <span class="badge bg-primary text-warning px-3 py-2 rounded-pill fw-bold mb-2">EXPANSIÓN MASS</span>
	            <h2 class="text-primary fw-bold mb-3">¿TIENES UN LOCAL EN ALQUILER EN LA JOYA?</h2>
	            <p class="text-secondary mb-3">
	              En Tiendas Mass buscamos locales comerciales para seguir abriendo nuevas sedes más cerca de las familias joyeras.
	            </p>
	            <ul class="text-secondary small ps-3 mb-4">
	              <li class="mb-1">Área mínima requerida: <strong>120 m² a 300 m²</strong> en primer piso.</li>
	              <li class="mb-1">Frente comercial mínimo de <strong>6 metros lineales</strong>.</li>
	              <li class="mb-1">Ubicación en avenidas principales, zonas comerciales o de alto tránsito peatonal.</li>
	              <li>Contratos a largo plazo con garantía y puntualidad de pago de Supermercados Peruanos.</li>
	            </ul>
	            <button class="btn-mass-pill btn-mass-yellow" onclick="openModal('ofreceLocalModal')">
	              <i class="fa-solid fa-building"></i> Registrar mi Local
	            </button>
	          </div>

	          <div class="col-12 col-lg-6">
	            <div class="bg-white p-4 rounded-4 shadow-sm border">
	              <h5 class="text-primary fw-bold mb-3"><i class="fa-solid fa-paper-plane"></i> Envíanos los datos de tu inmueble</h5>
	              <form id="ofrece-local-form" method="post" action="<?= e(url('api/local-offers')) ?>" data-api-form="local-offers" data-success-anchor="#seccion-ofrece-local">
	                <?= csrf_field() ?>
	                <input type="hidden" name="_anchor" value="#seccion-ofrece-local">
	                <div class="mb-2">
	                  <label class="form-label small fw-bold" for="local-name">Nombre del Propietario / Contacto:</label>
	                  <input type="text" id="local-name" name="name" class="form-control form-control-sm" required maxlength="120" placeholder="Ej: Juan Pérez">
	                </div>
	                <div class="row g-2 mb-2">
	                  <div class="col-6">
	                    <label class="form-label small fw-bold" for="local-phone">Teléfono / Celular:</label>
	                    <input type="tel" id="local-phone" name="phone" class="form-control form-control-sm" required maxlength="20" placeholder="987654321">
	                  </div>
	                  <div class="col-6">
	                    <label class="form-label small fw-bold" for="local-area">Área aprox (m²):</label>
	                    <input type="number" id="local-area" name="area" class="form-control form-control-sm" required min="1" max="100000" step="1" placeholder="Ej: 180">
	                  </div>
	                </div>
	                <div class="mb-3">
	                  <label class="form-label small fw-bold" for="local-address">Dirección en La Joya:</label>
	                  <input type="text" id="local-address" name="address" class="form-control form-control-sm" required minlength="5" maxlength="200" placeholder="Ej: Av. Panamericana Sur Km 980">
	                </div>
	                <button type="submit" class="btn-mass-pill btn-mass-blue w-100">
	                  Enviar Propuesta de Local
	                </button>
	              </form>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>
	  </section>
