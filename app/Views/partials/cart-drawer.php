<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= CART DRAWER OVERLAY & PANEL ================= -->
	  <div id="cart-drawer-overlay" class="cart-drawer-overlay" onclick="closeCartDrawer()" aria-hidden="true"></div>
	  <aside id="cart-drawer" class="cart-drawer" role="dialog" aria-modal="true" aria-labelledby="cart-drawer-title" inert>
	    <!-- Drawer Header -->
	    <div class="cart-header">
	      <div class="d-flex align-items-center gap-2">
	        <i class="fa-solid fa-cart-shopping fs-5 text-primary" aria-hidden="true"></i>
	        <h4 class="m-0" id="cart-drawer-title">Mi Carrito Mass</h4>
	        <span class="cart-drawer-badge" id="cart-drawer-count-badge">0 productos</span>
	      </div>
	      <button class="cart-close-btn" onclick="closeCartDrawer()" aria-label="Cerrar carrito">
	        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
	      </button>
	    </div>

	    <div id="cart-status" class="visually-hidden" role="status" aria-live="polite"></div>

	    <!-- Empty State -->
	    <div id="cart-empty-state" class="cart-empty-state" style="display:none;">
	      <div class="text-center">
	        <div class="mb-3">
	          <i class="fa-solid fa-cart-arrow-down text-warning fa-4x" aria-hidden="true"></i>
	        </div>
	        <h5 class="fw-bold text-primary mb-2">Tu carrito está vacío</h5>
	        <p class="text-muted small mb-4">Aprovecha los precios más bajos y llena tu carrito con productos frescos y de despensa para tu hogar.</p>
	        <button class="btn-mass-pill btn-mass-yellow px-4 py-2" onclick="closeCartDrawer(); scrollToCatalog()">
	          <i class="fa-solid fa-bag-shopping me-1" aria-hidden="true"></i> Explorar productos
	        </button>
	      </div>
	    </div>

	    <!-- Items List Body & Checkout Wrapper -->
	    <div id="cart-content-state" class="cart-content-wrapper">
	      <!-- Scrollable Middle Section -->
	      <div class="cart-scroll-area">
	        <!-- Delivery info badge -->
	        <div class="cart-delivery-banner">
	          <i class="fa-solid fa-truck-fast text-primary" aria-hidden="true"></i>
	          <span>Envíos rápidos a toda <strong>La Joya y alrededores</strong></span>
	        </div>

	        <!-- Rendered dynamically -->
	        <div id="cart-items-container" class="cart-items-list">
	          <!-- Items will be injected here -->
	        </div>

	        <!-- Coupon Code Form -->
	        <div class="cart-coupon-box">
	          <label class="form-label small fw-bold text-primary mb-1" for="coupon-input">
	            <i class="fa-solid fa-ticket me-1" aria-hidden="true"></i> Cupón de descuento
	          </label>
	          <div class="input-group input-group-sm">
	            <input type="text" id="coupon-input" class="form-control" placeholder="Ej: MASSJOYERO o LAJOYA10" autocomplete="off" maxlength="30" aria-describedby="coupon-feedback">
	            <button class="btn btn-primary fw-bold" type="button" onclick="applyCoupon()">Aplicar</button>
	          </div>
	          <div id="coupon-feedback" class="cart-coupon-feedback" role="status" aria-live="polite"></div>
	          <div id="cart-coupon-applied" class="cart-coupon-applied" hidden>
	            <span>
	              <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
	              <strong id="cart-coupon-code"></strong>
	              <span id="cart-coupon-desc"></span>
	            </span>
	            <button type="button" class="cart-coupon-remove" onclick="removeCoupon()" aria-label="Quitar cupón aplicado">
	              <i class="fa-solid fa-xmark" aria-hidden="true"></i>
	            </button>
	          </div>
	        </div>

	        <!-- Delivery & Payment Info Form -->
	        <div class="cart-checkout-card">
	          <div class="cart-checkout-card-header">
	            <i class="fa-solid fa-location-dot text-danger me-2" aria-hidden="true"></i>
	            <span>Datos para la Entrega y Pago</span>
	          </div>
	          <p class="cart-checkout-hint">Completa tus datos y te contactaremos por WhatsApp para confirmar el pedido.</p>
	          <div class="cart-checkout-card-body">
	            <div class="row g-2">
	              <div class="col-12">
	                <label class="form-label small fw-bold mb-1" for="checkout-name">Nombre completo: <span class="text-danger">*</span></label>
	                <input type="text" id="checkout-name" class="form-control form-control-sm" placeholder="Ej: Carmen Rojas" autocomplete="name" maxlength="120" aria-describedby="checkout-name-error">
	                <div id="checkout-name-error" class="checkout-field-error" role="alert"></div>
	              </div>
	              <div class="col-6">
	                <label class="form-label small fw-bold mb-1" for="checkout-phone">Teléfono / Celular:</label>
	                <input type="tel" id="checkout-phone" class="form-control form-control-sm" placeholder="Ej: 987654321" autocomplete="tel" inputmode="tel" maxlength="20" aria-describedby="checkout-phone-error">
	                <div id="checkout-phone-error" class="checkout-field-error" role="alert"></div>
	              </div>
	              <div class="col-6">
	                <label class="form-label small fw-bold mb-1" for="checkout-zone">Zona de entrega:</label>
	                <select id="checkout-zone" class="form-select form-select-sm delivery-zone-select" name="zone">
	                  <?php foreach ($deliveryZones as $zone): ?>
	                    <option value="<?= e($zone['id']) ?>" <?= $zone['id'] === $filters['zone'] ? 'selected' : '' ?>>
	                      <?= e($zone['name']) ?> <?= $zone['cost'] == 0 ? '(Gratis)' : '(+ ' . money($zone['cost']) . ')' ?>
	                    </option>
	                  <?php endforeach; ?>
	                </select>
	              </div>
	              <div class="col-12">
	                <label class="form-label small fw-bold mb-1" for="checkout-address">Dirección / Referencia:</label>
	                <input type="text" id="checkout-address" class="form-control form-control-sm" placeholder="Ej: Av. Paz Soldán, La Joya" autocomplete="street-address" maxlength="200">
	              </div>
	              <div class="col-12">
	                <label class="form-label small fw-bold mb-1" id="checkout-pay-label">Método de pago:</label>
	                <div class="cart-pay-methods" role="radiogroup" aria-labelledby="checkout-pay-label">
	                  <label class="cart-pay-opt">
	                    <input type="radio" name="payMethod" value="Yape / Plin" checked>
	                    <span><i class="fa-solid fa-mobile-screen-button text-purple" aria-hidden="true"></i> Yape/Plin</span>
	                  </label>
	                  <label class="cart-pay-opt">
	                    <input type="radio" name="payMethod" value="Efectivo Contra Entrega">
	                    <span><i class="fa-solid fa-money-bill-wave text-success" aria-hidden="true"></i> Efectivo</span>
	                  </label>
	                  <label class="cart-pay-opt">
	                    <input type="radio" name="payMethod" value="Tarjeta POS">
	                    <span><i class="fa-solid fa-credit-card text-primary" aria-hidden="true"></i> POS</span>
	                  </label>
	                </div>
	              </div>
	              <div class="col-12">
	                <label class="form-label small fw-bold mb-1" for="checkout-notes">Nota adicional:</label>
	                <input type="text" id="checkout-notes" class="form-control form-control-sm" placeholder="Ej: Casa frente a la plaza / Timbre blanco" maxlength="300">
	              </div>
	            </div>
	          </div>
	        </div>

	        <!-- Totals Breakdown Summary -->
	        <div class="cart-summary-box" aria-live="polite">
	          <div class="cart-summary-line">
	            <span class="text-muted">Subtotal productos:</span>
	            <span id="cart-subtotal-val" class="fw-bold">S/ 0.00</span>
	          </div>
	          <div class="cart-summary-line">
	            <span class="text-muted">Costo de delivery:</span>
	            <span id="cart-delivery-val" class="fw-bold text-primary">S/ 3.00</span>
	          </div>
	          <div class="cart-summary-line text-danger" id="cart-discount-line" style="display:none;">
	            <span>Descuento aplicado:</span>
	            <span id="cart-discount-val" class="fw-bold">- S/ 0.00</span>
	          </div>
	        </div>
	      </div>

	      <!-- Sticky Bottom Action Bar -->
	      <div class="cart-footer-sticky">
	        <div class="cart-total-bar">
	          <div>
	            <div class="text-muted small lh-1">Total a pagar</div>
	            <div class="cart-total-price" id="cart-total-val" aria-live="polite">S/ 0.00</div>
	          </div>
	          <button class="btn-whatsapp-checkout" onclick="checkoutViaWhatsApp()" aria-label="Completar pedido por WhatsApp">
	            <i class="fa-brands fa-whatsapp fs-4" aria-hidden="true"></i>
	            <span>Pedir por WhatsApp</span>
	          </button>
	        </div>
	        <p class="cart-checkout-note mb-1">
	          <i class="fa-solid fa-lock" aria-hidden="true"></i> Se abrirá WhatsApp con el resumen de tu pedido.
	        </p>
	        <div class="d-flex justify-content-between align-items-center pt-1 px-1">
	          <button type="button" class="btn btn-link btn-sm text-danger text-decoration-none p-0" onclick="clearCart()">
	            <i class="fa-solid fa-trash-can me-1" aria-hidden="true"></i> Vaciar carrito
	          </button>
	          <button type="button" class="btn btn-link btn-sm text-secondary text-decoration-none p-0" onclick="closeCartDrawer()">
	            Seguir comprando <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i>
	          </button>
	        </div>
	      </div>
	    </div>
	  </aside>
