<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= MODAL: ORDER TICKET CONFIRMATION ================= -->
	  <div class="modal fade" id="orderConfirmationModal" tabindex="-1" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered">
	      <div class="modal-content rounded-4 border-0 shadow-lg">
	        <div class="modal-header bg-warning border-0">
	          <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-circle-check"></i> ¡Pedido Generado con Éxito!</h5>
	          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	        </div>
	        <div class="modal-body p-4">
	          <p class="text-secondary small mb-3">
	            Hemos preparado el ticket de tu pedido. Si no se abrió WhatsApp automáticamente, haz clic en el botón verde inferior.
	          </p>
	          <div id="ticket-content-body">
	            <!-- Ticket generated dynamically -->
	          </div>
	        </div>
	        <div class="modal-footer border-0 pt-0">
	          <button type="button" class="btn btn-outline-secondary rounded-pill" onclick="window.print()">
	            <i class="fa-solid fa-print"></i> Imprimir Ticket
	          </button>
	          <button type="button" class="btn-mass-pill btn-mass-blue" data-bs-dismiss="modal">
	            Listo, Continuar
	          </button>
	        </div>
	      </div>
	    </div>
	  </div>
