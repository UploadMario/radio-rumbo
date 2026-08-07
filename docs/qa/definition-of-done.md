# Definition Of Done

Una tarea se considera terminada solo si:

- Cumple el requisito solicitado.
- Tiene validacion basica de entradas cuando aplica.
- No rompe pruebas existentes.
- Respeta la estructura del proyecto.
- No introduce credenciales ni datos reales sensibles.
- Usa consultas preparadas cuando accede a base de datos.
- Usa CSRF en formularios administrativos funcionales.
- Protege rutas administrativas con sesión cuando corresponde.
- Documenta cambios relevantes.
- Mantiene compatibilidad con DonWeb y hosting compartido.
- Mantiene `public/` como document root recomendado y evita exposicion de carpetas internas.
- Verifica `/` y `/admin/` cuando toca entradas publicas.
- Incluye pruebas cuando aplica.
- Mantiene roles limitados a `Super Admin` y `Admin`.
- Para formularios publicos, incluye CSRF, validacion y proteccion anti-spam simple cuando corresponde.
- Documenta claramente cuando una funcion guarda datos pero no envia correos o no ejecuta procesos externos.
- Para configuracion critica, respeta permisos por rol y mantiene `.env` como fallback documentado.
