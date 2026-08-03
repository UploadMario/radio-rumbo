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
