# Visual System Fase 3.1

Radio Rumbo usa un sistema visual modular sin React.

## Tokens

Los tokens viven en `src/styles/tokens.css`:

- Colores de marca, fondo, superficie, texto y estados.
- Radios `sm` a `pill`.
- Sombras `xs` a `glow`.
- Espaciado `space-1` a `space-10`.
- Escala tipográfica.
- Transiciones.

## CSS

La entrada pública es `src/styles/main.css`, que importa:

- base, layout, tipografía y utilidades;
- componentes: botones, cards, header, footer, player, forms, badges;
- páginas: home, en vivo, noticias, programación, equipo y publicidad.

`legacy-public.css` conserva la capa estable anterior mientras el sistema visual migra progresivamente a componentes más pequeños.

## JavaScript

La entrada pública es `src/scripts/main.js`:

- `navigation.js`
- `motion.js`
- `media.js`
- `player.js`

La entrada admin es `src/scripts/admin.js`.

## Motion

Se usa `motion` sobre DOM, no Motion for React. Las animaciones son moderadas y se desactivan con `prefers-reduced-motion: reduce`.

## Gradients

La Fase 3.1 usa gradientes CSS y pseudo-elementos como fallback ligero. No se implementa WebGL/shader real para evitar peso innecesario antes de validar el Visual System.
