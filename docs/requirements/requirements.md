# Requisitos Iniciales

## Requisitos funcionales

- Mostrar informacion publica de Radio Rumbo.
- Preparar transmision en vivo configurable.
- Publicar noticias.
- Mostrar programacion.
- Mostrar equipo.
- Recibir mensajes de contacto y publicidad.
- Preparar acceso administrativo seguro.
- Registrar auditoria de acciones administrativas futuras.

## Alcance Fase 1

- Crear GUI publica moderna para `/`, `/en-vivo/`, `/programacion/`, `/noticias/`, `/podcasts/`, `/equipo/` y `/publicidad-contacto/`.
- Crear reproductor inferior flotante visual.
- Crear dashboard administrativo visual en `/admin/`.
- Crear login administrativo visual en `/admin/?view=login`.
- Usar contenido base centralizado en `config/default-content.php`.
- Mantener formularios, botones y estados como preparacion visual, sin comportamiento real.

## Alcance Fase 1.1

- Integrar el logo real de Radio Rumbo en header, hero, footer, reproductor y vistas administrativas.
- Usar como contenido base confirmado: `Radio Rumbo`, `La radio que se ve` y `Huancayo, Perú`.
- Corregir textos visibles en español y mantener placeholders claros para datos no confirmados.
- Refinar el reproductor flotante como componente visual principal, sin conectarlo a stream real.
- Reforzar módulos planificados con etiquetas profesionales.

## Alcance Fase 2.0

- Conexión real a MySQL mediante PDO.
- Login administrativo funcional.
- Sesiones seguras con regeneración de ID al iniciar sesión.
- Logout administrativo.
- Dashboard protegido.
- Roles limitados a `Super Admin` y `Admin`.
- Configuración general básica desde `settings` con fallback.
- CRUD mínimo de noticias.
- Página pública de noticias leyendo solo registros `published`.
- CSRF y validación servidor en formularios administrativos.

## Alcance Fase 2.0.1

- Crear usuarios locales de desarrollo con script seguro e idempotente.
- Configurar audio en vivo real por `STREAM_AUDIO_URL`.
- Configurar widget de video en vivo por `LIVE_VIDEO_WIDGET_URL`.
- Configurar página oficial de Facebook y plugin de página.
- Mostrar estados de medios en dashboard.
- Mantener todos los embeds como configuración externa validada por HTTPS.
- No implementar podcasts, ranking, uploads, métricas avanzadas ni nuevos roles.

## Requisitos no funcionales

- Compatible con PHP 8.1+ y MySQL.
- Arquitectura MVC simple y mantenible.
- Seguridad basica desde la primera fase.
- Documentacion clara para continuidad.
- Dependencias minimas.
- Compatible con hosting compartido DonWeb.

## Restricciones tecnicas

- No usar Laravel, Symfony ni WordPress.
- No exigir Node.js, React, Docker ni WebSockets.
- No asumir procesos permanentes.
- No crear roles adicionales.
- No usar datos reales inventados.
- Mantener autenticacion, sesiones, CSRF y roles actuales sin relajar seguridad.
- Timezone configurable con fallback `America/Lima`.
- Sesiones administrativas con timeout y login con proteccion ante intentos repetidos.
- Logout administrativo mediante `POST` + CSRF.

## Modulos fase 1

- Home / En Vivo
- Reproductor flotante
- Noticias
- Programacion
- Publicidad y Contacto
- Equipo
- Login administrativo
- Dashboard administrativo basico

## Modulos visuales Fase 1 en preparacion

- Podcasts
- Login administrativo visual
- Dashboard administrativo visual
- Publicidad y banners visuales
- Mensajes recientes de referencia administrativa

## Modulos futuros

- Podcasts
- Ranking Top 20
- La Radio que se ve
- Estadisticas avanzadas
- Gestion avanzada de usuarios
- Banners
- Avisos urgentes

## Alcance Fase 2.1

- CRUD administrativo de programacion en `/admin/programs/`.
- Programacion publica dinamica en `/programacion/`.
- CRUD administrativo de equipo en `/admin/team/`, sin uploads reales.
- Equipo publico dinamico en `/equipo/`.
- Formulario de publicidad/contacto con CSRF, validacion y honeypot en `/publicidad-contacto/`.
- Bandeja de mensajes en `/admin/messages/` para revisar, marcar como leido y archivar.
- Dashboard con conteos reales basicos.
- No se implementan correos salientes, podcasts funcionales, ranking, banners avanzados, avisos urgentes ni nuevos roles.

## Alcance Fase 2.2

- Configuracion editable desde `/admin/settings/` usando tabla `settings`.
- `Super Admin` puede modificar configuracion critica; `Admin` solo puede verla.
- CRUD basico de banners en `/admin/banners/`.
- Home muestra banners activos vigentes sin romper si no existen.
- CRUD de avisos urgentes en `/admin/urgent-notices/`.
- Home y En Vivo pueden mostrar avisos activos mediante franja superior global.
- No hay uploads reales: `image_path` usa ruta manual, placeholder o URL HTTPS.
- Podcasts, ranking, usuarios avanzados, estadisticas y correos salientes siguen pendientes.

## Alcance Fase 2.6

- Pulir GUI/UX global para presentacion ejecutiva.
- Priorizar Home, En Vivo, Noticias, Programacion, Equipo y Publicidad/Contacto.
- Agregar mensajes profesionales para contenido pendiente de confirmacion oficial.
- Mejorar dashboard con estado de presentacion, modulos implementados y pendientes antes del despliegue final.
- Reducir protagonismo de modulos planificados: Podcasts, Ranking Top 20, Estadisticas avanzadas, Usuarios avanzados y Uploads.
- No implementar uploads, correos salientes, ranking, podcasts funcionales, estadisticas avanzadas ni roles nuevos.

## Ajuste Facebook Page Plugin

- `/noticias/` debe mostrar publicaciones recientes mediante el Page Plugin oficial de Facebook dentro de la web.
- Si la URL completa del plugin esta vacia, debe construirse automaticamente desde la pagina oficial `https://www.facebook.com/radiorumboperu`.
- Todos los enlaces externos de Facebook deben validarse como HTTPS.
- Si Facebook bloquea el iframe por navegador, cookies, bloqueadores o politicas externas, la interfaz debe mostrar fallback profesional sin parecer un error del sistema.

## Contenido oficial Radio Rumbo

- Mostrar lema `La radio que se ve`.
- Mostrar frecuencias `91.1 FM` y `1560 AM`.
- Mostrar ubicacion `Jr. Ayacucho 297, Huancayo, Junin, Peru`.
- Mostrar telefono comercial `964436961`.
- Mostrar redes oficiales recibidas: TikTok, Instagram, YouTube y Facebook, siempre con URLs HTTPS.
- No publicar programacion, conductores, equipo, banners ni correo institucional como definitivos hasta confirmacion administrativa.
