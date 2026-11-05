
# Radio Rumbo — Instrucciones para agentes

## Misión y alcance
- Desarrollar una plataforma web segura, mantenible y administrable por el personal autorizado de Radio Rumbo, sin necesidad de modificar código.
- Priorizar autonomía del cliente, estabilidad, seguridad, accesibilidad y facilidad de uso sobre complejidad técnica o estética.
- No inventar datos institucionales. Distinguir contenido verificado, provisional y fallbacks neutrales; priorizar la información administrada desde la BD.
- Mantener únicamente los roles `Super Admin` y `Admin`. No implementar funciones no autorizadas ni la API de la futura aplicación móvil.

## Arquitectura y herramientas
- Mantener el monolito modular PHP MVC server-rendered, con controladores, servicios, modelos, validadores, middleware y vistas separados.
- Conservar PDO, consultas preparadas, MariaDB, Composer, CSS modular propio, JavaScript y Vite. No introducir frameworks, SPA, microservicios ni dependencias innecesarias sin autorización.
- Mantener `public/` como document root; no exponer código interno, configuración, pruebas, SQL ni secretos.
- PHP 8.4 es el objetivo de migración, pendiente de validar compatibilidad, extensiones y disponibilidad en DonWeb. No elevar automáticamente los requisitos de Composer.
- Evitar hardcode, duplicación, abstracciones prematuras y refactorizaciones fuera del alcance solicitado.
- Ejecutar localmente con `php -S localhost:8000 -t public`; comprobar rutas y comandos reales antes de modificarlos.

## Administración y seguridad
- Cada operación administrativa debe verificar autorización en servidor, CSRF cuando cambie estado, validación, persistencia y manejo seguro de errores.
- Mantener sesiones seguras, hash de contraseñas, escape contextual, rate limiting y mínimo privilegio. Logout mediante `POST + CSRF`.
- Respetar `APP_TIMEZONE`, `ADMIN_SESSION_TIMEOUT`, `LOGIN_MAX_ATTEMPTS` y `LOGIN_WINDOW_SECONDS`.
- Conservar `ProgramScheduleService` como fuente de programación; utilizar `LoggerService` y `AuditLogService` con información sanitizada, sin interrumpir indebidamente operaciones principales.
- Mantener `storage/logs/` fuera de `public/`. No registrar ni exponer secretos, cookies, tokens, SQL, trazas ni datos sensibles innecesarios.
- Tratar uploads y edición enriquecida como capacidades pendientes: exigir validación de extensión, MIME, tamaño, rutas, almacenamiento seguro, bloqueo de ejecución y sanitización antes de habilitarlos.
- Favorecer archivado lógico, confirmaciones de operaciones destructivas y mensajes comprensibles para administradores no técnicos.

## Datos y configuración
- Separar estrictamente `radio_rumbo` (desarrollo), `radio_rumbo_e2e` (pruebas) y producción.
- No crear, reconstruir, limpiar ni modificar bases existentes sin autorización explícita y específica. Nunca ejecutar pruebas destructivas contra desarrollo o producción.
- No versionar `.env`, credenciales ni datos privados. Usar configuración externa y fallbacks seguros, explícitos y observables.
- Resolver streaming y Facebook desde `settings` con fallback permitido a `.env`; validar URLs externas y no ocultar errores críticos.
- Usar `config/default-content.php` solo para contenido verificado o mensajes neutrales; no introducir nuevos mocks productivos.
- `database/create-local-users.php` es exclusivo de `APP_ENV=local`; prohibido en producción.

## Diseño y experiencia de usuario
- Conservar la identidad visual y el sistema CSS propio basado en tokens, componentes, Grid y Flexbox; reducir gradualmente duplicaciones legacy.
- Ofrecer experiencia completa en desktop, tablet y móvil. Auditar 1920×1080, 1440×900, 1366×768, 768×1024, 390×844 y 360×800.
- Aplicar HTML semántico, navegación por teclado, foco visible, contraste, etiquetas accesibles y `prefers-reduced-motion`; WCAG 2.2 AA es un objetivo de verificación.
- Antes de modificar diseño relevante, inspeccionar el navegador real; documentar defectos, aplicar cambios mínimos y comprobar capturas antes/después.
- Preservar el reproductor global y sus funciones de audio/vídeo sin obstruir permanentemente contenido; no alterar el streaming sin justificación.
- Evitar efectos, animaciones y rediseños que comprometan legibilidad, rendimiento o accesibilidad.

## Calidad y pruebas
- Mantener PHPUnit, PHPStan, PHPCS, Vite y Playwright; ejecutar las validaciones pertinentes: `composer validate --strict`, `composer test`, `composer analyse`, `composer lint`, `npm run build` y `npm run test:e2e`.
- Las pruebas administrativas Playwright requieren entorno E2E aislado y credenciales externas; no eludir controles de seguridad ni alterar bases para hacerlas pasar.
- Distinguir siempre resultados aprobados, fallidos, omitidos y bloqueados. No declarar validaciones realizadas sin evidencia.
- Diagnosticar problemas de entorno antes de modificar código funcional. Incorporar CI progresivamente con matriz PHP 8.2/8.4 y auditorías de dependencias.

## Despliegue y entrega
- Mantener compatibilidad con hosting compartido DonWeb, sin Node permanente, Redis, Docker, WebSockets complejos ni colas residentes obligatorias.
- Compilar assets antes de desplegar; utilizar `scripts/build-deploy.ps1` y excluir secretos, Git, pruebas, logs y temporales.
- Configurar base y `.env` de producción independientemente; no incluir `CREATE DATABASE` ni `USE database` en el esquema productivo.
- No declarar listo el sistema hasta validar administración autónoma, seguridad, pruebas, instalación, respaldos, restauración y aceptación del cliente.
- Documentar configuración, despliegue, operaciones administrativas, mantenimiento y riesgos conocidos.

## Git y ejecución de tareas
- Inspeccionar el estado y alcance antes de modificar. Preservar cambios existentes y evitar modificaciones ajenas al objetivo.
- Utilizar Conventional Commits con tipo y ámbito en inglés y descripción en español, por ejemplo: `fix(player): corregir controles de reproducción`.
- Realizar staging selectivo; no hacer commit, push, rebase, reescribir historial ni descartar cambios sin autorización explícita.
- No usar comandos destructivos ni cambiar identidad de autor o añadir trailers no solicitados.
- Al finalizar, informar archivos modificados, decisiones, pruebas realmente ejecutadas, bloqueos y pendientes.
- Consultar documentación especializada solo cuando la tarea la requiera; no cargar documentación completa por defecto.
