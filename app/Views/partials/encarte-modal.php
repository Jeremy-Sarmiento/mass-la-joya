<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= MODAL: ENCARTE DIGITAL VIEWER ================= -->
	  <div class="modal fade" id="encarteViewerModal" tabindex="-1" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered modal-xl">
	      <div class="modal-content rounded-4 border-0 shadow-lg bg-dark text-white">
	        <div class="modal-header border-secondary">
	          <h5 class="modal-title fw-bold text-warning"><i class="fa-solid fa-book-open"></i> Encarte Oficial Mass de La Joya</h5>
	          <span class="badge bg-primary ms-3" id="encarte-page-counter">Página 1 de 3</span>
	          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
	        </div>
	        <div class="modal-body encarte-modal-body p-3">
	          <div class="encarte-viewer-container">
	            <button id="encarte-prev-btn" class="encarte-nav-btn encarte-nav-prev" onclick="changeEncartePage(-1)">
	              <i class="fa-solid fa-chevron-left"></i>
	            </button>
	            <img id="encarte-page-image" src="assets/img/encarte-page-1.svg" class="encarte-page-img" alt="Encarte de ofertas">
	            <button id="encarte-next-btn" class="encarte-nav-btn encarte-nav-next" onclick="changeEncartePage(1)">
	              <i class="fa-solid fa-chevron-right"></i>
	            </button>
	          </div>
	        </div>
	        <div class="modal-footer border-secondary justify-content-between">
	          <div class="small text-muted">
	            <i class="fa-solid fa-circle-info text-warning"></i> Precios válidos para Tienda Mass de La Joya y pedidos WhatsApp.
	          </div>
	          <div>
	            <button class="btn-mass-pill btn-mass-yellow btn-sm" onclick="openCartDrawer(); bootstrap.Modal.getInstance(document.getElementById('encarteViewerModal')).hide();">
	              <i class="fa-solid fa-cart-shopping"></i> Ver mi Carrito
	            </button>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
