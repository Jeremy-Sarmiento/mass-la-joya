// @ts-check
import { defineConfig } from 'astro/config';

import tailwindcss from '@tailwindcss/vite';

// https://astro.build/config
export default defineConfig({
  // URL canonica. GitHub Pages sirve este proyecto en /mass-la-joya/.
  // La barra final importa: sin ella, new URL() descarta el ultimo segmento.
  site: 'https://jeremy-sarmiento.github.io/mass-la-joya/',

  // Prefijo de los assets que genera Astro (el bundle CSS, por ejemplo).
  // Los assets de public/ se referencian con rutas relativas y funcionan en
  // cualquier subdirectorio, pero este valor si es necesario:
  //   - GitHub Pages: SITE_BASE=/mass-la-joya/
  //   - Apache en /tiendamass/: SITE_BASE=/tiendamass/
  //   - developing: sin definir, el dev server sirve en la raiz
  base: process.env.SITE_BASE || undefined,

  devToolbar: {
    enabled: false
  },
  vite: {
    plugins: [tailwindcss()]
  }
});