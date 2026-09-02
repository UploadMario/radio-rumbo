# Auditoría Inicial SEO Y Accesibilidad

La Fase 3.0 documenta deuda técnica de SEO y accesibilidad sin ejecutar todavía una reescritura visual completa.

## SEO Pendiente

- Revisar canonical final cuando el dominio definitivo esté configurado en `APP_URL`.
- Completar Open Graph con imagen final de alta calidad si el cliente entrega material oficial.
- Actualizar `public/sitemap.xml` con el dominio definitivo cuando el cliente confirme `APP_URL` de producción.
- Ampliar structured data cuando se confirme información institucional adicional.

## Accesibilidad Pendiente

- Revisar `alt` de imágenes informativas.
- Añadir `aria-live` en estados dinámicos del reproductor cuando se rediseñe.
- Revisar navegación por teclado en componentes interactivos.
- Estados de foco consistentes.
- Soporte `prefers-reduced-motion` en futuros efectos.
- Verificación de contraste después del Visual System.

## Implementado En Fase 3.1

- Meta description por página.
- Canonical basado en `APP_URL`.
- Open Graph y Twitter cards básicos.
- Favicon SVG.
- `robots.txt`.
- Sitemap básico en `/sitemap.xml`.
- Structured data básico `RadioStation`.
- Skip link, focus visible y `aria-live` en reproductor.
- `prefers-reduced-motion` en CSS/JS.

## Correcciones Permitidas En Esta Fase

Solo ajustes triviales que no cambien diseño ni comportamiento público. El rediseño visual queda para Radio Rumbo 3.1.
