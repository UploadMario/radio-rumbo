# Radio Rumbo — Corrección del entorno de pruebas e integridad de datos

Fecha de verificación: 2026-10-15

## Baseline y causa raíz

El repositorio contiene `vendor/autoload.php` y `phpstan.neon`, y PHP puede
leer ambos mediante `file_get_contents()`. Sin embargo, en el contexto normal
de ejecución de Windows, `is_readable()` devolvía `false` para esos archivos y
también para archivos de aplicación, configuración y pruebas. PHPUnit y
PHPStan utilizan esa comprobación internamente, por lo que abortaban antes de
ejecutar pruebas o analizar código.

La causa no fue una ruta incorrecta, una dependencia ausente ni un error de
negocio: fue el contexto ACL/permisos del árbol del repositorio. En un contexto
con permisos de lectura corregidos, PHPUnit y PHPStan funcionan sin cambios de
dependencias.

## Resultado de herramientas

| Comando | Resultado |
|---|---|
| `composer validate --no-check-publish` | OK: `./composer.json is valid` |
| `composer test` | OK: 75 tests, 118 assertions |
| `composer analyse` | OK: PHPStan sin errores, 80 archivos analizados |
| `composer lint` | OK |
| `npm run build -- --clearScreen false` | OK: Vite generó los assets compilados |

El contexto normal todavía puede reproducir el fallo si conserva las ACL que
hacen que PHP reporte los archivos como no legibles. La solución operativa es
restaurar permisos de lectura para el usuario que ejecuta PHP/Composer y no
reinstalar dependencias.

## Integridad de Programación y Equipo

Antes del ajuste, las acciones administrativas `delete` ejecutaban `DELETE`
físico en `Program::delete()` y `TeamMember::delete()`. Los registros no tenían
claves foráneas entrantes que exigieran ese borrado y ambas tablas ya contaban
con `is_active`, utilizado por las consultas públicas.

La acción administrativa ahora usa archivado lógico: `is_active = 0`. Se
conservan los registros, sus IDs, horarios, orden, datos de equipo y trazas
relacionadas. El alias de compatibilidad `delete()` también archiva, evitando
que una llamada antigua borre información.

No se añadió `deleted_at` ni se creó una migración: el esquema existente ya
ofrece el mecanismo mínimo suficiente. Las acciones quedan registradas como
`program.archive` y `team.archive` mediante `AuditLogService`.

## Matriz de permisos efectiva

| Operación | Público | Admin | Super Admin |
|---|---:|---:|---:|
| Ver contenido publicado/activo | Sí | Sí | Sí |
| Crear/editar/activar/archivar noticias | No | Sí | Sí |
| Crear/editar/activar/archivar programación | No | Sí | Sí |
| Crear/editar/activar/archivar equipo | No | Sí | Sí |
| Revisar/archivar mensajes | No | Sí | Sí |
| Gestionar banners y avisos | No | Sí | Sí |
| Editar configuración sensible | No | No | Sí |
| Gestionar usuarios/roles | No | No disponible | No disponible |
| Consultar auditoría | No | No disponible | No disponible |

La tabla refleja la autorización actualmente expuesta por los controladores;
la gestión de usuarios y la UI de auditoría siguen fuera de alcance.

## Riesgos pendientes

1. El permiso ACL debe quedar corregido también en el entorno del desarrollador
   y en cualquier agente/servicio que ejecute Composer.
2. No existe todavía una prueba HTTP completa que recorra formularios, CSRF y
   autorización por rol.
3. No se ha implementado restauración de registros archivados desde el panel;
   por ahora se puede reactivar mediante la acción existente de activación o
   una operación administrativa controlada.
4. La migración de índices de Fase 3.0 debe revisarse antes de repetirla en una
   base que ya tenga esos índices.

## Paquete de despliegue

`scripts/build-deploy.ps1` excluye `.env`, credenciales locales, logs,
`tests/`, cachés, `.git`, `node_modules` y artefactos temporales, y conserva
`vendor/` para hosting donde Composer no pueda ejecutarse. No se imprimieron
secretos durante esta verificación.
