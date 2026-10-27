<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= MODAL: MI CUENTA ================= -->
	  <div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalTitle" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
	      <div class="modal-content account-modal-content rounded-4 border-0 shadow-lg">
	        <div class="modal-header account-modal-header">
	          <div class="d-flex align-items-center gap-2">
	            <span class="account-header-icon" aria-hidden="true"><i class="fa-solid fa-user-shield"></i></span>
	            <div>
	              <h5 class="modal-title" id="accountModalTitle">Mi cuenta Mass</h5>
	              <p class="account-header-sub mb-0">Compra más rápido y revisa tus pedidos</p>
	            </div>
	          </div>
	          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
	        </div>
	        <div class="modal-body p-0">
	          <div id="account-panel" aria-live="polite" tabindex="-1">
	            <?php partial('partials/account-panel', [
	                'user' => $user ?? null,
	                'orders' => $userOrders ?? [],
	                'csrfToken' => $csrfToken ?? csrf_token(),
	                'whatsapp' => $whatsapp ?? (string) config('whatsapp'),
	            ]); ?>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
