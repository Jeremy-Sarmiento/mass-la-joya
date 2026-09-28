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

## 🚀 Cómo Ejecutar

Usa `pnpm` (proyecto en mono-repo con workspace):

```bash
pnpm install
pnpm dev          # Dev server en http://localhost:4321 (usar `pnpm astro -- dev --background` en servidores)
pnpm build        # Build de producción en ./dist/
pnpm preview      # Previsualizar el build
```

## 📁 Estructura del Proyecto

```text
tiendamass/
├── src/
│   ├── layouts/
│   │   └── Layout.astro        # <head> y estructura base del sitio
│   ├── pages/
│   │   └── index.astro         # Página principal (Body completo)
│   └── styles/
│       └── global.css          # Sistema de diseño Tailwind v4 (@theme + componentes)
├── public/
│   ├── assets/                 # Imágenes, banners y folletos
│   └── js/
│       ├── products.js         # Catálogo de productos, zonas y categorías
│       └── app.js              # Carrito, filtros, checkout, mapa y encarte
├── astro.config.mjs            # Plugin tailwindcss() de @tailwindcss/vite
└── package.json
```

## 💡 Notas de la migración e integración

- **Bootstrap + Tailwind sin conflicto**: los componentes de Tailwind viven en capas (`layer(theme)`, `layer(utilities)`) con `source(none)`, que impide que Tailwind escanee el HTML y genere utilidades que pisen clases de Bootstrap (`.col-*`, `.container`, `.py-5`…). Solo se emiten las utilidades usadas vía `@apply`.
- **Orden en el `<head>`**: el CSS de Bootstrap se carga antes del bundle de Astro/Tailwind; los overrides de marca y reglas propias van **sin capa al final** de `global.css` para ganar la cascada.
- **Overrides de Bootstrap**: en `:root` se redefinen `--bs-primary`, `--bs-warning`, `--bs-danger`, `--bs-info`, `--bs-secondary` (y sus `--bs-*-rgb`) hacia la paleta Mass.
- **Scripts clásicos**: `products.js` y `app.js` se cargan como scripts (`is:inline`) para que las funciones globales usadas en `onclick` sigan funcionando igual que en el HTML original.
- **Tailwind sin preflight**: no se aplica reset, se respeta el estilo base de Bootstrap/los componentes.
- Respaldos del sitio vanilla disponibles en `/tmp/opencode/tiendamass-backup`.