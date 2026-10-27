<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
<?php $user = $user ?? null; ?>
	  <!-- ================= MAIN HEADER & NAVBAR ================= -->
	  <header class="navbar-mass">
	    <div class="container">
	      <div class="row align-items-center g-2">
	        <!-- Logo & Brand -->
	        <div class="col-7 col-md-3">
	          <a href="#inicio" class="mass-brand-container">
	            <img src="assets/img/mass_logo.webp" alt="Logo Mass" class="mass-brand-logo">
	            <div class="mass-brand-text">
	              <span class="mass-brand-title">mass</span>
	              <span class="mass-brand-badge">La Joya</span>
	            </div>
	          </a>
	        </div>

	        <!-- Search Bar with Autocomplete -->
	        <div class="col-12 col-md-5 order-3 order-md-2">
	          <div class="search-wrapper" role="search">
	            <input
	              type="text"
	              id="main-search-input"
	              class="search-input"
	              value="<?= e($filters['search'] ?? '') ?>"
	              placeholder="Buscar producto en Mass..."
	              role="combobox"
	              aria-label="Buscar productos en Mass de La Joya"
	              aria-autocomplete="list"
	              aria-expanded="false"
	              aria-controls="search-results-dropdown"
	              autocomplete="off"
	              maxlength="80">
	            <button class="search-btn" type="button" aria-label="Buscar" tabindex="-1">
	              <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
	            </button>
	            <button id="search-clear-btn" class="btn btn-sm text-muted position-absolute top-50 translate-middle-y p-0" style="display:<?= empty($filters['search']) ? 'none' : 'block' ?>; right: 36px; font-size: 0.8rem;" onclick="clearSearch()" aria-label="Limpiar búsqueda">
	              <i class="fa-solid fa-xmark" aria-hidden="true"></i>
	            </button>
	            <div id="search-results-dropdown" class="search-dropdown" role="listbox" aria-label="Resultados de búsqueda"></div>
	          </div>
	        </div>

	        <!-- Actions & Cart Button -->
	        <div class="col-5 col-md-4 order-2 order-md-3 text-end d-flex align-items-center justify-content-end gap-2">
	          <button class="btn-mass-pill btn-mass-blue d-none d-xxl-inline-flex" onclick="openEncarteModal(1)">
	            <i class="fa-solid fa-book-open"></i> Encarte Digital
	          </button>

	          <div id="account-header-slot" class="account-header-slot">
	            <?php partial('partials/account-button', ['user' => $user]); ?>
	          </div>

	          <button type="button" class="btn-mass-pill btn-mass-blue theme-toggle-btn" id="themeToggleBtn" onclick="toggleTheme()" aria-label="Cambiar a modo oscuro" aria-pressed="false">
	            <i class="fa-solid fa-moon"></i>
	          </button>

	          <button class="btn-mass-pill btn-mass-blue cart-btn-wrapper" onclick="openCartDrawer()" aria-label="Ver carrito de compras" aria-controls="cart-drawer" aria-expanded="false" data-cart-open>
	            <i class="fa-solid fa-cart-shopping fs-6" aria-hidden="true"></i>
	            <span class="d-none d-sm-inline" id="cart-header-total">S/ 0.00</span>
	            <span class="cart-counter cart-counter-badge" style="display:none;">0</span>
	          </button>
	        </div>
	      </div>
	    </div>

	    <!-- Secondary Links Bar -->
	    <nav class="nav-categories-bar mt-2">
	      <div class="container">
	        <ul class="nav-links-list">
	          <li><a href="#inicio" class="nav-link-item active"><i class="fa-solid fa-house"></i> Inicio</a></li>
	          <li><a href="#seccion-ofertas" class="nav-link-item"><i class="fa-solid fa-tag"></i> Precios Más Mass</a></li>
	          <li><a href="#seccion-catalogo" class="nav-link-item"><i class="fa-solid fa-basket-shopping"></i> Catálogo Completo</a></li>
	          <li><a href="javascript:void(0)" onclick="openEncarteModal(1)" class="nav-link-item"><i class="fa-solid fa-newspaper"></i> Encarte de la Quincena</a></li>
	          <li><a href="#seccion-ubicame" class="nav-link-item"><i class="fa-solid fa-location-dot"></i> Ubícanos en La Joya</a></li>
	          <li><a href="#seccion-fuerza-amarilla" class="nav-link-item"><i class="fa-solid fa-users"></i> Fuerza Amarilla</a></li>
	          <li><a href="#seccion-ofrece-local" class="nav-link-item"><i class="fa-solid fa-store"></i> Ofrece tu Local</a></li>
	          <li><a href="javascript:void(0)" onclick="openModal('myModalPoliticas')" class="nav-link-item"><i class="fa-solid fa-rotate-left"></i> Cambios y Devoluciones</a></li>
	        </ul>
	      </div>
	    </nav>
	  </header>
