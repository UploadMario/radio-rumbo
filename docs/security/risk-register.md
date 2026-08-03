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
| Exposicion de carpetas internas | `app`, `config`, `database`, `docs`, `resources`, `tests` o `vendor` quedan navegables. | Alto | Media | Document root en `public/`, `.htaccess` raiz y checklist DonWeb. | Control reforzado Fase 0.1 |
| Mala configuracion de document root | El dominio apunta a la raiz del proyecto en lugar de `public/`. | Alto | Media | Guia `docs/deployment/donweb.md`, bloqueos `.htaccess` y verificacion manual 403. | Control reforzado Fase 0.1 |
| Ejecucion de archivos maliciosos en uploads | Un archivo subido se ejecuta como PHP dentro de `public/uploads`. | Alto | Media | `public/uploads/.htaccess`, validaciones futuras de extension, MIME, tamano y nombre. | Control inicial reforzado |
| Exposicion accidental de `.env` | El archivo de entorno real queda accesible desde el navegador. | Critico | Media | Bloqueo `.htaccess`, `.gitignore`, document root `public/` y prueba manual de `/.env`. | Control reforzado Fase 0.1 |
