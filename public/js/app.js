/**
 * MASS DE LA JOYA - APLICACIÓN PRINCIPAL
 * Gestión de catálogo, carrito, WhatsApp checkout, mapa y encarte digital
 */

// Global State
const state = {
  cart: JSON.parse(localStorage.getItem('mass_lajoya_cart')) || [],
  category: 'todos',
  search: '',
  sortBy: 'default',
  selectedZone: 'centro',
  couponCode: '',
  discountPercent: 0,
  encartePage: 1,
  totalEncartePages: 3,
  quickViewProduct: null
};

// WhatsApp Store Number for Mass La Joya
const LA_JOYA_WHATSAPP = '51997833866';

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', () => {
  initApp();
});

function initApp() {
  renderCategories();
  renderProducts();
  renderFeaturedProducts();
  updateCartUI();
  initSearch();
  initDeliveryZoneSelect();
  initLeafletMap();
  initEventListeners();
  initTestimonials();
}

/* ================= RENDER CATEGORIES ================= */
function renderCategories() {
  const container = document.getElementById('category-pills-container');
  if (!container) return;

  container.innerHTML = CATEGORIES.map(cat => {
    const isActive = state.category === cat.id;
    return `
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="category-pill-card ${isActive ? 'active' : ''}" onclick="selectCategory('${cat.id}')">
          <div class="category-icon-circle">
            <i class="fa-solid ${cat.icon}"></i>
          </div>
          <span class="category-name">${cat.name}</span>
        </div>
      </div>
    `;
  }).join('');
}

function selectCategory(catId) {
  state.category = catId;
  renderCategories();
  renderProducts();
  
  // Smooth scroll to catalog section
  const catalogEl = document.getElementById('seccion-catalogo');
  if (catalogEl) {
    catalogEl.scrollIntoView({ behavior: 'smooth' });
  }
}

/* ================= RENDER PRODUCTS ================= */
function getFilteredProducts() {
  let list = [...PRODUCTS];

  // Category filter
  if (state.category !== 'todos') {
    list = list.filter(p => p.category === state.category);
  }

  // Search filter
  if (state.search.trim()) {
    const q = state.search.toLowerCase().trim();
    list = list.filter(p => 
      p.name.toLowerCase().includes(q) ||
      p.brand.toLowerCase().includes(q) ||
      p.description.toLowerCase().includes(q) ||
      p.category.toLowerCase().includes(q)
    );
  }

  // Sorting
  if (state.sortBy === 'price-asc') {
    list.sort((a, b) => a.price - b.price);
  } else if (state.sortBy === 'price-desc') {
    list.sort((a, b) => b.price - a.price);
  } else if (state.sortBy === 'name') {
    list.sort((a, b) => a.name.localeCompare(b.name));
  } else if (state.sortBy === 'discount') {
    list.sort((a, b) => {
      const discA = a.oldPrice ? (a.oldPrice - a.price) : 0;
      const discB = b.oldPrice ? (b.oldPrice - b.price) : 0;
      return discB - discA;
    });
  }

  return list;
}

function renderProducts() {
  const container = document.getElementById('products-grid');
  const countEl = document.getElementById('products-count-label');
  if (!container) return;

  const products = getFilteredProducts();

  if (countEl) {
    countEl.textContent = `Mostrando ${products.length} productos en Mass de La Joya`;
  }

  if (products.length === 0) {
    container.innerHTML = `
      <div class="col-12 text-center py-5">
        <div class="p-5 bg-white rounded-4 shadow-sm">
          <i class="fa-solid fa-face-frown text-warning fa-3x mb-3"></i>
          <h4 class="text-primary fw-bold">No encontramos productos con ese filtro</h4>
          <p class="text-muted">Prueba buscando con otra palabra o selecciona otra categoría.</p>
          <button class="btn-mass-pill btn-mass-yellow mt-2" onclick="resetFilters()">
            <i class="fa-solid fa-arrows-rotate"></i> Ver todos los productos
          </button>
        </div>
      </div>
    `;
    return;
  }

  container.innerHTML = products.map(prod => createProductCardHtml(prod)).join('');
}

function renderFeaturedProducts() {
  const container = document.getElementById('featured-products-grid');
  if (!container) return;

  const featured = PRODUCTS.filter(p => p.isFeatured).slice(0, 8);
  container.innerHTML = featured.map(prod => createProductCardHtml(prod)).join('');
}

function createProductCardHtml(prod) {
  const inCartItem = state.cart.find(item => item.id === prod.id);
  const qty = inCartItem ? inCartItem.quantity : 0;

  return `
    <div class="col-6 col-md-4 col-lg-3">
      <div class="product-card" id="card-${prod.id}">
        ${prod.badge ? `<span class="product-badge ${prod.badgeColor}">${prod.badge}</span>` : ''}
        
        <div class="product-img-wrapper" onclick="openQuickView('${prod.id}')">
          <img src="${prod.image}" alt="${prod.name}" loading="lazy">
        </div>

        <span class="product-brand">${prod.brand}</span>
        <h3 class="product-title" onclick="openQuickView('${prod.id}')" title="${prod.name}">${prod.name}</h3>
        <span class="product-unit">${prod.unit}</span>

        <div class="price-container">
          <span class="current-price">S/ ${prod.price.toFixed(2)}</span>
          ${prod.oldPrice ? `<span class="old-price">S/ ${prod.oldPrice.toFixed(2)}</span>` : ''}
        </div>

        <div class="product-actions mt-auto" id="action-box-${prod.id}">
          ${qty > 0 ? `
            <div class="qty-control-wrapper">
              <button class="btn-qty" onclick="changeQty('${prod.id}', -1)">
                <i class="fa-solid fa-minus"></i>
              </button>
              <span class="qty-number">${qty}</span>
              <button class="btn-qty" onclick="changeQty('${prod.id}', 1)">
                <i class="fa-solid fa-plus"></i>
              </button>
            </div>
          ` : `
            <button class="btn-add-cart" onclick="addToCart('${prod.id}')">
              <i class="fa-solid fa-cart-plus"></i> Agregar
            </button>
          `}
        </div>
      </div>
    </div>
  `;
}

function resetFilters() {
  state.category = 'todos';
  state.search = '';
  state.sortBy = 'default';
  const searchInput = document.getElementById('main-search-input');
  if (searchInput) searchInput.value = '';
  renderCategories();
  renderProducts();
}

/* ================= CART MANAGEMENT ================= */
function addToCart(productId, qtyToAdd = 1) {
  const prod = PRODUCTS.find(p => p.id === productId);
  if (!prod) return;

  const existing = state.cart.find(item => item.id === productId);
  if (existing) {
    existing.quantity += qtyToAdd;
  } else {
    state.cart.push({
      id: prod.id,
      name: prod.name,
      price: prod.price,
      unit: prod.unit,
      image: prod.image,
      quantity: qtyToAdd
    });
  }

  saveCart();
  updateCartUI();
  renderProducts();
  renderFeaturedProducts();
  showToast(`¡Agregaste "${prod.name}" al carrito!`);
}

function changeQty(productId, delta) {
  const existing = state.cart.find(item => item.id === productId);
  if (!existing) return;

  existing.quantity += delta;
  if (existing.quantity <= 0) {
    state.cart = state.cart.filter(item => item.id !== productId);
    showToast('Producto retirado del carrito');
  }

  saveCart();
  updateCartUI();
  renderProducts();
  renderFeaturedProducts();
}

function removeFromCart(productId) {
  state.cart = state.cart.filter(item => item.id !== productId);
  saveCart();
  updateCartUI();
  renderProducts();
  renderFeaturedProducts();
  showToast('Producto eliminado del carrito');
}

function clearCart() {
  state.cart = [];
  saveCart();
  updateCartUI();
  renderProducts();
  renderFeaturedProducts();
  showToast('El carrito ha sido vaciado');
}

function saveCart() {
  localStorage.setItem('mass_lajoya_cart', JSON.stringify(state.cart));
}

function getCartCount() {
  return state.cart.reduce((sum, item) => sum + item.quantity, 0);
}

function getCartSubtotal() {
  return state.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
}

function getDeliveryCost() {
  const zone = DELIVERY_ZONES.find(z => z.id === state.selectedZone);
  return zone ? zone.cost : 3.00;
}

function getDiscountAmount() {
  const subtotal = getCartSubtotal();
  return (subtotal * state.discountPercent) / 100;
}

function getCartFinalTotal() {
  const subtotal = getCartSubtotal();
  const delivery = getDeliveryCost();
  const discount = getDiscountAmount();
  return Math.max(0, subtotal + delivery - discount);
}

function updateCartUI() {
  const count = getCartCount();
  const subtotal = getCartSubtotal();
  const delivery = getDeliveryCost();
  const discount = getDiscountAmount();
  const total = getCartFinalTotal();

  // Badges & Floating numbers
  const badgeEls = document.querySelectorAll('.cart-counter-badge');
  badgeEls.forEach(el => {
    el.textContent = count;
    el.style.display = count > 0 ? 'flex' : 'none';
  });

  const drawerCountBadge = document.getElementById('cart-drawer-count-badge');
  if (drawerCountBadge) {
    drawerCountBadge.textContent = `${count} ${count === 1 ? 'producto' : 'productos'}`;
  }

  const cartHeaderTotal = document.getElementById('cart-header-total');
  if (cartHeaderTotal) {
    cartHeaderTotal.textContent = `S/ ${subtotal.toFixed(2)}`;
  }

  // Cart Drawer Body
  const cartItemsContainer = document.getElementById('cart-items-container');
  const cartSubtotalEl = document.getElementById('cart-subtotal-val');
  const cartDeliveryEl = document.getElementById('cart-delivery-val');
  const cartDiscountLine = document.getElementById('cart-discount-line');
  const cartDiscountEl = document.getElementById('cart-discount-val');
  const cartTotalEl = document.getElementById('cart-total-val');
  const emptyCartState = document.getElementById('cart-empty-state');
  const cartContentState = document.getElementById('cart-content-state');

  if (cartItemsContainer) {
    if (state.cart.length === 0) {
      if (emptyCartState) emptyCartState.style.display = 'flex';
      if (cartContentState) cartContentState.style.display = 'none';
      cartItemsContainer.innerHTML = '';
    } else {
      if (emptyCartState) emptyCartState.style.display = 'none';
      if (cartContentState) cartContentState.style.display = 'flex';

      cartItemsContainer.innerHTML = state.cart.map(item => `
        <div class="cart-item">
          <img src="${item.image}" alt="${item.name}" class="cart-item-img">
          <div class="cart-item-details">
            <h5 class="cart-item-name" title="${item.name}">${item.name}</h5>
            <div class="cart-item-unit">${item.unit}</div>
            <div class="cart-item-price">S/ ${(item.price * item.quantity).toFixed(2)} <span class="text-muted small fw-normal">(S/ ${item.price.toFixed(2)} c/u)</span></div>
          </div>
          <div class="cart-item-actions">
            <button class="btn-cart-qty" title="Disminuir" onclick="changeQty('${item.id}', -1)">-</button>
            <span class="cart-qty-num">${item.quantity}</span>
            <button class="btn-cart-qty" title="Aumentar" onclick="changeQty('${item.id}', 1)">+</button>
            <button class="btn-cart-del" title="Eliminar del carrito" onclick="removeFromCart('${item.id}')">
              <i class="fa-solid fa-trash-can"></i>
            </button>
          </div>
        </div>
      `).join('');
    }
  }

  if (cartSubtotalEl) cartSubtotalEl.textContent = `S/ ${subtotal.toFixed(2)}`;
  if (cartDeliveryEl) cartDeliveryEl.textContent = delivery === 0 ? 'GRATIS' : `S/ ${delivery.toFixed(2)}`;
  
  if (cartDiscountLine && cartDiscountEl) {
    if (state.discountPercent > 0) {
      cartDiscountLine.style.display = 'flex';
      cartDiscountEl.textContent = `- S/ ${discount.toFixed(2)} (${state.discountPercent}%)`;
    } else {
      cartDiscountLine.style.display = 'none';
    }
  }

  if (cartTotalEl) cartTotalEl.textContent = `S/ ${total.toFixed(2)}`;
}

/* ================= CART DRAWER OPEN / CLOSE ================= */
function openCartDrawer() {
  const overlay = document.getElementById('cart-drawer-overlay');
  const drawer = document.getElementById('cart-drawer');
  if (overlay && drawer) {
    overlay.classList.add('active');
    drawer.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
}

function closeCartDrawer() {
  const overlay = document.getElementById('cart-drawer-overlay');
  const drawer = document.getElementById('cart-drawer');
  if (overlay && drawer) {
    overlay.classList.remove('active');
    drawer.classList.remove('active');
    document.body.style.overflow = '';
  }
}

/* ================= SEARCH & AUTOCOMPLETE ================= */
function initSearch() {
  const input = document.getElementById('main-search-input');
  const dropdown = document.getElementById('search-results-dropdown');
  const clearBtn = document.getElementById('search-clear-btn');
  const sortSelect = document.getElementById('sort-products-select');

  if (input) {
    input.addEventListener('input', (e) => {
      const q = e.target.value;
      state.search = q;

      if (clearBtn) {
        clearBtn.style.display = q ? 'block' : 'none';
      }

      if (q.trim().length >= 2) {
        const matches = PRODUCTS.filter(p => 
          p.name.toLowerCase().includes(q.toLowerCase()) ||
          p.brand.toLowerCase().includes(q.toLowerCase())
        ).slice(0, 6);

        if (dropdown) {
          if (matches.length > 0) {
            dropdown.innerHTML = matches.map(m => `
              <div class="search-item" onclick="selectSearchProduct('${m.id}')">
                <img src="${m.image}" alt="${m.name}">
                <div class="flex-grow-1">
                  <div class="fw-bold text-dark">${m.name}</div>
                  <div class="small text-muted">${m.brand} • ${m.unit}</div>
                </div>
                <div class="fw-bold text-primary">S/ ${m.price.toFixed(2)}</div>
              </div>
            `).join('');
            dropdown.classList.add('active');
          } else {
            dropdown.innerHTML = '<div class="p-3 text-muted text-center small">No se encontraron productos</div>';
            dropdown.classList.add('active');
          }
        }
      } else {
        if (dropdown) dropdown.classList.remove('active');
      }

      renderProducts();
    });

    // Close search dropdown on click outside
    document.addEventListener('click', (e) => {
      if (!input.contains(e.target) && dropdown && !dropdown.contains(e.target)) {
        dropdown.classList.remove('active');
      }
    });
  }

  if (sortSelect) {
    sortSelect.addEventListener('change', (e) => {
      state.sortBy = e.target.value;
      renderProducts();
    });
  }
}

function selectSearchProduct(productId) {
  const dropdown = document.getElementById('search-results-dropdown');
  if (dropdown) dropdown.classList.remove('active');
  openQuickView(productId);
}

function clearSearch() {
  const input = document.getElementById('main-search-input');
  if (input) input.value = '';
  state.search = '';
  const clearBtn = document.getElementById('search-clear-btn');
  if (clearBtn) clearBtn.style.display = 'none';
  renderProducts();
}

/* ================= DELIVERY ZONE SELECTOR ================= */
function initDeliveryZoneSelect() {
  const selects = document.querySelectorAll('.delivery-zone-select');
  selects.forEach(select => {
    select.innerHTML = DELIVERY_ZONES.map(z => `
      <option value="${z.id}" ${state.selectedZone === z.id ? 'selected' : ''}>
        ${z.name} ${z.cost === 0 ? '(Gratis)' : `(+ S/ ${z.cost.toFixed(2)})`}
      </option>
    `).join('');

    select.addEventListener('change', (e) => {
      state.selectedZone = e.target.value;
      updateCartUI();
    });
  });
}

/* ================= COUPON CODE SYSTEM ================= */
function applyCoupon() {
  const input = document.getElementById('coupon-input');
  if (!input) return;

  const code = input.value.trim().toUpperCase();
  if (code === 'MASSJOYERO' || code === 'LAJOYA10') {
    state.couponCode = code;
    state.discountPercent = 10;
    updateCartUI();
    showToast(`¡Cupón "${code}" aplicado! 10% de descuento en tu compra.`);
  } else if (code === 'MASS20') {
    state.couponCode = code;
    state.discountPercent = 20;
    updateCartUI();
    showToast(`¡Cupón "${code}" aplicado! 20% de descuento.`);
  } else {
    showToast('Código de cupón no válido');
  }
}

/* ================= WHATSAPP ORDER GENERATOR ================= */
function checkoutViaWhatsApp() {
  if (state.cart.length === 0) {
    showToast('Tu carrito está vacío, agrega productos primero.');
    return;
  }

  const nameInput = document.getElementById('checkout-name');
  const phoneInput = document.getElementById('checkout-phone');
  const addressInput = document.getElementById('checkout-address');
  const notesInput = document.getElementById('checkout-notes');
  const payMethodInput = document.querySelector('input[name="payMethod"]:checked');

  const name = nameInput ? nameInput.value.trim() : '';
  const phone = phoneInput ? phoneInput.value.trim() : '';
  const address = addressInput ? addressInput.value.trim() : '';
  const notes = notesInput ? notesInput.value.trim() : '';
  const payMethod = payMethodInput ? payMethodInput.value : 'Yape / Plin';

  if (!name) {
    alert('Por favor ingresa tu nombre completo para el pedido.');
    if (nameInput) nameInput.focus();
    return;
  }

  const zone = DELIVERY_ZONES.find(z => z.id === state.selectedZone);
  const subtotal = getCartSubtotal();
  const delivery = getDeliveryCost();
  const discount = getDiscountAmount();
  const total = getCartFinalTotal();
  const orderId = 'MASS-LJ-' + Math.floor(100000 + Math.random() * 900000);
  const now = new Date().toLocaleString('es-PE', { dateStyle: 'short', timeStyle: 'short' });

  // Generate formatted WhatsApp message
  let text = `🛒 *NUEVO PEDIDO - MASS DE LA JOYA*\n`;
  text += `━━━━━━━━━━━━━━━━━━━━━\n`;
  text += `📋 *Pedido N°:* ${orderId}\n`;
  text += `📅 *Fecha:* ${now}\n`;
  text += `👤 *Cliente:* ${name}\n`;
  text += `📞 *Teléfono:* ${phone || 'No especificado'}\n`;
  text += `📍 *Zona:* ${zone ? zone.name : 'La Joya'}\n`;
  if (address) text += `🏠 *Dirección/Referencia:* ${address}\n`;
  text += `💳 *Método de Pago:* ${payMethod}\n`;
  if (notes) text += `📝 *Nota adicional:* ${notes}\n`;
  text += `━━━━━━━━━━━━━━━━━━━━━\n`;
  text += `📦 *DETALLE DE PRODUCTOS:*\n`;

  state.cart.forEach((item, index) => {
    text += `${index + 1}. ${item.name} (${item.unit})\n`;
    text += `   ↳ ${item.quantity} unid. x S/ ${item.price.toFixed(2)} = *S/ ${(item.quantity * item.price).toFixed(2)}*\n`;
  });

  text += `━━━━━━━━━━━━━━━━━━━━━\n`;
  text += `💵 *Subtotal:* S/ ${subtotal.toFixed(2)}\n`;
  if (delivery > 0) {
    text += `🛵 *Delivery (${zone.name}):* S/ ${delivery.toFixed(2)}\n`;
  } else {
    text += `🏢 *Entrega:* Recojo en tienda (Gratis)\n`;
  }
  if (discount > 0) {
    text += `🎟️ *Descuento (${state.couponCode}):* - S/ ${discount.toFixed(2)}\n`;
  }
  text += `💰 *TOTAL A PAGAR:* *S/ ${total.toFixed(2)}*\n`;
  text += `━━━━━━━━━━━━━━━━━━━━━\n`;
  text += `¡Gracias por elegir Mass de La Joya! 💛💙`;

  // Open WhatsApp
  const url = `https://wa.me/${LA_JOYA_WHATSAPP}?text=${encodeURIComponent(text)}`;
  window.open(url, '_blank');

  // Close Cart and show order confirmation ticket
  closeCartDrawer();
  showOrderTicket({
    orderId,
    name,
    phone,
    address,
    zone: zone.name,
    payMethod,
    subtotal,
    delivery,
    discount,
    total,
    now,
    items: [...state.cart]
  });
}

/* ================= ORDER TICKET RECEIPT MODAL ================= */
function showOrderTicket(data) {
  const modalEl = document.getElementById('orderConfirmationModal');
  const ticketContent = document.getElementById('ticket-content-body');

  if (ticketContent) {
    ticketContent.innerHTML = `
      <div class="receipt-ticket text-center mb-4">
        <div class="mass-brand-title fs-3 text-primary mb-1">mass</div>
        <div class="fw-bold text-danger small">SEDE LA JOYA - AREQUIPA</div>
        <div class="text-muted small">Av. Paz Soldán, La Joya, Arequipa</div>
        <div class="text-muted small">RUC: 20608280333</div>
        <div class="my-2 border-top border-bottom border-dark py-1 fw-bold">
          COMPROBANTE DE PEDIDO: ${data.orderId}
        </div>
        <div class="text-start small mb-2">
          <div><strong>Fecha:</strong> ${data.now}</div>
          <div><strong>Cliente:</strong> ${data.name}</div>
          <div><strong>Zona:</strong> ${data.zone}</div>
          <div><strong>Dirección:</strong> ${data.address || 'Recojo en tienda'}</div>
          <div><strong>Pago:</strong> ${data.payMethod}</div>
        </div>
        
        <table class="table table-sm text-start small mb-2">
          <thead>
            <tr>
              <th>Cant.</th>
              <th>Prod.</th>
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            ${data.items.map(it => `
              <tr>
                <td>${it.quantity}</td>
                <td>${it.name}</td>
                <td class="text-end">S/ ${(it.quantity * it.price).toFixed(2)}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>

        <div class="border-top border-dark pt-2 text-end small">
          <div>Subtotal: S/ ${data.subtotal.toFixed(2)}</div>
          <div>Delivery: ${data.delivery === 0 ? 'Gratis' : `S/ ${data.delivery.toFixed(2)}`}</div>
          ${data.discount > 0 ? `<div class="text-danger">Descuento: - S/ ${data.discount.toFixed(2)}</div>` : ''}
          <div class="fs-5 fw-bold text-primary mt-1">TOTAL: S/ ${data.total.toFixed(2)}</div>
        </div>

        <div class="mt-3 small text-muted">
          ¡Gracias por tu compra en Mass de La Joya!<br>
          Atención: Lun-Dom 7am - 10pm
        </div>
      </div>
    `;
  }

  if (window.bootstrap && modalEl) {
    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
  }
}

/* ================= QUICK VIEW PRODUCT MODAL ================= */
function openQuickView(productId) {
  const prod = PRODUCTS.find(p => p.id === productId);
  if (!prod) return;

  state.quickViewProduct = prod;
  const modalEl = document.getElementById('productQuickViewModal');
  const bodyEl = document.getElementById('quick-view-body');

  if (bodyEl) {
    const inCart = state.cart.find(it => it.id === prod.id);
    const currentQty = inCart ? inCart.quantity : 1;

    bodyEl.innerHTML = `
      <div class="row align-items-center">
        <div class="col-md-6 text-center mb-3 mb-md-0">
          <div class="p-3 bg-light rounded-4">
            <img src="${prod.image}" alt="${prod.name}" class="img-fluid" style="max-height: 280px;">
          </div>
        </div>
        <div class="col-md-6">
          ${prod.badge ? `<span class="badge ${prod.badgeColor} mb-2">${prod.badge}</span>` : ''}
          <div class="text-muted small fw-bold text-uppercase">${prod.brand}</div>
          <h3 class="fw-bold text-primary mb-2">${prod.name}</h3>
          <div class="text-muted mb-3">${prod.unit} • Código: <code>${prod.barcode}</code></div>
          
          <div class="d-flex align-items-baseline gap-3 mb-3">
            <span class="fs-2 fw-bold text-primary">S/ ${prod.price.toFixed(2)}</span>
            ${prod.oldPrice ? `<span class="fs-5 text-muted text-decoration-line-through">S/ ${prod.oldPrice.toFixed(2)}</span>` : ''}
          </div>

          <p class="text-secondary small mb-4">${prod.description}</p>

          <div class="d-flex align-items-center gap-2 mb-4">
            <span class="badge bg-success"><i class="fa-solid fa-check"></i> Disponible en Mass La Joya</span>
            <span class="badge bg-warning text-dark"><i class="fa-solid fa-star text-dark"></i> ${prod.rating} (${prod.reviewsCount} opiniones)</span>
          </div>

          <div class="d-flex align-items-center gap-3">
            <div class="qty-control-wrapper" style="width: 140px;">
              <button class="btn-qty" onclick="changeQuickViewQty(-1)">-</button>
              <span class="qty-number" id="quick-view-qty-val">${currentQty}</span>
              <button class="btn-qty" onclick="changeQuickViewQty(1)">+</button>
            </div>
            <button class="btn-mass-pill btn-mass-yellow flex-grow-1" onclick="addQuickViewToCart('${prod.id}')">
              <i class="fa-solid fa-cart-plus"></i> Agregar al Carrito
            </button>
          </div>
        </div>
      </div>
    `;
  }

  if (window.bootstrap && modalEl) {
    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
  }
}

function changeQuickViewQty(delta) {
  const el = document.getElementById('quick-view-qty-val');
  if (!el) return;
  let current = parseInt(el.textContent) || 1;
  current = Math.max(1, current + delta);
  el.textContent = current;
}

function addQuickViewToCart(productId) {
  const el = document.getElementById('quick-view-qty-val');
  const qty = el ? parseInt(el.textContent) || 1 : 1;
  addToCart(productId, qty);

  const modalEl = document.getElementById('productQuickViewModal');
  if (window.bootstrap && modalEl) {
    const bsModal = bootstrap.Modal.getInstance(modalEl);
    if (bsModal) bsModal.hide();
  }
}

/* ================= ENCARTE DIGITAL FLYER VIEWER ================= */
function openEncarteModal(page = 1) {
  state.encartePage = page;
  updateEncarteViewer();
  const modalEl = document.getElementById('encarteViewerModal');
  if (window.bootstrap && modalEl) {
    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
  }
}

function changeEncartePage(delta) {
  state.encartePage += delta;
  if (state.encartePage < 1) state.encartePage = 1;
  if (state.encartePage > state.totalEncartePages) state.encartePage = state.totalEncartePages;
  updateEncarteViewer();
}

function updateEncarteViewer() {
  const imgEl = document.getElementById('encarte-page-image');
  const labelEl = document.getElementById('encarte-page-counter');
  const prevBtn = document.getElementById('encarte-prev-btn');
  const nextBtn = document.getElementById('encarte-next-btn');

  if (imgEl) {
    imgEl.src = `assets/img/encarte-page-${state.encartePage}.svg`;
  }
  if (labelEl) {
    labelEl.textContent = `Página ${state.encartePage} de ${state.totalEncartePages}`;
  }
  if (prevBtn) prevBtn.disabled = (state.encartePage === 1);
  if (nextBtn) nextBtn.disabled = (state.encartePage === state.totalEncartePages);
}

/* ================= LEAFLET MAP OF LA JOYA ================= */
function initLeafletMap() {
  const mapContainer = document.getElementById('map-la-joya');
  if (!mapContainer || typeof L === 'undefined') return;

  // Coordinates for Tienda Mass La Joya (-16.4256223, -71.8212998)
  const laJoyaCoords = [-16.4256223, -71.8212998];
  const map = L.map('map-la-joya').setView(laJoyaCoords, 16);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap contributors | Tiendas Mass'
  }).addTo(map);

  // Custom Icon for Mass
  const massIcon = L.divIcon({
    className: 'custom-mass-marker',
    html: `
      <div style="background:#FFD100; color:#082EB7; border:3px solid #082EB7; border-radius:50%; width:44px; height:44px; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
        mass
      </div>
    `,
    iconSize: [44, 44],
    iconAnchor: [22, 44]
  });

  const marker = L.marker(laJoyaCoords, { icon: massIcon }).addTo(map);
  marker.bindPopup(`
    <div style="text-align:center; padding:5px;">
      <h6 style="color:#082EB7; font-weight:bold; margin-bottom:4px;">Tienda Mass La Joya</h6>
      <p style="margin-bottom:6px; font-size:12px;">Av. Paz Soldán, La Joya, Arequipa</p>
      <div style="background:#082EB7; color:#FFD100; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:bold; display:inline-block;">
        Abierto Lun - Dom: 7:00 AM - 10:00 PM
      </div>
      <div style="margin-top:8px;">
        <a href="https://maps.app.goo.gl/L1dAmB6dL8NKLfm97" target="_blank" style="color:#082EB7; font-weight:bold; font-size:12px;">
          <i class="fa-solid fa-diamond-turn-right"></i> Cómo llegar en Google Maps
        </a>
      </div>
    </div>
  `).openPopup();
}

/* ================= LIBRO DE RECLAMACIONES ================= */
function submitReclamation(e) {
  e.preventDefault();
  const form = document.getElementById('reclamaciones-form');
  if (!form) return;

  const claimCode = 'REC-LJ-' + Math.floor(100000 + Math.random() * 900000);
  alert(`¡Tu reclamo ha sido registrado con éxito!\nCódigo de Hoja de Reclamación: ${claimCode}\nNos comunicaremos contigo en un plazo máximo de 15 días hábiles.`);
  
  form.reset();
  const modalEl = document.getElementById('libroReclamacionesModal');
  if (window.bootstrap && modalEl) {
    const bsModal = bootstrap.Modal.getInstance(modalEl);
    if (bsModal) bsModal.hide();
  }
}

/* ================= TRABAJA CON NOSOTROS ================= */
function submitJobApplication(e) {
  e.preventDefault();
  alert('¡Gracias por postular a la Fuerza Amarilla de Mass La Joya! Nuestro equipo de RRHH revisará tus datos para las convocatorias activas.');
  const form = document.getElementById('trabaja-form');
  if (form) form.reset();
}

/* ================= OFRECE TU LOCAL ================= */
function submitLocalOffer(e) {
  e.preventDefault();
  alert('¡Gracias por ofrecer tu local en La Joya! Nuestro equipo de Expansión se pondrá en contacto contigo si cumple con las dimensiones requeridas.');
  const form = document.getElementById('ofrece-local-form');
  if (form) form.reset();
}

/* ================= TESTIMONIALS ================= */
function initTestimonials() {
  const container = document.getElementById('testimonials-container');
  if (!container) return;

  container.innerHTML = TESTIMONIALS.map(t => `
    <div class="col-md-4 mb-4">
      <div class="p-4 bg-white rounded-4 shadow-sm border h-100 d-flex flex-direction-column">
        <div class="text-warning mb-2">
          ${'★'.repeat(t.rating)}
        </div>
        <p class="text-secondary small fst-italic flex-grow-1">"${t.comment}"</p>
        <div class="border-top pt-2 mt-2">
          <h6 class="fw-bold text-primary mb-0">${t.name}</h6>
          <span class="text-muted small">${t.zone} • ${t.date}</span>
        </div>
      </div>
    </div>
  `).join('');
}

/* ================= TOAST NOTIFICATION ================= */
function showToast(message) {
  let container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    container.setAttribute('role', 'status');
    container.setAttribute('aria-live', 'polite');
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = 'mass-toast';
  toast.innerHTML = `
    <i class="fa-solid fa-circle-check text-warning"></i>
    <span>${message}</span>
  `;

  container.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, 2500);
}

/* ================= GLOBAL LISTENERS ================= */
function initEventListeners() {
  // ESC key closes cart drawer
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeCartDrawer();
    }
  });
}
