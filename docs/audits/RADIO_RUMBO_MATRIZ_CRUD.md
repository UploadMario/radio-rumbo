# Radio Rumbo - Matriz CRUD

Estados usados:

- IMPLEMENTADO Y VERIFICADO: existe codigo, rutas/formularios y evidencia por tests o validacion ejecutada en esta auditoria.
- IMPLEMENTADO SIN VERIFICACION COMPLETA: existe codigo y rutas/formularios, pero no se pudo verificar completamente por entorno o falta de pruebas E2E.
- PARCIAL: existe parte del flujo, pero no CRUD completo.
- AUSENTE: no se encontro modulo real.
- NO APLICA: no corresponde como CRUD.

> Nota: como `composer test` fallo por lectura de `vendor/autoload.php`, ningun modulo se marca como "IMPLEMENTADO Y VERIFICADO" por suite completa en esta auditoria.

## 1. Configuracion institucional

| Aspecto | Estado |
|---|---|
| Create | PARCIAL. `Setting::upsertMany()` crea por upsert. |
| Read | IMPLEMENTADO SIN VERIFICACION COMPLETA. `SettingService::editableSettings()`, `station()`. |
| Update | IMPLEMENTADO SIN VERIFICACION COMPLETA. `SettingController::update()`. |
| Delete | AUSENTE. No hay eliminacion de settings. |
| Activacion/desactivacion | NO APLICA. |
| Borrador/publicacion | NO APLICA. |
| Rutas | `/admin/settings/`. |
| Formularios | `resources/views/admin/settings/index.php`. |
| Persistencia | `settings(setting_key, setting_value, description)`. |
| Validaciones | `SettingValidator`. Valida keys permitidas y URL HTTPS. |
| Autorizacion | `SettingController` limita edicion a `Super Admin`; Admin solo lectura. |
| CSRF | Si, en formulario. |
| Frontend | `SettingService::station()` alimenta station/fallback. |
| Pruebas | `SettingServiceTest`, `SettingValidatorTest`. |
| Faltante | Historial/versionado de cambios, preview, validacion de existencia externa de URLs. |

## 2. Usuarios y roles

| Aspecto | Estado |
|---|---|
| Create | PARCIAL. Script local `database/create-local-users.php` y SQL ejemplo, sin CRUD admin. |
| Read | PARCIAL. `User::findActiveByIdentifier()` solo para login. |
| Update | PARCIAL. `touchLastLogin()`. No edicion admin. |
| Delete | AUSENTE. |
| Activacion/desactivacion | PARCIAL. `users.status` existe, sin UI. |
| Roles | Solo `Super Admin`, `Admin` en `Role`. |
| Rutas | `/admin/login/`, `/admin/logout/`; no `/admin/users/`. |
| Persistencia | `users`, `roles`. |
| Validaciones | `LoginValidator`; no UserValidator. |
| Autorizacion | Login verifica rol permitido. No permisos granulares por operacion salvo settings. |
| CSRF | Login y logout tienen CSRF. |
| Pruebas | `AuthServiceTest`, `RoleTest`, `LoginValidatorTest`. |
| Faltante | CRUD de usuarios, cambio de contrasena, reset seguro, gestion de estado, auditoria UI. |

## 3. Programacion

| Aspecto | Estado |
|---|---|
| Create | IMPLEMENTADO SIN VERIFICACION COMPLETA. `ProgramController::store()`, `ProgramService::create()`. |
| Read | IMPLEMENTADO SIN VERIFICACION COMPLETA. Admin y publico. |
| Update | IMPLEMENTADO SIN VERIFICACION COMPLETA. `update()`. |
| Delete | IMPLEMENTADO SIN VERIFICACION COMPLETA. `ProgramService::delete()` borra fisicamente. |
| Activacion/desactivacion | IMPLEMENTADO SIN VERIFICACION COMPLETA. `toggle()`. |
| Archivado | AUSENTE. No hay estado archived; hay borrado fisico. |
| Borrador/publicacion | NO APLICA en esquema actual. |
| Rutas | `/admin/programs/`, `/create/`, `/edit/?id=`, `/programacion/?dia=...`. |
| Formularios | `resources/views/admin/programs/form.php`. |
| Persistencia | `programs`. |
| Validaciones | `ProgramValidator`; acepta horarios nocturnos por diferencia de inicio/fin. |
| Autorizacion | Requiere sesion; no se detecto distincion fina Super Admin/Admin. |
| CSRF | Si. |
| Frontend | `/programacion/`, Home, player por `ProgramScheduleService`. |
| Pruebas | `ProgramServiceTest`, `ProgramValidatorTest`, `ProgramScheduleServiceTest`. |
| Faltante | Archivado, deteccion de solapamientos, relaciones con conductores reales. |

## 4. Programas y conductores

| Aspecto | Estado |
|---|---|
| Programa | PARCIAL. Programas existen en `programs`. |
| Conductores | PARCIAL. `host_name` es texto libre; no hay tabla/CRUD de conductores. |
| Relacion con equipo | AUSENTE. `team_members.program_name` tambien es texto libre. |
| Faltante | Modelo normalizado de conductores, relacion programa-equipo, historial. |

## 5. Equipo

| Aspecto | Estado |
|---|---|
| Create | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Read | IMPLEMENTADO SIN VERIFICACION COMPLETA. Admin y publico. |
| Update | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Delete | IMPLEMENTADO SIN VERIFICACION COMPLETA. `TeamMember::delete()` borra fisicamente. |
| Activacion/desactivacion | IMPLEMENTADO SIN VERIFICACION COMPLETA. `toggle()`. |
| Archivado | AUSENTE. |
| Rutas | `/admin/team/`, `/create/`, `/edit/?id=`, `/equipo/`. |
| Formularios | `resources/views/admin/team/form.php`. |
| Persistencia | `team_members`. |
| Validaciones | Nombre, rol y URLs sociales HTTPS. |
| CSRF | Si. |
| Frontend | `/equipo/` muestra activos. |
| Pruebas | `TeamServiceTest`, `TeamMemberValidatorTest`. |
| Faltante | Upload real de foto, recorte/preview, archivado, relacion con programas. |

## 6. Noticias

| Aspecto | Estado |
|---|---|
| Create | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Read | IMPLEMENTADO SIN VERIFICACION COMPLETA. Admin lista; publico usa Facebook como fuente principal y `NewsService::published()` para dinamicas. |
| Update | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Delete | PARCIAL. No borra; archiva. |
| Activacion/desactivacion | PARCIAL. Estados `draft/published/archived`; no toggle directo. |
| Archivado | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Borrador/publicacion | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Rutas | `/admin/news/`, `/create/`, `/edit/?id=`, `/noticias/`. |
| Formularios | `news-form.php`, `news-index.php`. |
| Persistencia | `news`. |
| Validaciones | `NewsValidator`. |
| CSRF | Si. |
| Frontend | `/noticias/` prioriza Facebook feed. |
| Pruebas | `NewsServiceTest`, `NewsValidatorTest`. |
| Faltante | Imagen destacada, preview, SEO por noticia, vista detalle publica. |

## 7. Integracion de Facebook

| Aspecto | Estado |
|---|---|
| Create/Update/Delete | NO APLICA como CRUD; se configura por settings. |
| Read | IMPLEMENTADO SIN VERIFICACION COMPLETA. `facebook-feed.php` usa URL plugin/pagina. |
| Rutas | `/noticias/`, `/en-vivo/`; settings en `/admin/settings/`. |
| Validaciones | URLs HTTPS via `SettingValidator` y `UrlHelper`. |
| Frontend | Iframe oficial con fallback. |
| Pruebas | `SettingServiceTest` cubre fallback de plugin. |
| Faltante | Verificacion en navegador real, estado visual si terceros bloquean iframe. |

## 8. Banners y promociones

| Aspecto | Estado |
|---|---|
| Create | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Read | IMPLEMENTADO SIN VERIFICACION COMPLETA. Admin y publico por `public-banner.php`. |
| Update | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Delete | PARCIAL. Archiva, no elimina. |
| Activacion/desactivacion | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Archivado | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Borrador/publicacion | PARCIAL. Estados `draft/active/inactive/archived`. |
| Rutas | `/admin/banners/`, `/create/`, `/edit/?id=`. |
| Formularios | `resources/views/admin/banners/form.php`. |
| Persistencia | `banners`. |
| Validaciones | `BannerValidator`. |
| CSRF | Si. |
| Frontend | Home por `public-banner.php`, posiciones `home_hero`, `home_middle`, `sidebar`, `footer`. |
| Pruebas | `BannerServiceTest`, `BannerValidatorTest`. |
| Faltante | Upload, video, duracion, media library, preview, MIME/tamano, carousel mixto. |

## 9. Avisos urgentes

| Aspecto | Estado |
|---|---|
| Create | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Read | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Update | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Delete | PARCIAL. Archiva, no elimina. |
| Activacion/desactivacion | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Archivado | IMPLEMENTADO SIN VERIFICACION COMPLETA. |
| Borrador/publicacion | PARCIAL por estados. |
| Rutas | `/admin/urgent-notices/`, `/create/`, `/edit/?id=`. |
| Formularios | `urgent-notices/form.php`, `index.php`. |
| Persistencia | `urgent_notices`. |
| Validaciones | `UrgentNoticeValidator`. |
| Frontend | `urgent-notice-bar.php`. |
| Pruebas | `UrgentNoticeServiceTest`, `UrgentNoticeValidatorTest`. |
| Faltante | Segmentacion por pagina, prioridad avanzada, preview. |

## 10. Eventos

| Aspecto | Estado |
|---|---|
| CRUD | AUSENTE. |
| Evidencia | No existe tabla `events`, controlador, servicio, modelo ni vistas. |
| Confusion posible | `urgent_notices.type = event` no equivale a modulo de eventos. |
| Faltante | Tabla, CRUD, portada/media, fecha/hora, ubicacion, vigencia, estado, publicacion. |

## 11. Biblioteca multimedia

| Aspecto | Estado |
|---|---|
| CRUD | AUSENTE. |
| Upload | AUSENTE. |
| Validacion MIME/tamano | AUSENTE. |
| Reutilizacion | AUSENTE. |
| Evidencia | No hay `MediaService`, `Media` model, `$_FILES` ni `move_uploaded_file`. |

## 12. Imagenes y videos

| Aspecto | Estado |
|---|---|
| Imagenes en banners | PARCIAL por `image_path` manual o URL HTTPS. |
| Imagenes de equipo | PARCIAL por `photo_path` manual. |
| Video en vivo | IMPLEMENTADO SIN VERIFICACION COMPLETA por Servilive iframe configurado. |
| Videos promocionales | AUSENTE. |
| Upload y almacenamiento | AUSENTE. |
| Validaciones | HTTPS para URLs externas; no MIME/tamano porque no hay upload. |

## 13. Mensajes y solicitudes comerciales

| Aspecto | Estado |
|---|---|
| Create | IMPLEMENTADO SIN VERIFICACION COMPLETA desde `/publicidad-contacto/`. |
| Read | IMPLEMENTADO SIN VERIFICACION COMPLETA en `/admin/messages/` y detalle. |
| Update | PARCIAL. Marcar leido y archivar. |
| Delete | PARCIAL. Archiva, no elimina. |
| Estados | `new/read/archived`. |
| CSRF | Si en publico y admin. |
| Anti-spam | Honeypot `website`. |
| Pruebas | `ContactMessageServiceTest`, `ContactMessageValidatorTest`. |
| Faltante | Respuesta por correo, exportacion, retencion configurable, asignacion. |

## 14. Redes sociales y enlaces de transmision

| Aspecto | Estado |
|---|---|
| CRUD | PARCIAL via settings editables. |
| Campos | Audio, video, Facebook, WhatsApp, email. |
| Validaciones | HTTPS para URLs criticas. |
| Frontend | Header/footer/player/live/news/contacto consumen `station`. |
| Faltante | Validacion de disponibilidad externa, pruebas E2E de embeds. |

## 15. Auditoria administrativa

| Aspecto | Estado |
|---|---|
| Create | PARCIAL via `AuditLogService::record()`. |
| Read | AUSENTE. No hay UI/listado. |
| Update/Delete | NO APLICA o AUSENTE. |
| Persistencia | `audit_logs`. |
| Acciones registradas | Login/logout, settings y acciones en varios controladores admin segun llamadas a `audit()->record()`. |
| Faltante | UI de auditoria, filtros, retencion, exportacion, cobertura uniforme por todas las acciones. |
