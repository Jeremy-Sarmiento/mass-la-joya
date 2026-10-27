<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= MODAL: TRABAJA CON NOSOTROS ================= -->
	  <div class="modal fade" id="trabajaModal" tabindex="-1" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered">
	      <div class="modal-content rounded-4 border-0 shadow-lg">
	        <div class="modal-header bg-warning border-0">
	          <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-users"></i> Postulación Fuerza Amarilla - La Joya</h5>
	          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	        </div>
	        <div class="modal-body p-4">
	          <p class="text-secondary small">Completa el formulario para participar en las convocatorias de Mass de La Joya:</p>
	          <form id="trabaja-form" method="post" action="<?= e(url('api/job-applications')) ?>" data-api-form="jobs" data-success-anchor="#seccion-fuerza-amarilla">
	            <?= csrf_field() ?>
	            <input type="hidden" name="_anchor" value="#seccion-fuerza-amarilla">
	            <div class="mb-2">
	              <label class="form-label small fw-bold" for="job-name">Nombre Completo:</label>
	              <input type="text" id="job-name" name="name" class="form-control form-control-sm" required minlength="3" maxlength="120">
	            </div>
	            <div class="row g-2 mb-2">
	              <div class="col-6">
	                <label class="form-label small fw-bold" for="job-dni">DNI:</label>
	                <input type="text" id="job-dni" name="dni" class="form-control form-control-sm" required minlength="8" maxlength="12">
	              </div>
	              <div class="col-6">
	                <label class="form-label small fw-bold" for="job-age">Edad:</label>
	                <input type="number" id="job-age" name="age" min="18" max="70" class="form-control form-control-sm" required placeholder="18+">
	              </div>
	            </div>
	            <div class="mb-2">
	              <label class="form-label small fw-bold" for="job-phone">Teléfono Celular:</label>
	              <input type="tel" id="job-phone" name="phone" class="form-control form-control-sm" required maxlength="20">
	            </div>
	            <div class="mb-2">
	              <label class="form-label small fw-bold" for="job-zone">Zona de residencia en La Joya:</label>
	              <input type="text" id="job-zone" name="zone" class="form-control form-control-sm" required minlength="3" maxlength="120" placeholder="Ej: La Joya Centro / El Triunfo">
	            </div>
	            <div class="mb-3">
	              <label class="form-label small fw-bold" for="job-position">Puesto de interés:</label>
	              <select id="job-position" name="position" class="form-select form-select-sm">
	                <?php foreach ($positions as $position): ?>
	                  <option value="<?= e($position) ?>"><?= e($position) ?></option>
	                <?php endforeach; ?>
	              </select>
	            </div>
	            <button type="submit" class="btn-mass-pill btn-mass-blue w-100">
	              Enviar Postulación
	            </button>
	          </form>
	        </div>
	      </div>
	    </div>
	  </div>
