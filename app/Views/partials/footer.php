<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= FOOTER ================= -->
	  <footer>
	    <div class="container">
	      <!-- Métodos de Pago -->
	      <div class="text-center mb-5">
	        <h4 class="footer-heading mb-3">MÉTODOS DE PAGO ACEPTADOS</h4>
	        <img src="assets/img/metodos-pago.png" alt="Métodos de pago en Tiendas Mass" class="img-fluid" style="max-height: 48px;">
	      </div>

	      <div class="row g-4 mb-4">
	        <!-- About Mass La Joya -->
	        <div class="col-12 col-md-4">
	          <div class="d-flex align-items-center gap-2 mb-3">
	            <img src="assets/img/mass_logo.webp" alt="Mass Logo" style="height: 42px;">
	            <span class="fs-4 fw-bold text-primary">mass <span class="badge bg-primary text-warning small">La Joya</span></span>
	          </div>
	          <p class="text-secondary small">
	            Soy la tienda Mass, la más Mass de todos los barrios con productos de calidad y a los mejores precios para que tú y tu familia siempre puedan ahorrar en sus compras diarias.
	          </p>
	          <div class="d-flex gap-2">
	            <a href="<?= e($store['social']['facebook']) ?>" target="_blank" class="social-icon-btn" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
	            <a href="<?= e($store['social']['instagram']) ?>" target="_blank" class="social-icon-btn" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
	            <a href="<?= e($store['social']['tiktok']) ?>" target="_blank" class="social-icon-btn" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
	            <a href="<?= e($store['social']['youtube']) ?>" target="_blank" class="social-icon-btn" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
	          </div>
	        </div>

	        <!-- Useful Links -->
	        <div class="col-6 col-md-4">
	          <h5 class="footer-heading">ENLACES RÁPIDOS</h5>
	          <ul class="footer-links">
	            <li><a href="#inicio">Inicio</a></li>
	            <li><a href="javascript:void(0)" onclick="openAccountModal()">Mi cuenta</a></li>
	            <li><a href="#seccion-catalogo">Precios Mass Catálogo</a></li>
	            <li><a href="javascript:void(0)" onclick="openEncarteModal(1)">Encarte Quincenal</a></li>
	            <li><a href="#seccion-ubicame">Ubícanos en La Joya</a></li>
	            <li><a href="#seccion-fuerza-amarilla">Trabaja con Nosotros</a></li>
	            <li><a href="#seccion-ofrece-local">Alquila tu Local</a></li>
	          </ul>
	        </div>

	        <!-- Customer Service & Legals -->
	        <div class="col-6 col-md-4">
	          <h5 class="footer-heading">SERVICIO AL CLIENTE</h5>
	          <p class="text-secondary small mb-1"><strong>Horario de atención:</strong><br><?= e($store['hours']) ?></p>
	          <p class="text-secondary small mb-2"><strong>WhatsApp Tienda:</strong> <?= e($store['phone']) ?></p>
	          <ul class="footer-links">
	            <li><a href="javascript:void(0)" onclick="openModal('myModalPoliticas')">Políticas de Cambios y Devoluciones</a></li>
	            <li><a href="javascript:void(0)" onclick="openModal('libroReclamacionesModal')">Libro de Reclamaciones</a></li>
	            <li><a href="javascript:void(0)" onclick="openModal('myModalPrivacidad')">Políticas de Privacidad</a></li>
	            <li><a href="javascript:void(0)" onclick="massOpenConsentSettings()">Configurar cookies</a></li>
	          </ul>
	          <div class="mt-3">
	            <a href="javascript:void(0)" onclick="openModal('libroReclamacionesModal')">
	              <img src="assets/img/libroReclamaciones.webp" alt="Libro de Reclamaciones Virtual" style="max-width: 160px;">
	            </a>
	          </div>
	        </div>
	      </div>

	      <div class="border-top pt-3 text-center text-muted small">
	        <p class="mb-0">© <?= date('Y') ?> Tiendas Mass - Sede Mass de La Joya | <?= e($store['legal_name']) ?> | Todos los derechos reservados.</p>
	      </div>
	    </div>
	  </footer>
