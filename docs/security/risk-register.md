# Matriz Inicial De Riesgos

| Riesgo | Descripcion | Impacto | Probabilidad | Control propuesto | Estado |
| --- | --- | --- | --- | --- | --- |
| Acceso no autorizado al panel | Un tercero accede a funciones administrativas. | Alto | Media | Login seguro, sesiones, CSRF, roles `Super Admin` y `Admin`, registro en auditoria. | Identificado |
| SQL Injection | Entradas manipuladas alteran consultas SQL. | Alto | Media | PDO, consultas preparadas, validadores y revision de codigo. | Control inicial definido |
| XSS | Contenido no confiable se renderiza como HTML ejecutable. | Alto | Media | Escape de salida, validacion de entradas y sanitizacion contextual. | Control inicial definido |
| Subida de archivos maliciosos | Uploads contienen scripts o archivos peligrosos. | Alto | Media | Validar extension, MIME, tamano, nombre, almacenamiento y permisos. | Pendiente fase funcional |
| Perdida de datos | Fallas, errores humanos o ausencia de backups afectan informacion. | Alto | Media | Backups, migraciones, auditoria y procedimientos de restauracion. | Identificado |
| Caida del stream | El reproductor futuro no puede transmitir audio. | Medio | Media | Configuracion externa de stream, mensajes de fallback y monitoreo manual inicial. | Identificado |
| Exposicion de credenciales | `.env`, backups o logs quedan publicos. | Alto | Media | `.gitignore`, document root en `public/`, permisos y revision antes de despliegue. | Control inicial definido |
| Cambios no trazables | Acciones administrativas futuras no tienen historial. | Medio | Media | Tabla `audit_logs` y registro de acciones relevantes. | Preparado en esquema |
| Sesion administrativa prolongada | Una sesion abierta en equipo compartido podria quedar activa demasiado tiempo. | Alto | Media | Timeout configurable por `ADMIN_SESSION_TIMEOUT` y destruccion segura de sesion. | Mitigado Fase 3.0 |
| Fuerza bruta de login | Intentos repetidos podrian presionar el formulario administrativo. | Alto | Media | Rate limiting local por ventana de tiempo, mensajes genericos y sin revelar existencia de usuario. | Mitigado Fase 3.0 |
| Errores no observables | Fallbacks silenciosos pueden ocultar fallas reales. | Medio | Media | `LoggerService` en `storage/logs/app.log` con contexto sanitizado. | Mitigado Fase 3.0 |
| Paquete ZIP con `.env` local | Un despliegue manual podria subir credenciales locales. | Alto | Media | Script `scripts/build-deploy.ps1` excluye `.env` y documentacion exige crearlo manualmente en servidor. | Mitigado Fase 3.0 |
| Exposicion de carpetas internas | `app`, `config`, `database`, `docs`, `resources`, `tests` o `vendor` quedan navegables. | Alto | Media | Document root en `public/`, `.htaccess` raiz y checklist DonWeb. | Control reforzado Fase 0.1 |
| Mala configuracion de document root | El dominio apunta a la raiz del proyecto en lugar de `public/`. | Alto | Media | Guia `docs/deployment/donweb.md`, bloqueos `.htaccess` y verificacion manual 403. | Control reforzado Fase 0.1 |
| Ejecucion de archivos maliciosos en uploads | Un archivo subido se ejecuta como PHP dentro de `public/uploads`. | Alto | Media | `public/uploads/.htaccess`, validaciones futuras de extension, MIME, tamano y nombre. | Control inicial reforzado |
| Exposicion accidental de `.env` | El archivo de entorno real queda accesible desde el navegador. | Critico | Media | Bloqueo `.htaccess`, `.gitignore`, document root `public/` y prueba manual de `/.env`. | Control reforzado Fase 0.1 |
| Fuerza bruta contra login | Intentos repetidos buscan adivinar credenciales administrativas. | Alto | Media | Mensajes no sensibles, contraseñas con hash y recomendación de credenciales fuertes; rate limiting queda para fase futura. | Mitigación inicial Fase 2.0 |
| CSRF en acciones admin | Un tercero intenta ejecutar formularios administrativos desde otro sitio. | Alto | Media | Tokens CSRF en login y formularios de noticias. | Control implementado Fase 2.0 |
| Exposición de noticias no publicadas | Borradores o archivadas aparecen en la web pública. | Medio | Media | Consulta pública filtra exclusivamente `status = published`. | Control implementado Fase 2.0 |
| Uso de credenciales locales en producción | Credenciales de desarrollo se usan en DonWeb. | Crítico | Baja | Script limitado a `APP_ENV=local`, documentación explícita y `.env.example` marcado como desarrollo. | Control implementado Fase 2.0.1 |
| URL externa maliciosa | Una URL no HTTPS se usa en iframe o audio. | Alto | Media | `UrlHelper` valida protocolo HTTPS y descarta protocolos peligrosos. | Control implementado Fase 2.0.1 |
| Bloqueo de embeds externos | Facebook o widgets externos no cargan por políticas del navegador. | Bajo | Media | Mensajes fallback y enlaces externos alternativos; sin scraping. | Riesgo aceptado |
| Abuso de formulario publico | Bots o usuarios pueden enviar mensajes repetidos al formulario de contacto. | Medio | Media | CSRF, limites de longitud, validacion servidor y honeypot sin reCAPTCHA en esta fase. | Mitigacion inicial Fase 2.1 |
| URLs sociales inseguras | Enlaces de equipo podrian usar protocolos peligrosos. | Medio | Baja | Aceptar solo URLs HTTPS en redes sociales. | Mitigado Fase 2.1 |
| Correos no configurados | El usuario podria asumir respuesta automatica desde el formulario. | Bajo | Media | Documentar que el formulario guarda mensajes y no envia correos todavia. | Mitigado Fase 2.1 |
| Cambios criticos de configuracion | Un usuario no autorizado modifica stream, Facebook o datos institucionales. | Alto | Media | Edicion limitada a `Super Admin`, CSRF y validacion servidor. | Mitigacion inicial Fase 2.2 |
| Banners con enlaces inseguros | Un banner apunta a protocolo peligroso o destino no confiable. | Medio | Media | Validar URLs HTTPS y escapar salida HTML. | Mitigado Fase 2.2 |
| Avisos alarmistas o vencidos | Comunicados antiguos o mal redactados afectan percepcion publica. | Bajo | Media | Estados, vigencia opcional y gestion administrativa de activacion. | Mitigacion inicial Fase 2.2 |
