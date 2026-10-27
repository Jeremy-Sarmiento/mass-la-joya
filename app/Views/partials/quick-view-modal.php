<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= MODAL: QUICK VIEW PRODUCT ================= -->
	  <div class="modal fade" id="productQuickViewModal" tabindex="-1" aria-labelledby="quick-view-title" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered modal-lg">
	      <div class="modal-content rounded-4 border-0 shadow-lg">
	        <div class="modal-header border-0 pb-0">
	          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar vista rápida"></button>
	        </div>
	        <div class="modal-body p-4" id="quick-view-body">
	          <!-- Rendered dynamically -->
	        </div>
	      </div>
	    </div>
	  </div>
