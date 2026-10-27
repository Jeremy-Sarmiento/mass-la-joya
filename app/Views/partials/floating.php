<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= BACK TO TOP BUTTON ================= -->
	  <button id="scrollTopBtn" class="scroll-top-btn" type="button" aria-label="Volver arriba">
	    <i class="fa-solid fa-chevron-up"></i>
	  </button>

	  <!-- ================= FLOATING WHATSAPP BUTTON ================= -->
	  <a href="https://wa.me/<?= e($whatsapp) ?>?text=Hola%20Mass%20de%20La%20Joya,%20quiero%20hacer%20un%20pedido%20o%20consultar%20ofertas" target="_blank" class="floating-whatsapp" aria-label="Escribir por WhatsApp">
	    <i class="fa-brands fa-whatsapp fs-4"></i>
	    <span class="d-none d-sm-inline">WhatsApp La Joya</span>
	  </a>

	  <!-- ================= MOBILE BOTTOM NAVIGATION ================= -->
	  <nav class="mobile-bottom-nav">
	    <a href="#inicio" class="mobile-nav-item active">
	      <i class="fa-solid fa-house"></i>
	      <span>Inicio</span>
	    </a>
	    <a href="#seccion-catalogo" class="mobile-nav-item">
	      <i class="fa-solid fa-basket-shopping"></i>
	      <span>Catálogo</span>
	    </a>
	    <a href="javascript:void(0)" onclick="openEncarteModal(1)" class="mobile-nav-item">
	      <i class="fa-solid fa-book-open"></i>
	      <span>Encarte</span>
	    </a>
	    <a href="javascript:void(0)" onclick="openCartDrawer()" class="mobile-nav-item">
	      <i class="fa-solid fa-cart-shopping"></i>
	      <span>Carrito</span>
	      <span class="cart-counter cart-counter-badge" style="top:0; right:15px; display:none;">0</span>
	    </a>
	    <a href="#seccion-ubicame" class="mobile-nav-item">
	      <i class="fa-solid fa-location-dot"></i>
	      <span>Ubícanos</span>
	    </a>
	  </nav>
