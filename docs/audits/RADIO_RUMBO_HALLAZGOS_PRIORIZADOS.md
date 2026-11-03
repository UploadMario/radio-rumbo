# Radio Rumbo - Hallazgos priorizados

## P0 CRITICO

### RR-P0-001 - Baseline de pruebas PHP bloqueado

- Modulo: QA / entorno.
- Evidencia: `composer test` falla con `Cannot open bootstrap script "C:\Continental\radio-rumbo\vendor/autoload.php"`. `composer analyse` falla con `phpstan.neon is missing or is not readable`. Ambos archivos existen segun `Test-Path`.
- Impacto: no se puede certificar la salud del backend desde esta auditoria.
- Causa probable: bloqueo/permisos/estado transitorio del entorno PHP/Composer o acceso concurrente a archivos.
- Solucion recomendada: revisar permisos, proceso antivirus/locking, ruta de ejecucion y repetir en terminal limpia; si persiste, regenerar autoload con `composer dump-autoload` despues de confirmar que no modifica dependencias.
- Esfuerzo aproximado: Bajo.
- Pruebas necesarias: `composer test`, `composer analyse`, `composer lint`.

### RR-P0-002 - No existe flujo seguro de upload aunque el producto lo necesita

- Modulo: Multimedia / uploads.
- Evidencia: no se detecta `$_FILES`, `move_uploaded_file`, `MediaService`, tabla de medios ni validaciones MIME/tamano. `public/uploads/.htaccess` bloquea PHP, pero el codigo no implementa subida.
- Impacto: administradores no tecnicos no pueden cargar imagenes/videos; si se improvisa con rutas manuales puede derivar en errores o riesgos operativos.
- Causa probable: fases anteriores dejaron `image_path` y `photo_path` como solucion manual.
- Solucion recomendada: implementar primero biblioteca multimedia con validacion de extension, MIME, tamano, nombres seguros, almacenamiento en `public/uploads/` y registro DB; luego conectar banners/equipo.
- Esfuerzo aproximado: Medio/Alto.
- Pruebas necesarias: upload valido, MIME invalido, tamano excedido, nombre malicioso, no ejecucion PHP, borrado seguro.

## P1 ALTO

### RR-P1-001 - Banners no soportan carrusel mixto imagen/video

- Modulo: Banners/promociones.
- Evidencia: tabla `banners` solo tiene `image_path`, `link_url`, `position`, fechas y orden. `BannerValidator` no maneja `media_type`, `video_url`, `duration_seconds`, `autoplay`, etc.
- Impacto: no alcanza para Home con promociones mixtas imagen/video sin duplicar sistema.
- Causa probable: banners fueron concebidos como cards simples.
- Solucion recomendada: extender `banners` o crear `media_assets` + campos de promocion; ver plan propuesto en `RADIO_RUMBO_PLAN_IMPLEMENTACION.md`.
- Esfuerzo aproximado: Medio.
- Pruebas necesarias: imagen activa, video activo, orden, duracion, vigencia, fallback, reduced motion.

### RR-P1-002 - Eventos ausentes como modulo real

- Modulo: Eventos.
- Evidencia: no hay tabla `events`, controlador, servicio, modelo ni vistas. `urgent_notices.type = event` existe, pero es solo aviso.
- Impacto: la radio no puede publicar eventos institucionales/comerciales con estructura propia.
- Causa probable: modulo no fue implementado.
- Solucion recomendada: crear modulo de eventos despues de multimedia, con portada opcional, fecha/hora, ubicacion, enlaces, estado y vigencia.
- Esfuerzo aproximado: Medio.
- Pruebas necesarias: CRUD, publicacion, archivado, fecha pasada/futura, frontend.

### RR-P1-003 - Usuarios no tienen CRUD administrativo

- Modulo: Usuarios y roles.
- Evidencia: `User` solo implementa login y `touchLastLogin`; no existe `/admin/users/`.
- Impacto: crear/desactivar usuarios depende de scripts o SQL; riesgo operativo para produccion.
- Causa probable: alcance previo limitado a usuarios locales y autenticacion.
- Solucion recomendada: CRUD minimo de usuarios solo para Super Admin; mantener roles fijos.
- Esfuerzo aproximado: Medio.
- Pruebas necesarias: crear usuario, desactivar, rechazar rol invalido, password_hash, permisos.

### RR-P1-004 - Programacion y equipo usan borrado fisico

- Modulo: Programacion / Equipo.
- Evidencia: `Program::delete()` ejecuta `DELETE FROM programs`; `TeamMember::delete()` ejecuta `DELETE FROM team_members`.
- Impacto: perdida de datos, perdida de historial, posibles referencias textuales sin trazabilidad.
- Causa probable: CRUD inicial simple.
- Solucion recomendada: migrar a archivado logico (`status` o `is_archived`) o conservar registros inactivos.
- Esfuerzo aproximado: Medio.
- Pruebas necesarias: archivar, no mostrar en publico, conservar en admin, auditoria.

### RR-P1-005 - Permisos granulares incompletos

- Modulo: Autorizacion.
- Evidencia: `SettingController` limita edicion a `Super Admin`, pero otros controladores admin aceptan usuario autenticado sin distincion fina visible.
- Impacto: un Admin puede modificar banners, avisos, noticias, equipo y programacion; puede ser correcto o excesivo segun politica institucional.
- Causa probable: solo existen dos roles sin matriz de permisos.
- Solucion recomendada: definir matriz por operacion manteniendo roles actuales; no crear roles nuevos salvo decision explicita.
- Esfuerzo aproximado: Bajo/Medio.
- Pruebas necesarias: Admin vs Super Admin por operacion.

### RR-P1-006 - Auditoria administrativa sin interfaz de consulta

- Modulo: Auditoria.
- Evidencia: `AuditLogService::record()` escribe en `audit_logs`; no hay `AuditLogController` ni vista admin.
- Impacto: hay trazabilidad parcial, pero no es util para operacion diaria ni investigacion sin SQL.
- Causa probable: fase hardening implemento servicio, no UI.
- Solucion recomendada: vista de solo lectura para Super Admin con filtros basicos.
- Esfuerzo aproximado: Medio.
- Pruebas necesarias: registro de acciones, visualizacion, filtros, proteccion.

## P2 MEDIO

### RR-P2-001 - Convivencia CSS legacy + modular puede generar deuda visual

- Modulo: Frontend.
- Evidencia: `src/styles/main.css` importa `legacy-public.css` junto con modulos nuevos; existe `legacy-admin.css`.
- Impacto: riesgo de duplicidad, sobreescrituras y regresiones responsive.
- Causa probable: migracion progresiva del sistema visual.
- Solucion recomendada: inventario de reglas legacy usadas, retiro incremental por componente.
- Esfuerzo aproximado: Medio.
- Pruebas necesarias: capturas responsive, no overflow, player/header/footer.

### RR-P2-002 - Noticias internas no tienen vista detalle publica

- Modulo: Noticias.
- Evidencia: `NewsService::published()` existe, pero `/noticias/` prioriza Facebook y no se observa ruta `/noticias/{slug}`.
- Impacto: si se publican noticias internas, no hay experiencia editorial completa.
- Causa probable: Facebook se definio como fuente principal.
- Solucion recomendada: decidir si noticias internas seran secundarias o crear detalle por slug.
- Esfuerzo aproximado: Medio.
- Pruebas necesarias: noticia publicada, SEO, 404, escape.

### RR-P2-003 - Settings exponen texto tecnico en admin

- Modulo: Admin UX.
- Evidencia: `resources/views/admin/settings/index.php` muestra `.env sigue funcionando como fallback`.
- Impacto: aceptable para admin tecnico, pero puede confundir a usuarios no tecnicos.
- Causa probable: mensaje de fase tecnica.
- Solucion recomendada: reemplazar por lenguaje operativo en fase UX admin.
- Esfuerzo aproximado: Bajo.
- Pruebas necesarias: revision visual admin.

### RR-P2-004 - No hay pruebas HTTP/E2E

- Modulo: QA.
- Evidencia: tests unitarios/integracion de servicios existen; no se observa framework o suite HTTP para rutas completas.
- Impacto: no se validan CSRF/rutas/protecciones en flujo real.
- Causa probable: arquitectura PHP tradicional sin test client.
- Solucion recomendada: agregar pruebas de entrypoints con servidor local o tests de controlador con stubs.
- Esfuerzo aproximado: Medio.
- Pruebas necesarias: login, logout POST, rutas admin sin sesion, contacto, CRUD.

### RR-P2-005 - Modelo de conductores no normalizado

- Modulo: Programas/equipo.
- Evidencia: `programs.host_name` y `team_members.program_name` son texto libre.
- Impacto: duplicacion e inconsistencias entre programacion y equipo.
- Causa probable: simplicidad inicial.
- Solucion recomendada: evaluar relacion opcional programa-integrante cuando existan datos reales.
- Esfuerzo aproximado: Medio.
- Pruebas necesarias: relacion, fallback, migracion.

## P3 BAJO

### RR-P3-001 - Textos admin sin tildes en algunos formularios

- Modulo: Admin UX.
- Evidencia: formularios usan `Configuracion`, `Gestion`, `Descripcion`, `Dia`.
- Impacto: pulido percibido.
- Causa probable: ASCII inicial.
- Solucion recomendada: normalizar textos visibles en admin.
- Esfuerzo aproximado: Bajo.
- Pruebas necesarias: revision visual.

### RR-P3-002 - Podcasts existe como ruta publica planificada pero sin CRUD

- Modulo: Podcasts.
- Evidencia: `public/podcasts/index.php`, `resources/views/public/podcasts.php`, tabla `podcasts`; no hay controller/service/admin.
- Impacto: si se enlaza en algun lugar puede parecer modulo incompleto.
- Causa probable: previsto para fase futura.
- Solucion recomendada: mantener oculto del menu publico hasta implementacion real.
- Esfuerzo aproximado: Bajo/Medio.
- Pruebas necesarias: navegacion publica.
