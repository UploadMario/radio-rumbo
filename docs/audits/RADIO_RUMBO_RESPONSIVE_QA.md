# Radio Rumbo — Auditoría responsive, UX/UI y QA

Fecha: 2026-10-15

## Alcance

Se revisaron los layouts públicos y administrativos, las vistas PHP, la
arquitectura Vite, los estilos heredados y modernos, los formularios, tablas,
sidebar, login y scripts de interacción. La intervención mantiene la lógica
PHP/MySQL, autenticación, CSRF, roles, rutas y contratos de formularios.

## Vistas inventariadas

### Público

- `/` — `resources/views/public/home.php`
- `/en-vivo/` — `resources/views/public/live.php`
- `/programacion/` — `resources/views/public/schedule.php`
- `/noticias/` — `resources/views/public/news.php`
- `/equipo/` — `resources/views/public/team.php`
- `/publicidad-contacto/` — `resources/views/public/advertising-contact.php`
- Layout y componentes: `resources/views/layouts/public.php`,
  `resources/views/partials/header.php`, `footer.php`,
  `floating-player.php` y `facebook-feed.php`.

### Administrativo

- `/admin/login/`
- `/admin/dashboard/`
- `/admin/news/`
- `/admin/programs/`
- `/admin/team/`
- `/admin/messages/`
- `/admin/settings/`
- `/admin/banners/`
- `/admin/urgent-notices/`

El layout administrativo es `resources/views/layouts/admin.php`; la navegación
compartida está en `resources/views/partials/admin-sidebar.php`.

## Problemas corregidos

1. El menú administrativo mostraba estados técnicos y enlaces `#` para módulos
   no operativos. Se retiraron del sidebar para evitar controles muertos.
2. El login tenía texto de fase de desarrollo y un enlace de recuperación que
   no ejecutaba ninguna acción. Se eliminaron ambos.
3. El campo de contraseña carecía de control de visibilidad y podía perder
   legibilidad en autofill o pantallas estrechas. Se añadió un control accesible
   y estilos explícitos de contraste, ancho y foco.
4. El panel móvil no tenía una navegación compacta clara. Se añadió un drawer
   con `aria-expanded`, `aria-controls` y cierre al seleccionar una ruta.
5. Las tablas administrativas no estaban protegidas contra anchos mínimos en
   pantallas pequeñas. Se añadió desplazamiento horizontal contenido dentro del
   panel, sin ocultar acciones.
6. Formularios y botones administrativos heredaban estilos públicos de forma
   ambigua. Se añadió una capa `admin-system.css` con tokens existentes,
   estados de foco, superficies, botones y controles legibles.

## Componentes normalizados

- Sidebar y navegación móvil.
- Login y campo de contraseña.
- Inputs, selects, textareas y estados de foco.
- Tablas y acciones de fila.
- Paneles, tarjetas de resumen y alertas.
- Perfil y encabezado administrativo.

## Matriz responsive

| Área | 320–430 px | 768 px | 1024 px o más |
|---|---|---|---|
| Login | Card fluida, campos a ancho completo | Card centrada | Card centrada con espacio estable |
| Sidebar | Drawer lateral | Drawer lateral | Sidebar fija |
| Dashboard | Una columna | Dos columnas cuando cabe | Grid completo |
| Tablas | Scroll dentro del panel | Scroll solo si es necesario | Tabla completa |
| Formularios | Campos apilados | Campos fluidos | Grid existente |

Esta matriz describe las reglas implementadas en CSS; no sustituye una
verificación visual en cada dispositivo físico.

## Accesibilidad aplicada

- Labels asociados a los campos del login.
- `aria-controls` y `aria-expanded` en el menú móvil.
- `aria-pressed` en mostrar/ocultar contraseña.
- Foco visible conservado y reforzado.
- Contraste explícito para texto, inputs y botón principal.
- Soporte de `prefers-reduced-motion` para transiciones del panel.
- Navegación semántica mediante `nav`, `main`, `aside`, tablas y formularios.

## Verificación técnica

- `composer test`: OK, 75 tests y 118 assertions.
- `composer analyse`: OK, sin errores PHPStan.
- `composer lint`: OK.
- `npm run build`: OK, bundle administrativo generado.

## Limitaciones

No se ejecutó una auditoría visual automatizada con navegador en esta sesión.
Debe validarse manualmente el login, dashboard y CRUD en 375, 768, 1024,
1366 y 1440 px, incluyendo teclado, autofill, foco y desplazamiento de tablas.
No se modificaron autenticación, autorización, base de datos ni operaciones
CRUD.
