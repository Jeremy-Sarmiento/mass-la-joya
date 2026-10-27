<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= BACK TO TOP BUTTON ================= -->
	  <button id="scrollTopBtn" class="scroll-top-btn" type="button" aria-label="Volver arriba">
	    <i class="fa-solid fa-chevron-up"></i>
	  </button>

	  <!-- ================= FLOATING WHATSAPP BUTTON ================= -->
	  <a href="https://wa.me/<?= e($whatsapp) ?>?text=Hola%20Mass%20de%20La%20Joya,%20quiero%20hacer%20un%20pedido%20o%20consultar%20ofertas" target="_blank" rel="noopener" class="floating-whatsapp" aria-label="Escribir por WhatsApp a Mass de La Joya">
	    <i class="fa-brands fa-whatsapp fs-4" aria-hidden="true"></i>
	    <span class="d-none d-sm-inline">WhatsApp La Joya</span>
	  </a>

	  <!-- ================= MOBILE CART SUMMARY BAR ================= -->
	  <div id="mobile-cart-bar" class="mobile-cart-bar" hidden>
	    <div class="mobile-cart-bar-info">
	      <span class="mobile-cart-bar-count" id="mobile-cart-count">0 productos</span>
	      <span class="mobile-cart-bar-total" id="mobile-cart-total">S/ 0.00</span>
	    </div>
	    <button type="button" class="mobile-cart-bar-btn" onclick="openCartDrawer()" aria-controls="cart-drawer">
	      Ver carrito <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
	    </button>
	  </div>

	  <!-- ================= MOBILE BOTTOM NAVIGATION ================= -->
	  <nav class="mobile-bottom-nav" aria-label="Navegación rápida">
	    <a href="#inicio" class="mobile-nav-item active" aria-current="true">
	      <i class="fa-solid fa-house" aria-hidden="true"></i>
	      <span>Inicio</span>
	    </a>
	    <a href="#seccion-catalogo" class="mobile-nav-item">
	      <i class="fa-solid fa-basket-shopping" aria-hidden="true"></i>
	      <span>Catálogo</span>
	    </a>
	    <a href="javascript:void(0)" onclick="openEncarteModal(1)" class="mobile-nav-item">
	      <i class="fa-solid fa-book-open" aria-hidden="true"></i>
	      <span>Encarte</span>
	    </a>
	    <a href="javascript:void(0)" onclick="openCartDrawer()" class="mobile-nav-item" data-cart-open aria-controls="cart-drawer">
	      <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
	      <span>Carrito</span>
	      <span class="cart-counter cart-counter-badge" style="top:0; right:15px; display:none;">0</span>
	    </a>
	    <a href="#seccion-ubicame" class="mobile-nav-item">
	      <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
	      <span>Ubícanos</span>
	    </a>
	  </nav>
