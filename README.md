# 🛒 Mass de La Joya - Tienda Web Oficial Hard Discount (Astro + Tailwind)

Tienda web interactiva y moderna para **"Mass de La Joya"** (Arequipa, Perú), inspirada en la estética, paleta de colores (amarillo `#FFD100`, azul `#082EB7`, rojo `#E30613`), catálogo y funcionalidades de [Tiendas Mass Perú](https://www.tiendasmass.com.pe/).

Migrada a [Astro](https://astro.build) con **Tailwind CSS v4** como sistema de diseño.

---

## 🌟 Características Principales

- 🏷️ Identidad de marca Mass: logotipo, estilo *hard discount* y precios bajos (`PRECIO MASS`, `2x1`, `OFERTÓN`).
- 🥫 Catálogo completo (58+ productos) en `js/products.js`.
- 🛒 Carrito interactivo con `localStorage`, cupones de descuento y **pedido por WhatsApp**.
- 📖 Encarte Digital Quincenal interactivo (3 páginas).
- 📍 Ubícanos en La Joya con mapa OpenStreetMap.
- 👥 Fuerza Amarilla (postulación laboral), Ofrece tu Local y Libro de Reclamaciones.
- 🧭 Mejoras de UX: scroll suave con scrollspy, botón volver-arriba, tira de beneficios, toast accesible, feedback táctil y soporte para *safe areas* (iPhone).
- 🎨 Paleta unificada Mass: los componentes de Bootstrap (`text-primary`, `bg-warning`, botones…) sobreescriben sus variables para usar los colores de marca, no los por defecto.

---

## 🚀 Cómo Ejecutar Localmente

### Requisitos previos

| Herramienta | Versión | Cómo obtenerla |
| --- | --- | --- |
| **Node.js** | 22.12 o superior | <https://nodejs.org> (instalador `.msi` en Windows, `.pkg` en macOS) |
| **pnpm** | cualquiera | `npm install -g pnpm` |

> ¿No quieres usar `pnpm`? Todos los comandos de abajo funcionan igual con `npm`: `npm install`, `npm run dev`, `npm run build`, `npm run preview`.

### Paso 1 · Descargar el proyecto

```bash
git clone https://github.com/Jeremy-Sarmiento/mass-la-joya.git
cd mass-la-joya
```

### Paso 2 · Instalar las dependencias

```bash
pnpm install
```

### Paso 3 · Arrancar el sitio

```bash
pnpm dev
```

Abre <http://localhost:4321> en tu navegador. Los cambios en el código se aplican automáticamente, no hace falta repetir el comando.

### Paso 4 · Otros comandos

| Comando | Qué hace |
| --- | --- |
| `pnpm dev` | Servidor de desarrollo con recarga automática en `http://localhost:4321` |
| `pnpm build` | Genera el sitio estático de producción en `./dist/` |
| `pnpm preview` | Sirve localmente el resultado de `pnpm build` para revisarlo |

> ⚠️ **Importante si publicas el sitio desde Apache u otro servidor web:** lo que se publica es el contenido de `dist/`, no `src/`. Ejecuta `pnpm build` después de cada cambio para que se refleje.

### Administrar el servidor de desarrollo

Astro 7 lo deja corriendo en segundo plano, así que la terminal queda libre. Para controlarlo:

```bash
pnpm astro dev status   # Ver si está activo y en qué puerto
pnpm astro dev logs     # Ver la consola del servidor
pnpm astro dev stop     # Detenerlo
```

### Problemas frecuentes

**"El puerto 4321 está ocupado"** — arranca en otro puerto:

```bash
pnpm dev --port 4322
```

**"pnpm: command not found"** — falta instalarlo: `npm install -g pnpm`.

**"La página se ve sin estilos"** — no abras `dist/index.html` con doble clic. Ese archivo no incluye el CSS compilado; usa `pnpm dev` mientras programas y `pnpm build` solo para generar la versión final.

## 📊 Meta Pixel (Facebook)

El sitio mide visitas con el **Meta Pixel**, que **no carga hasta que el visitante acepta las cookies**.

### Configuración

En desarrollo, copia `.env.example` como `.env` y pon tu ID real:

```bash
cp .env.example .env
# luego edita .env y rellena PUBLIC_META_PIXEL_ID
```

El ID se encuentra en **Meta Business Suite → Configuración del negocio → Eventos → Píxel de Facebook**. Sin este valor el píxel no se carga y el aviso de cookies no aparece, así que el sitio funciona igual.

Como `.env` está en `.gitignore`, el ID real nunca se sube al repositorio: en GitHub Pages llega como secreto de Actions.

### Consentimiento

La elección del visitante se guarda en `localStorage`, bajo la clave `mass-cookie-consent`:

| Estado | Aviso | Píxel |
| --- | --- | --- |
| Sin definir | Visible | Apagado |
| `accepted` | Oculto | Carga al entrar |
| `rejected` | Oculto | Apagado |

El enlace **"Configurar cookies"** del pie de página reabre el aviso para poder cambiar de opinión.

### Eventos enviados

| Evento | Cuándo se dispara |
| --- | --- |
| `PageView` | Al cargar la página, si se aceptaron las cookies |
| `ViewContent` | Al abrir la vista rápida de un producto |
| `AddToCart` | Al agregar un producto al carrito |
| `InitiateCheckout` | Al generar el pedido de WhatsApp |
| `Purchase` | Al enviar el pedido (al abrir WhatsApp) |

> ⚠️ **Dos advertencias sobre `Purchase`:** el checkout ocurre dentro de WhatsApp, así que este evento se dispara al abrir el chat, no cuando el cliente confirma. Es probable que Meta cuente pedidos que nunca se completen. Para un conteo real de ventas habría que disparar `Purchase` desde un webhook del lado del servidor.

> 🔒 **Privacidad:** nunca se envían al píxel el nombre, teléfono, dirección ni método de pago del cliente. Meta prohíbe recibir datos personales por el Pixel y puede suspender la cuenta publicitaria. El parámetro `order_id` es un código interno generado en el navegador (`MASS-LJ-XXXXXX`), no un dato del cliente.

### Comprobar que funciona

1. Abre el sitio en una ventana de incógnito.
2. Acepta las cookies.
3. En la consola del navegador deberías ver `fbq` definido: `typeof fbq === 'function'`.
4. En **Meta Events Manager** confirma que los eventos llegan.

Para verificar que el píxel **no** se carga sin consentimiento, revisa la pestaña *Network* antes de aceptar: no debe haber ninguna petición a `connect.facebook.net`.

## 📁 Estructura del Proyecto

```text
tiendamass/
├── src/
│   ├── layouts/
│   │   └── Layout.astro        # <head> y estructura base del sitio
│   ├── pages/
│   │   └── index.astro         # Página principal (Body completo)
│   └── styles/
│       └── global.css          # Paleta --mass-*, componentes y modo oscuro (CSS propio)
├── public/
│   ├── assets/                 # Imágenes, banners y folletos
│   └── js/
│       ├── products.js         # Catálogo de productos, zonas y categorías
│       └── app.js              # Carrito, filtros, checkout, mapa y encarte
├── astro.config.mjs            # Plugin tailwindcss() de @tailwindcss/vite (ver "Arquitectura CSS")
└── package.json
```

## 🧱 Arquitectura CSS

El diseño visual **no usa Tailwind**, aunque el paquete esté instalado. Conviene saber por qué, para no buscar clases que no existen.

### Cómo funciona hoy

| Pieza | Origen | Aporta |
| --- | --- | --- |
| `src/styles/global.css` | CSS propio, ~2.000 líneas | Paleta `--mass-*`, botones, tarjetas, modales, modo oscuro |
| Bootstrap 5.3.3 | CDN en el `<head>` | Rejilla, utilidades (`flex`, `gap-2`, `text-secondary`, `rounded-pill`…) y modales |
| Tailwind v4 | `@tailwindcss/vite` en `astro.config.mjs` | **Nada**: `global.css` nunca lo importa |

`global.css` no contiene ningún `@import`, `@layer`, `@theme`, `@source`, `@tailwind` ni `@apply`. Por eso el plugin de Vite no emite CSS: el bundle compilado es solo el archivo propio minificado, sin variables `--tw-*` ni utilidades de Tailwind. Si añades una clase como `text-2xl` al HTML, **no tendrá estilo** y no fallará con error, que es la parte confusa.

Si en el futuro quieres usar Tailwind de verdad, el punto de partida es añadir `@import 'tailwindcss';` al principio de `global.css` y revisar el orden de la cascada respecto a Bootstrap.

### Por qué está instalado si no se usa

Quedó de la migración inicial. Se puede quitar sin riesgo: en `package.json` están `tailwindcss` y `@tailwindcss/vite`, y en `astro.config.mjs` el `plugins: [tailwindcss()]`. Ninguna otra pieza depende de ellos.

### Detalles de la integración

- **Orden en el `<head>`**: el CSS de Bootstrap se carga antes que el bundle de Astro, así que los overrides de marca van **sin capa al final** de `global.css` para ganar la cascada.
- **Overrides de Bootstrap**: en `:root` se redefinen `--bs-primary`, `--bs-warning`, `--bs-danger`, `--bs-info`, `--bs-secondary` (y sus `--bs-*-rgb`) hacia la paleta Mass.
- **Modo oscuro**: se aplica con `data-theme` en `<html>` y bloques `html[data-theme="dark"]` al final de `global.css`, uno por componente.
- **Scripts clásicos**: `products.js` y `app.js` viven en `public/` y se cargan como scripts normales, para que las funciones globales usadas en `onclick` sigan funcionando.
- **Rutas relativas**: los assets se referencian sin barra inicial (`assets/img/…`) para que el sitio funcione igual en la raíz de un dominio o dentro de un subdirectorio. Así publica en Apache y en GitHub Pages sin ajustes.

## 🚀 Despliegue en GitHub Pages

Cada `push` a `main` dispara `.github/workflows/deploy.yml`, que compila el sitio y lo publica en:

**<https://jeremy-sarmiento.github.io/mass-la-joya/>**

El flujo pide permiso una sola vez (`Settings → Pages → Source → GitHub Actions`) y a partir de ahí cada push se despliega solo.

El **Meta Pixel** se inyecta en el build mediante el secreto `PUBLIC_META_PIXEL_ID` de GitHub Actions, para que el ID real no quede escrito en el repositorio. Créalo en `Settings → Secrets and variables → Actions`. Si añades variables nuevas, decláralas también como secretos en el workflow.

### La variable `SITE_BASE`

El sitio se publica en un subdirectorio, así que Astro necesita saber cuál es. Se define con la variable `SITE_BASE`:

| Destino | Comando |
| --- | --- |
| GitHub Pages | `SITE_BASE=/mass-la-joya/ pnpm build` |
| Apache en `/tiendamass/dist/` | `SITE_BASE=/tiendamass/dist/ pnpm build` |
| Desarrollo local | *(sin definir)* — el dev server sirve en la raíz |

El workflow ya la define sola. Si publicas en otro servidor, ajústala antes de compilar: sin ella, el CSS y los assets generados por Astro apuntan a la raíz del dominio y dan error 404.

Los assets de `public/` (imágenes, scripts, catálogo) se referencian con rutas relativas y funcionan en cualquier subdirectorio sin configurar nada.

## 💡 Notas

- Respaldos del sitio vanilla disponibles en `/tmp/opencode/tiendamass-backup`.