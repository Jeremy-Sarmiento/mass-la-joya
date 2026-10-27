# 🛒 Mass de La Joya — Tienda Web Oficial (PHP + MVC)

Tienda web interactiva de **Mass de La Joya** (Arequipa, Perú), migrada de Astro a **PHP 8 con arquitectura MVC** y sin dependencias externas de backend. Conserva el diseño, la paleta de marca (amarillo `#FFD100`, azul `#082EB7`, rojo `#E30613`), el catálogo de 58 productos y toda la interacción del carrito.

---

## 🌟 Características

- 🧱 **MVC propio**: front controller, router, controladores, modelos, vistas y capa de almacenamiento.
- 🏷️ Identidad de marca Mass: logotipo, estilo *hard discount* y precios bajos (`PRECIO MASS`, `2x1`, `OFERTÓN`).
- 🥫 Catálogo de **58 productos** servido desde PHP, con filtros y ordenamiento.
- 🛒 Carrito interactivo con `localStorage`, cupones y **pedido por WhatsApp**.
- 💾 **Pedidos, reclamos, postulaciones y ofertas de local guardados en el servidor** (no solo un `alert`).
- 👤 **Cuentas de cliente**: registro, inicio de sesión con contraseña cifrada, sesión persistente e historial de pedidos.
- ✅ Validación de datos en el servidor, CSRF, escapado de HTML y recálculo de precios/totales.
- 📖 Encarte Digital Quincenal interactivo (3 páginas).
- 📍 Ubícanos en La Joya con mapa OpenStreetMap.
- 👥 Fuerza Amarilla, Ofrece tu Local y Libro de Reclamaciones Virtual.
- 🧭 UX: scroll suave con scrollspy, botón volver-arriba, tira de beneficios, toasts, feedback táctil y *safe areas*.
- ♿ Accesibilidad: enlace de *skip*, landmark `<main>`, buscador con ARIA combobox y teclado navegable, carrito con diálogo accesible, foco gestionado, filtros activos con chips, estados vacíos y movimientos reducidos.
- 📱 UX móvil: barra de resumen de carrito fija en móvil, navegación inferior, bottom sheet, toasts contextuales.
- 🎨 Paleta unificada Mass sobre Bootstrap 5.3 + CSS propio.
- 🌙 **Modo oscuro mejorado**: persistente, sincronizado con el sistema, transiciones suaves, contraste alto y componentes nativos oscuros.

---

## 🚀 Requisitos y ejecución

| Herramienta | Versión |
| --- | --- |
| **PHP** | 8.1 o superior (probado con 8.5) |
| Extensiones PHP | `mbstring`, `json`, `session` (vienen por defecto) |
| Servidor web | Apache con `mod_php`, Nginx + PHP-FPM, o el servidor embebido de PHP |

No hace falta Node, Composer ni base de datos.

### Arrancar el sitio

```bash
cd mass-la-joya
php -S localhost:8000 -t .
```

Abre <http://localhost:8000/> en el navegador.

> En este servidor el proyecto también funciona en un subdirectorio, por ejemplo
> `http://localhost/mass-la-joya/index.php`. Todas las rutas internas se calculan
> a partir de la ubicación de `index.php`, así que no hay que configurar nada.
>
> `index.html` solo redirige a `index.php` para servidores Apache cuya
> `DirectoryIndex` no incluye PHP. Nunca contiene lógica de la aplicación.

### Pruebas

```bash
php tests/smoke.php   # catálogo, pedidos, validaciones y formularios
find app config tests index.php -name '*.php' -print0 | xargs -0 -n1 php -l
```

Las pruebas se ejecutan también en cada `push` mediante `.github/workflows/php.yml`.

---

## 🧭 Cómo funciona el MVC

```text
Petición HTTP
   └─ index.php ................ front controller
        └─ App\Core\Router ..... resuelve la ruta
             └─ Controller .... recibe la petición
                  ├─ Model .... catálogo, pedidos, formularios
                  └─ View ..... HTML + layout
```

### Rutas principales

| Método | Ruta | Destino |
| --- | --- | --- |
| `GET` | `/` o `?r=home` | `HomeController@index` |
| `GET` | `?r=home&categoria=lacteos&q=leche&orden=price-asc` | Catálogo filtrado y ordenado en el servidor |
| `GET` | `?r=api/health` | Estado de la aplicación (JSON) |
| `POST` | `?r=api/orders` | Registrar pedido del carrito |
| `POST` | `?r=api/claims` | Libro de Reclamaciones |
| `POST` | `?r=api/job-applications` | Fuerza Amarilla |
| `POST` | `?r=api/local-offers` | Oferta de local |
| `POST` | `?r=api/auth/login` | Iniciar sesión |
| `POST` | `?r=api/auth/register` | Crear cuenta de cliente |
| `POST` | `?r=api/auth/logout` | Cerrar sesión |
| `GET` | `?r=api/auth/account` | Panel de cuenta actualizado (JSON) |

El router usa `index.php?r=...` para funcionar en cualquier subdirectorio sin
`mod_rewrite`. También reconoce `PATH_INFO` si tu servidor lo prefiere.

Los endpoints detectan si la petición viene de `fetch()` (`Accept: application/json`)
y responden JSON; si es un formulario HTML normal, guardan el registro y
redirigen con un mensaje flash. Es decir, los formularios funcionan incluso sin
JavaScript.

---

## 📁 Estructura del proyecto

```text
mass-la-joya/
├── index.php                  # Front controller
├── index.html                 # Redirección para Apache (sin lógica)
├── config/
│   └── config.php             # Configuración (lee .env)
├── app/
│   ├── bootstrap.php          # Autoload, entorno, sesión y errores
│   ├── Core/
│   │   ├── Router.php
│   │   ├── Controller.php
│   │   ├── Request.php
│   │   ├── View.php
│   │   ├── Validator.php
│   │   └── ValidationException.php
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── AuthController.php # Login, registro y panel de cliente
│   │   └── ApiController.php
│   ├── Models/
│   │   ├── Product.php        # Catálogo
│   │   ├── Category.php       # Categorías y contadores
│   │   ├── DeliveryZone.php   # Zonas y costo de delivery
│   │   ├── Coupon.php         # Cupones
│   │   ├── Testimonial.php
│   │   ├── User.php           # Cuentas y hash de contraseñas
│   │   ├── Order.php          # Pedido + recálculo de totales
│   │   ├── Claim.php          # Libro de reclamaciones
│   │   ├── JobApplication.php
│   │   ├── LocalOffer.php
│   │   └── JsonRecord.php     # Base de persistencia
│   ├── Services/
│   │   ├── Auth.php           # Sesión, intentos y regeneración de ID
│   │   └── Storage.php        # Archivos JSON con bloqueo y guarda PHP
│   ├── Data/
│   │   ├── products.json
│   │   ├── categories.json
│   │   ├── delivery_zones.json
│   │   ├── testimonials.json
│   │   ├── coupons.json
│   │   └── store.php
│   ├── Support/
│   │   ├── helpers.php        # e(), url(), money(), csrf...
│   │   └── Env.php            # Lector de .env
│   └── Views/
│       ├── layouts/main.php
│       ├── pages/home.php
│       └── partials/*.php     # Header, hero, catálogo, modales...
├── assets/
│   ├── css/global.css
│   ├── js/app.js
│   ├── img/
│   └── products/
├── storage/                   # Datos generados (no versionados)
├── tests/smoke.php
└── .github/workflows/php.yml
```

---

## 💾 Datos y persistencia

El catálogo vive en `app/Data/*.json` y los registros generados por los usuarios
en `storage/`:

| Archivo | Contenido |
| --- | --- |
| `storage/orders.php` | Pedidos del carrito |
| `storage/claims.php` | Hojas de reclamación |
| `storage/job_applications.php` | Postulaciones |
| `storage/local_offers.php` | Ofertas de locales |
| `storage/users.php` | Cuentas de cliente (contraseñas con `password_hash`) |

Cada archivo empieza con una guarda `<?php http_response_code(403); exit; ?>`,
así que pedirlo directamente devuelve **403** aunque el servidor no respete
`.htaccess`. La escritura usa `flock()` para evitar corrupciones por peticiones
simultáneas.

No se necesita base de datos. Si más adelante quieres usar MySQL, basta con
reemplazar `JsonRecord`/`Storage` por un modelo PDO: los controladores y las
vistas no dependen del formato de almacenamiento.

### Pedidos: el servidor manda

El navegador solo envía `id` y `cantidad`. `Order::place()` busca cada producto
en el catálogo, recalcula subtotal, delivery y descuento, y guarda el pedido
rechazando:

- carritos vacíos o cantidades fuera de rango (1–99),
- productos inexistentes o agotados,
- zonas de entrega o métodos de pago inválidos,
- cupones inexistentes,
- más de 200 unidades o 30 productos distintos.

Después, el navegador abre WhatsApp con el resumen. El pedido queda registrado
en el servidor con el mismo código mostrado al cliente (`MASS-LJ-XXXXXX`).

### Cuentas de cliente

- El registro pide nombre, correo, teléfono (opcional) y contraseña de al menos
  8 caracteres.
- La contraseña se guarda con `password_hash()` y nunca se devuelve a la vista
  ni a la API.
- El inicio de sesión usa `password_verify()`, renueva el ID de sesión para
  evitar *session fixation* y limita a **5 intentos fallidos cada 5 minutos**.
- Los pedidos realizados con sesión iniciada se guardan con `user_id` y se
  muestran en **Mi cuenta → Mis pedidos**.
- Si el cliente no recuerda su contraseña, el panel ofrece contacto por
  WhatsApp; no hay envío de correos porque el proyecto no usa servicios externos.

---

## 🎟️ Cupones disponibles

| Código | Descuento |
| --- | --- |
| `MASSJOYERO` | 10 % |
| `LAJOYA10` | 10 % |
| `MASS20` | 20 % |

Para añadir uno nuevo, edita `app/Data/coupons.json`. El servidor lo valida al
registrar el pedido.

---

## 📊 Meta Pixel (Facebook)

El píxel **no se carga hasta que el visitante acepta las cookies**. La elección
se guarda en `localStorage` bajo `mass-cookie-consent` y el enlace
**Configurar cookies** del pie permite cambiarla.

### Configuración

```bash
cp .env.example .env
# edita .env y rellena META_PIXEL_ID
```

También se acepta el nombre anterior `PUBLIC_META_PIXEL_ID`, para no romper
despliegues existentes. Sin valor, el píxel y el aviso de cookies no aparecen.

Eventos enviados: `PageView`, `ViewContent`, `AddToCart`, `InitiateCheckout` y
`Purchase`. Nunca se envían nombre, teléfono, dirección ni método de pago.

> ⚠️ `Purchase` se dispara al abrir WhatsApp, no cuando el cliente confirma el
> pedido. Para un conteo exacto habría que dispararlo desde un webhook del
> servidor.

---

## 🌐 Despliegue en Apache

1. Copia el proyecto al servidor (por ejemplo `/var/www/html/mass-la-joya`).
2. Asegura que `storage/` tenga permiso de escritura para el usuario de Apache:
   ```bash
   chown -R www-data:www-data storage
   chmod -R 775 storage
   ```
3. Confirma que `DirectoryIndex` incluye `index.php` (en la mayoría de
   instalaciones ya es así). Si no, `index.html` redirige a `index.php`.
4. Opcional: define la URL pública en `.env`:
   ```dotenv
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://tu-dominio.com/mass-la-joya
   META_PIXEL_ID=tu_id
   ```

No se publica ninguna carpeta `dist/`: el sitio se sirve directamente desde la
raíz del proyecto. **GitHub Pages ya no es compatible** porque no ejecuta PHP;
el workflow antiguo se sustituyó por uno de integración continua
(`.github/workflows/php.yml`).

### Si ves un listado de archivos

El servidor no está usando `index.php` como índice. Abre
`https://tu-dominio/mass-la-joya/index.php` o añade `index.php` a
`DirectoryIndex` en la configuración de Apache (`httpd.conf` o el vhost).

---

## 🔄 Migración desde Astro (mapa rápido)

| Antes (Astro) | Ahora (PHP MVC) |
| --- | --- |
| `src/pages/index.astro` | `app/Views/pages/home.php` + `app/Views/partials/*` |
| `src/layouts/Layout.astro` | `app/Views/layouts/main.php` |
| `src/styles/global.css` | `assets/css/global.css` |
| `public/js/products.js` | `app/Data/products.json` + `App\Models\Product` |
| `public/js/app.js` | `assets/js/app.js` (datos inyectados por PHP) |
| `public/assets/*` | `assets/*` |
| `pnpm build` | No hay build: se sirve PHP directamente |
| Formularios con `alert()` | Endpoints PHP con validación y persistencia |
| Carrito solo local | Carrito local + pedido validado en el servidor |

El historial de Git conserva la versión anterior por si necesitas comparar.

---

## 🔒 Notas de seguridad

- Todas las salidas de datos dinámicos usan `e()` (`htmlspecialchars`).
- Los formularios incluyen token CSRF de sesión; los endpoints lo validan con
  `hash_equals()`.
- `storage/` y `app/` incluyen `.htaccess` de denegación; además los datos
  generados están protegidos con una guarda PHP.
- Los precios y totales **nunca** se aceptan desde el cliente.
- Las contraseñas se guardan con `password_hash()` y se comparan con
  `password_verify()`; nunca se muestran ni se registran en claro.
- El ID de sesión se regenera al iniciar y cerrar sesión; los intentos fallidos
  de acceso están limitados por sesión.
- `user_id` de un pedido se toma siempre de la sesión, nunca del navegador.
- En producción mantén `APP_DEBUG=false` y `APP_ENV=production`.

---

## 💡 Notas

- Datos institucionales (dirección, teléfono, horarios, redes) en
  `app/Data/store.php`.
- Categorías y productos en `app/Data/`.
- Si cambias los assets, usa rutas relativas (`assets/...`) para que el sitio
  siga funcionando en la raíz o en un subdirectorio.
