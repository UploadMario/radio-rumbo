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
- Usar datos mock centralizados en `config/mock-data.php`.
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
- No conectar contenido visual de Fase 1 a MySQL todavia.
- No implementar autenticacion ni CRUDs reales todavia.

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
- Mensajes recientes mock

## Modulos futuros

- Podcasts
- Ranking Top 20
- La Radio que se ve
- Estadisticas avanzadas
- Gestion avanzada de usuarios
- Banners
- Avisos urgentes
