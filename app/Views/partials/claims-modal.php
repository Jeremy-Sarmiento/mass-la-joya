<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= MODAL: LIBRO DE RECLAMACIONES ================= -->
	  <div class="modal fade" id="libroReclamacionesModal" tabindex="-1" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered modal-lg">
	      <div class="modal-content rounded-4 border-0 shadow-lg">
	        <div class="modal-header bg-primary text-white border-0">
	          <h5 class="modal-title fw-bold text-warning"><i class="fa-solid fa-book"></i> Libro de Reclamaciones Virtual - Mass La Joya</h5>
	          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
	        </div>
	        <div class="modal-body p-4">
	          <p class="text-secondary small">Conforme a lo establecido en el Código de Protección y Defensa del Consumidor (Ley N° 29571), la tienda pone a su disposición este Libro de Reclamaciones Virtual.</p>
	          <form id="reclamaciones-form" method="post" action="<?= e(url('api/claims')) ?>" data-api-form="claims" data-success-anchor="#libro-reclamaciones">
	            <?= csrf_field() ?>
	            <input type="hidden" name="_anchor" value="#libro-reclamaciones">
	            <div class="row g-2 mb-2">
	              <div class="col-md-6">
	                <label class="form-label small fw-bold" for="claim-name">Nombre y Apellidos:</label>
	                <input type="text" id="claim-name" name="name" class="form-control form-control-sm" required minlength="3" maxlength="120">
	              </div>
	              <div class="col-md-6">
	                <label class="form-label small fw-bold" for="claim-dni">DNI / CE:</label>
	                <input type="text" id="claim-dni" name="dni" class="form-control form-control-sm" required minlength="8" maxlength="12">
	              </div>
	            </div>
	            <div class="row g-2 mb-2">
	              <div class="col-md-6">
	                <label class="form-label small fw-bold" for="claim-phone">Teléfono / Celular:</label>
	                <input type="tel" id="claim-phone" name="phone" class="form-control form-control-sm" required maxlength="20">
	              </div>
	              <div class="col-md-6">
	                <label class="form-label small fw-bold" for="claim-email">Correo Electrónico:</label>
	                <input type="email" id="claim-email" name="email" class="form-control form-control-sm" required maxlength="120">
	              </div>
	            </div>
	            <div class="mb-2">
	              <label class="form-label small fw-bold" for="claim-address">Dirección en La Joya:</label>
	              <input type="text" id="claim-address" name="address" class="form-control form-control-sm" required minlength="5" maxlength="200">
	            </div>
	            <div class="mb-2">
	              <label class="form-label small fw-bold" for="claim-type">Tipo de Reclamo:</label>
	              <select id="claim-type" name="type" class="form-select form-select-sm" required>
	                <option value="queja">Queja (Disconformidad respecto a la atención)</option>
	                <option value="reclamo">Reclamo (Disconformidad relacionada a los productos o servicios)</option>
	              </select>
	            </div>
	            <div class="mb-3">
	              <label class="form-label small fw-bold" for="claim-detail">Detalle de la Queja o Reclamo:</label>
	              <textarea id="claim-detail" name="detail" class="form-control form-control-sm" rows="3" required minlength="10" maxlength="1000" placeholder="Describe lo ocurrido..."></textarea>
	            </div>
	            <button type="submit" class="btn-mass-pill btn-mass-blue w-100">
	              Registrar Hoja de Reclamación
	            </button>
	          </form>
	        </div>
	      </div>
	    </div>
	  </div>
