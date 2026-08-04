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
