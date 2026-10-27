<?php
/**
 * Layout principal.
 * Variables: $title, $description, $canonical, $content, $pixelId, $jsData, $flashes.
 */
if (!defined('MASS_APP')) {
    http_response_code(404);
    exit;
}

$pageTitle = $title ?? 'Mass de La Joya';
$pageDescription = $description ?? 'Tienda Mass de La Joya, Arequipa.';
$pageCanonical = $canonical ?? absolute_url();
$pixelId = (string) ($pixelId ?? '');
$pixelEnabled = $pixelId !== '';
$flashes = $flashes ?? [];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="generator" content="PHP <?= e(PHP_VERSION) ?> · Mass MVC">
    <title><?= e($pageTitle) ?></title>

    <!-- Canonical: evita contenido duplicado (p. ej. /index.php vs /) -->
    <link rel="canonical" href="<?= e($pageCanonical) ?>">

    <!-- Color de la interfaz del navegador (barra móvil) y esquema nativo -->
    <meta name="theme-color" content="#FFD100" id="mass-theme-color">
    <meta name="color-scheme" content="light dark">

    <!-- Tema (claro/oscuro) antes de pintar -->
    <script>
        (function () {
            function applyTheme(theme, persist) {
                var dark = theme === 'dark';
                document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
                document.documentElement.style.colorScheme = dark ? 'dark' : 'light';

                var meta = document.getElementById('mass-theme-color');
                if (meta) meta.setAttribute('content', dark ? '#0B1322' : '#FFD100');

                if (persist) {
                    try {
                        localStorage.setItem('mass-theme', dark ? 'dark' : 'light');
                    } catch (e) {}
                }

                if (typeof window.massSyncThemeUI === 'function') {
                    window.massSyncThemeUI(dark);
                }
            }

            window.massApplyTheme = applyTheme;

            try {
                var stored = localStorage.getItem('mass-theme');
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                applyTheme(stored || (prefersDark ? 'dark' : 'light'), false);
            } catch (e) {
                applyTheme('light', false);
            }
        })();
    </script>

    <!-- SEO & OpenGraph Meta Tags -->
    <meta name="description" content="<?= e($pageDescription) ?>">
    <meta name="keywords" content="Tiendas Mass, Mass La Joya, Supermercado La Joya, Abarrotes La Joya Arequipa, Delivery La Joya, Precios Bajos">
    <meta property="og:title" content="Mass de La Joya | Lleva Mass pagando Menos">
    <meta property="og:description" content="Ahorra todos los días en tu canasta básica con Tiendas Mass en La Joya, Arequipa.">
    <meta property="og:image" content="<?= e(absolute_url('assets/img/og-cover.png')) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="og:url" content="<?= e($pageCanonical) ?>">
    <meta property="og:type" content="website">

    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="<?= e(asset('img/mass_logo.webp')) ?>">

    <!-- Google Fonts: Montserrat & Fredoka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700;800&family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- FontAwesome 6.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

    <!-- CSS propio de Mass (sin Tailwind ni build de por medio) -->
    <link rel="stylesheet" href="<?= e(asset('css/global.css')) ?>">

    <!-- Meta Pixel (Facebook). Solo se inyecta si el visitante acepta las cookies. -->
    <script>
        window.MASS_PIXEL_ID = <?= json_for_js($pixelId) ?>;
        window.MASS_PIXEL_ENABLED = <?= $pixelEnabled ? 'true' : 'false' ?>;
        window.MASS_CONSENT_KEY = 'mass-cookie-consent';

        window.loadMetaPixel = function () {
            if (!window.MASS_PIXEL_ENABLED || window._fbq) return;
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s);
            }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
            window.fbq('init', window.MASS_PIXEL_ID);
            window.fbq('track', 'PageView');
        };

        window.massGetConsent = function () {
            try {
                return localStorage.getItem(window.MASS_CONSENT_KEY);
            } catch (e) {
                return null;
            }
        };

        window.massHideBanner = function () {
            var banner = document.getElementById('cookie-banner');
            if (banner) banner.classList.remove('is-visible');
        };

        window.massSetConsent = function (choice) {
            try {
                localStorage.setItem(window.MASS_CONSENT_KEY, choice);
            } catch (e) {}
            window.massHideBanner();
            if (choice === 'accepted') window.loadMetaPixel();
        };

        window.massOpenConsentSettings = function () {
            var banner = document.getElementById('cookie-banner');
            if (banner) banner.classList.add('is-visible');
        };

        // Si ya aceptó en una visita anterior, el pixel carga sin volver a preguntar.
        if (window.massGetConsent() === 'accepted') {
            window.loadMetaPixel();
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (window.MASS_PIXEL_ENABLED && !window.massGetConsent()) {
                window.massOpenConsentSettings();
            }
        });
    </script>
</head>
<body>
    <a class="skip-link" href="#contenido-principal">Saltar al contenido principal</a>

    <?= $content ?>

    <!-- Aviso de consentimiento de cookies (Ley N.° 29733) -->
    <div id="cookie-banner" class="cookie-banner" role="dialog" aria-labelledby="cookie-banner-title" aria-live="polite">
        <div class="cookie-banner-inner">
            <p id="cookie-banner-title" class="cookie-banner-title">
                <i class="fa-solid fa-cookie-bite" aria-hidden="true"></i>
                <span>Usamos cookies para medir el tráfico del sitio</span>
            </p>
            <p class="cookie-banner-text">
                Usamos cookies de terceros (Meta Pixel) para entender cómo se usa la tienda y mejorar el contenido.
                Puedes aceptarlas o quedarte solo con las necesarias. Consulta nuestra
                <a href="javascript:void(0)" onclick="openModal('myModalPrivacidad')">Política de Privacidad</a>.
            </p>
            <div class="cookie-banner-actions">
                <button type="button" class="btn btn-sm btn-mass-pill btn-mass-blue" onclick="massSetConsent('rejected')">Solo las necesarias</button>
                <button type="button" class="btn btn-sm btn-mass-pill btn-mass-yellow" onclick="massSetConsent('accepted')">Aceptar todas</button>
            </div>
        </div>
    </div>

    <!-- Mensajes flash del servidor (formularios sin JavaScript) -->
    <?php if ($flashes !== []): ?>
        <div class="toast-container" role="status" aria-live="polite">
            <?php foreach ($flashes as $flash): ?>
                <div class="mass-toast" data-flash>
                    <i class="fa-solid <?= ($flash['type'] ?? '') === 'success' ? 'fa-circle-check text-warning' : 'fa-circle-exclamation text-danger' ?>"></i>
                    <span><?= e($flash['message'] ?? '') ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Datos del servidor para la capa interactiva (carrito, filtros, modales) -->
    <script>
        window.MASS_DATA = <?= json_for_js((object) ($jsData ?? [])) ?>;
    </script>

    <!-- Helper for opening general modals -->
    <script>
        function openModal(modalId) {
            const el = document.getElementById(modalId);
            if (el && window.bootstrap) {
                const modal = new bootstrap.Modal(el);
                modal.show();
            }
        }

        function setThemeIcon(dark) {
            const btn = document.getElementById('themeToggleBtn');
            if (!btn) return;
            const icon = btn.querySelector('i');
            if (icon) icon.className = dark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            btn.setAttribute('aria-pressed', dark ? 'true' : 'false');
            btn.setAttribute('aria-label', dark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro');
            btn.title = dark ? 'Modo claro' : 'Modo oscuro';
        }

        window.massSyncThemeUI = setThemeIcon;

        function toggleTheme() {
            const root = document.documentElement;
            const dark = root.getAttribute('data-theme') !== 'dark';

            root.classList.add('theme-transition');
            if (typeof window.massApplyTheme === 'function') {
                window.massApplyTheme(dark ? 'dark' : 'light', true);
            } else {
                root.setAttribute('data-theme', dark ? 'dark' : 'light');
            }
            setThemeIcon(dark);

            window.setTimeout(() => root.classList.remove('theme-transition'), 380);
        }

        setThemeIcon(document.documentElement.getAttribute('data-theme') === 'dark');

        // Si el visitante no eligió un tema, sigue los cambios del sistema.
        (function () {
            if (!window.matchMedia) return;
            const query = window.matchMedia('(prefers-color-scheme: dark)');
            const onChange = (event) => {
                let stored = null;
                try {
                    stored = localStorage.getItem('mass-theme');
                } catch (e) {}
                if (stored) return;

                const root = document.documentElement;
                root.classList.add('theme-transition');
                if (typeof window.massApplyTheme === 'function') {
                    window.massApplyTheme(event.matches ? 'dark' : 'light', false);
                }
                setThemeIcon(event.matches);
                window.setTimeout(() => root.classList.remove('theme-transition'), 380);
            };

            if (typeof query.addEventListener === 'function') {
                query.addEventListener('change', onChange);
            } else if (typeof query.addListener === 'function') {
                query.addListener(onChange);
            }
        })();
    </script>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Carrito, filtros, checkout, mapa y encarte -->
    <script src="<?= e(asset('js/app.js')) ?>"></script>

    <!-- UI/UX: Scrollspy + Back to top + auto-cierre de flashes -->
    <script>
        (function () {
            const sectionIds = ['inicio', 'seccion-ofertas', 'seccion-catalogo', 'seccion-ubicame', 'seccion-fuerza-amarilla', 'seccion-ofrece-local'];
            const navLinks = document.querySelectorAll('.nav-link-item[href^="#"]');
            const mobileNavItems = document.querySelectorAll('.mobile-nav-item[href^="#"]');

            function setActive(id) {
                navLinks.forEach((a) => a.classList.toggle('active', (a.getAttribute('href') || '').slice(1) === id));
                mobileNavItems.forEach((a) => a.classList.toggle('active', (a.getAttribute('href') || '').slice(1) === id));
            }

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(
                    (entries) => entries.forEach((e) => { if (e.isIntersecting) setActive(e.target.id); }),
                    { rootMargin: '-40% 0px -55% 0px' }
                );
                sectionIds.forEach((id) => {
                    const el = document.getElementById(id);
                    if (el) observer.observe(el);
                });
            }

            const topBtn = document.getElementById('scrollTopBtn');
            if (topBtn) {
                const toggleTopBtn = () => {
                    const y = window.scrollY || document.documentElement.scrollTop;
                    topBtn.classList.toggle('show', y > 500);
                };
                window.addEventListener('scroll', toggleTopBtn, { passive: true });
                toggleTopBtn();
                topBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
            }

            document.querySelectorAll('[data-flash]').forEach((toast) => {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.3s ease';
                    setTimeout(() => toast.remove(), 300);
                }, 4500);
            });
        })();
    </script>
</body>
</html>
