<?php
/**
 * Panel de cuenta: formularios de acceso o resumen del cliente.
 *
 * Variables: $user (array|null), $orders (array), $csrfToken, $whatsapp.
 */
if (!defined('MASS_APP')) {
    http_response_code(404);
    exit;
}

$user = $user ?? null;
$orders = is_array($orders ?? null) ? $orders : [];
$csrfToken = $csrfToken ?? csrf_token();
$whatsapp = $whatsapp ?? (string) config('whatsapp');
$whatsappHelp = 'https://wa.me/' . rawurlencode((string) $whatsapp) . '?text=' . rawurlencode('Hola, necesito ayuda con mi cuenta de Mass de La Joya.');
?>
<?php if (!is_array($user)): ?>
	  <div class="account-panel">
	    <div class="account-hero">
	      <span class="account-hero-icon" aria-hidden="true"><i class="fa-solid fa-user-shield"></i></span>
	      <h4 class="account-hero-title">Tu cuenta Mass</h4>
	      <p class="account-hero-text">Compra más rápido, guarda tus datos de contacto y revisa tus pedidos en un solo lugar.</p>
	    </div>

	    <div class="account-tabs" role="tablist" aria-label="Acceso a tu cuenta">
	      <button type="button" class="account-tab is-active" id="account-tab-login" data-account-tab="login" role="tab" aria-selected="true" aria-controls="account-pane-login">
	        <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i> Iniciar sesión
	      </button>
	      <button type="button" class="account-tab" id="account-tab-register" data-account-tab="register" role="tab" aria-selected="false" aria-controls="account-pane-register">
	        <i class="fa-solid fa-user-plus" aria-hidden="true"></i> Crear cuenta
	      </button>
	    </div>

	    <div class="account-pane is-active" id="account-pane-login" data-account-pane="login" role="tabpanel" aria-labelledby="account-tab-login">
	      <form class="account-form" action="<?= e(url('api/auth/login')) ?>" method="post" data-auth-form="login">
	        <input type="hidden" name="_token" value="<?= e($csrfToken) ?>">

	        <div class="account-form-alert" data-auth-alert role="alert" hidden></div>

	        <div class="mb-3">
	          <label class="form-label fw-bold small" for="account-login-email">Correo electrónico</label>
	          <input type="email" class="form-control" id="account-login-email" name="email" autocomplete="email" inputmode="email" placeholder="tucorreo@ejemplo.com" required>
	          <div class="account-field-error" data-field-error="email"></div>
	        </div>

	        <div class="mb-3">
	          <label class="form-label fw-bold small" for="account-login-password">Contraseña</label>
	          <div class="account-password-wrap">
	            <input type="password" class="form-control" id="account-login-password" name="password" autocomplete="current-password" placeholder="Tu contraseña" required>
	            <button type="button" class="account-password-toggle" data-password-toggle="account-login-password" aria-label="Mostrar contraseña" aria-pressed="false">
	              <i class="fa-regular fa-eye" aria-hidden="true"></i>
	            </button>
	          </div>
	          <div class="account-field-error" data-field-error="password"></div>
	        </div>

	        <button type="submit" class="btn-mass-pill btn-mass-blue account-submit">
	          <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i> Entrar a mi cuenta
	        </button>

	        <p class="account-help mb-0">
	          ¿Olvidaste tu contraseña o tienes problemas para entrar?
	          <a href="<?= e($whatsappHelp) ?>" target="_blank" rel="noopener">Escríbenos por WhatsApp</a>.
	        </p>
	      </form>
	    </div>

	    <div class="account-pane" id="account-pane-register" data-account-pane="register" role="tabpanel" aria-labelledby="account-tab-register" hidden>
	      <form class="account-form" action="<?= e(url('api/auth/register')) ?>" method="post" data-auth-form="register">
	        <input type="hidden" name="_token" value="<?= e($csrfToken) ?>">

	        <div class="account-form-alert" data-auth-alert role="alert" hidden></div>

	        <div class="row g-3">
	          <div class="col-12">
	            <label class="form-label fw-bold small" for="account-register-name">Nombre completo</label>
	            <input type="text" class="form-control" id="account-register-name" name="name" autocomplete="name" placeholder="Ej: Carmen Rojas" minlength="2" maxlength="80" required>
	            <div class="account-field-error" data-field-error="name"></div>
	          </div>

	          <div class="col-12 col-sm-6">
	            <label class="form-label fw-bold small" for="account-register-email">Correo electrónico</label>
	            <input type="email" class="form-control" id="account-register-email" name="email" autocomplete="email" inputmode="email" placeholder="tucorreo@ejemplo.com" required>
	            <div class="account-field-error" data-field-error="email"></div>
	          </div>

	          <div class="col-12 col-sm-6">
	            <label class="form-label fw-bold small" for="account-register-phone">Teléfono / Celular <span class="text-muted fw-normal">(opcional)</span></label>
	            <input type="tel" class="form-control" id="account-register-phone" name="phone" autocomplete="tel" inputmode="tel" placeholder="Ej: 987654321">
	            <div class="account-field-error" data-field-error="phone"></div>
	          </div>

	          <div class="col-12 col-sm-6">
	            <label class="form-label fw-bold small" for="account-register-password">Contraseña</label>
	            <div class="account-password-wrap">
	              <input type="password" class="form-control" id="account-register-password" name="password" autocomplete="new-password" placeholder="Mínimo 8 caracteres" minlength="8" maxlength="72" required>
	              <button type="button" class="account-password-toggle" data-password-toggle="account-register-password" aria-label="Mostrar contraseña" aria-pressed="false">
	                <i class="fa-regular fa-eye" aria-hidden="true"></i>
	              </button>
	            </div>
	            <div class="account-field-error" data-field-error="password"></div>
	          </div>

	          <div class="col-12 col-sm-6">
	            <label class="form-label fw-bold small" for="account-register-password-confirmation">Repite la contraseña</label>
	            <div class="account-password-wrap">
	              <input type="password" class="form-control" id="account-register-password-confirmation" name="password_confirmation" autocomplete="new-password" placeholder="Confirma tu contraseña" minlength="8" maxlength="72" required>
	              <button type="button" class="account-password-toggle" data-password-toggle="account-register-password-confirmation" aria-label="Mostrar contraseña" aria-pressed="false">
	                <i class="fa-regular fa-eye" aria-hidden="true"></i>
	              </button>
	            </div>
	            <div class="account-field-error" data-field-error="password_confirmation"></div>
	          </div>
	        </div>

	        <div class="form-check account-terms mt-3">
	          <input class="form-check-input" type="checkbox" value="1" id="account-register-terms" name="terms" required>
	          <label class="form-check-label small" for="account-register-terms">
	            Acepto la <a href="javascript:void(0)" onclick="openPrivacyFromAccount()">política de privacidad</a> y el tratamiento de mis datos para gestionar mi cuenta y mis pedidos.
	          </label>
	          <div class="account-field-error" data-field-error="terms"></div>
	        </div>

	        <button type="submit" class="btn-mass-pill btn-mass-blue account-submit mt-3">
	          <i class="fa-solid fa-user-plus" aria-hidden="true"></i> Crear mi cuenta
	        </button>
	      </form>
	    </div>

	    <div class="account-perks" aria-label="Beneficios de tener cuenta">
	      <span><i class="fa-solid fa-bolt" aria-hidden="true"></i> Checkout más rápido</span>
	      <span><i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i> Historial de pedidos</span>
	      <span><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Datos protegidos</span>
	    </div>
	  </div>
<?php else: ?>
	  <?php
	  $memberSince = '';
	  $createdAt = (string) ($user['created_at'] ?? '');
	  if ($createdAt !== '') {
	      $timestamp = strtotime($createdAt);
	      $memberSince = $timestamp !== false ? date('m/Y', $timestamp) : '';
	  }
	  ?>
	  <div class="account-panel">
	    <div class="account-profile">
	      <span class="account-avatar account-avatar-lg" aria-hidden="true"><?= e($user['initial'] ?? 'M') ?></span>
	      <div class="account-profile-info">
	        <span class="account-eyebrow">Hola de nuevo</span>
	        <h4 class="account-profile-name"><?= e($user['name'] ?? '') ?></h4>
	        <p class="account-profile-meta mb-0">
	          <i class="fa-regular fa-envelope" aria-hidden="true"></i> <?= e($user['email'] ?? '') ?>
	          <?php if (!empty($user['phone'])): ?>
	            <span class="account-meta-sep" aria-hidden="true">·</span>
	            <i class="fa-solid fa-phone" aria-hidden="true"></i> <?= e($user['phone']) ?>
	          <?php endif; ?>
	        </p>
	      </div>
	      <span class="account-badge"><i class="fa-solid fa-star" aria-hidden="true"></i> Cliente Mass</span>
	    </div>

	    <div class="account-stats">
	      <div class="account-stat">
	        <span class="account-stat-value"><?= e((string) count($orders)) ?></span>
	        <span class="account-stat-label">Pedido<?= count($orders) === 1 ? '' : 's' ?> registrado<?= count($orders) === 1 ? '' : 's' ?></span>
	      </div>
	      <div class="account-stat">
	        <span class="account-stat-value"><?= e($memberSince !== '' ? $memberSince : '—') ?></span>
	        <span class="account-stat-label">Miembro desde</span>
	      </div>
	      <div class="account-stat account-stat-highlight">
	        <span class="account-stat-value"><i class="fa-solid fa-truck-fast" aria-hidden="true"></i></span>
	        <span class="account-stat-label">Delivery en La Joya</span>
	      </div>
	    </div>

	    <div class="account-orders">
	      <div class="account-orders-head">
	        <h5 class="mb-0"><i class="fa-solid fa-bag-shopping" aria-hidden="true"></i> Mis pedidos</h5>
	        <button type="button" class="account-link-btn" onclick="closeAccountAndGoCatalog()">Ver catálogo</button>
	      </div>

	      <?php if ($orders === []): ?>
	        <div class="account-empty">
	          <i class="fa-solid fa-receipt" aria-hidden="true"></i>
	          <p class="mb-1">Aún no tienes pedidos con tu cuenta.</p>
	          <span>Los pedidos que hagas mientras tengas la sesión iniciada aparecerán aquí.</span>
	        </div>
	      <?php else: ?>
	        <div class="account-order-list">
	          <?php foreach ($orders as $order): ?>
	            <?php
	            $orderId = (string) ($order['id'] ?? '');
	            $status = (string) ($order['status'] ?? 'pendiente');
	            $statusLabels = [
	                'pendiente' => 'Pendiente',
	                'confirmado' => 'Confirmado',
	                'en camino' => 'En camino',
	                'entregado' => 'Entregado',
	                'cancelado' => 'Cancelado',
	            ];
	            $statusLabel = $statusLabels[$status] ?? ucfirst($status);
	            $statusClass = match ($status) {
	                'entregado' => 'is-success',
	                'cancelado' => 'is-danger',
	                'confirmado', 'en camino' => 'is-info',
	                default => 'is-warning',
	            };
	            $createdLocal = (string) ($order['created_at_local'] ?? '');
	            $createdTimestamp = $createdLocal !== '' ? strtotime($createdLocal) : false;
	            $dateLabel = $createdTimestamp !== false ? date('d/m/Y H:i', $createdTimestamp) : '—';
	            $itemCount = 0;
	            foreach ((array) ($order['items'] ?? []) as $item) {
	                $itemCount += (int) ($item['quantity'] ?? 0);
	            }
	            ?>
	            <article class="account-order">
	              <div class="account-order-main">
	                <div class="account-order-title">
	                  <strong><?= e($orderId) ?></strong>
	                  <span class="account-status <?= e($statusClass) ?>"><?= e($statusLabel) ?></span>
	                </div>
	                <div class="account-order-meta">
	                  <span><i class="fa-regular fa-calendar" aria-hidden="true"></i> <?= e($dateLabel) ?></span>
	                  <span><i class="fa-solid fa-box" aria-hidden="true"></i> <?= e((string) $itemCount) ?> producto<?= $itemCount === 1 ? '' : 's' ?></span>
	                  <span><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <?= e((string) ($order['zone']['name'] ?? 'La Joya')) ?></span>
	                </div>
	              </div>
	              <div class="account-order-total">
	                <span>Total</span>
	                <strong><?= e(money((float) ($order['total'] ?? 0))) ?></strong>
	              </div>
	            </article>
	          <?php endforeach; ?>
	        </div>
	      <?php endif; ?>
	    </div>

	    <div class="account-actions">
	      <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-dismiss="modal">
	        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Seguir comprando
	      </button>
	      <form action="<?= e(url('api/auth/logout')) ?>" method="post" data-auth-form="logout">
	        <input type="hidden" name="_token" value="<?= e($csrfToken) ?>">
	        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
	          <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Cerrar sesión
	        </button>
	      </form>
	    </div>
	  </div>
<?php endif; ?>
