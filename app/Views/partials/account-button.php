<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
<?php $user = $user ?? null; ?>
<?php if (is_array($user)): ?>
	  <button type="button" class="btn-mass-pill btn-mass-blue account-btn" onclick="openAccountModal()" aria-label="Mi cuenta: <?= e($user['name'] ?? '') ?>">
	    <span class="account-avatar" aria-hidden="true"><?= e($user['initial'] ?? 'M') ?></span>
	    <span class="d-none d-lg-inline account-btn-label"><?= e($user['first_name'] ?? 'Mi cuenta') ?></span>
	  </button>
<?php else: ?>
	  <button type="button" class="btn-mass-pill btn-mass-blue account-btn" onclick="openAccountModal('login')" aria-label="Iniciar sesión">
	    <i class="fa-regular fa-user" aria-hidden="true"></i>
	    <span class="d-none d-lg-inline account-btn-label">Ingresar</span>
	  </button>
<?php endif; ?>
