# Radio Rumbo - Plan recomendado de implementacion

Este plan no implementa cambios; organiza etapas para completar la operacion institucional sin duplicar sistemas existentes.

## A. Correcciones criticas de seguridad e integridad

Objetivo: recuperar confianza tecnica antes de nuevas funcionalidades.

1. Resolver bloqueo de baseline PHP:
   - revisar por que `composer test` no puede abrir `vendor/autoload.php`;
   - revisar por que PHPStan no puede leer `phpstan.neon`;
   - repetir `composer test`, `composer analyse`, `composer lint`.
2. Confirmar en navegador local:
   - login/logout POST;
   - rutas admin sin sesion;
   - CSRF invalido;
   - Facebook y Servilive con CSP.
3. Sustituir borrado fisico en Programacion y Equipo por archivado logico.
4. Definir matriz de permisos Super Admin/Admin por modulo.
5. Revisar paquete DonWeb:
   - ejecutar `scripts/build-deploy.ps1`;
   - confirmar que `.env`, logs, tests y temporales no entren al ZIP.

## B. Servicio y biblioteca multimedia

Objetivo: resolver la base comun antes de carruseles, equipo, banners y eventos.

### Propuesta de modelo

Tabla sugerida `media_assets`:

| Campo | Proposito |
|---|---|
| `id` | Identificador |
| `title` | Nombre administrativo |
| `media_type` | `image` / `video` |
| `storage_path` | Ruta local segura si es upload |
| `external_url` | URL HTTPS si el recurso es externo |
| `mime_type` | MIME validado |
| `file_size` | Tamano en bytes |
| `alt_text` | Accesibilidad |
| `duration_seconds` | Duracion de video si aplica |
| `width` / `height` | Dimensiones cuando se puedan obtener |
| `status` | `active` / `archived` |
| `created_by` | Usuario |
| `created_at` / `updated_at` | Timestamps |

### Reglas minimas

- Validar extension y MIME real.
- Limitar tamano.
- Generar nombre seguro.
- Guardar fuera de control manual de usuarios.
- Bloquear ejecucion PHP en uploads.
- Permitir reutilizacion por banners, equipo y eventos.
- No borrar fisicamente si esta referenciado.

## C. Promociones mixtas y carrusel Home

Objetivo: extender `banners` sin crear un segundo sistema paralelo.

### Contrato de datos propuesto para promocion

```php
[
    'id' => 1,
    'title' => 'Campaña local',
    'subtitle' => 'Mensaje comercial o institucional',
    'media_type' => 'image|video',
    'media_src' => '/uploads/media/archivo.webp',
    'poster_src' => '/uploads/media/poster.webp',
    'link_url' => 'https://...',
    'cta_label' => 'Ver mas',
    'position' => 'home_hero|home_middle|...',
    'status' => 'draft|active|inactive|archived',
    'start_at' => '2026-10-12 08:00:00',
    'end_at' => null,
    'display_order' => 10,
    'duration_seconds' => 6,
    'muted' => true,
]
```

### Comportamiento frontend propuesto

- Imagen:
  - avanza por tiempo configurable;
  - muestra progreso;
  - pausa en hover/focus si corresponde.
- Video:
  - inicia silenciado por defecto;
  - avanza al finalizar;
  - no compite con player de audio;
  - respeta `prefers-reduced-motion`.
- Accesibilidad:
  - controles visibles;
  - pausa/reanudar;
  - teclado;
  - labels claros.
- Coordinacion:
  - no autoplay con sonido;
  - no pausar player de audio por videos promocionales mudos;
  - si algun video exige sonido, accion explicita del usuario.

## D. Configuracion institucional y programacion

1. Pulir admin de settings con lenguaje no tecnico.
2. Agregar preview de cambios publicos.
3. Definir si `Admin` puede editar settings no criticos.
4. Programacion:
   - detectar solapamientos;
   - mantener soporte nocturno;
   - agregar archivado;
   - evaluar relacion con integrantes.

## E. Eventos, equipo y contenido

### Eventos

Crear modulo despues de biblioteca multimedia:

| Campo | Proposito |
|---|---|
| `title` | Titulo |
| `slug` | URL |
| `description` | Contenido |
| `starts_at` / `ends_at` | Fecha/hora |
| `location_name` | Lugar |
| `address` | Direccion |
| `cover_media_id` | Imagen/video opcional |
| `link_url` | Enlace externo |
| `status` | `draft/published/archived` |
| `display_order` | Orden |

Frontend:

- listado publico;
- detalle opcional;
- integracion Home solo si hay eventos activos.

### Equipo

- Usar biblioteca multimedia para fotos.
- Evitar rutas manuales.
- Relacionar opcionalmente con programas.

## F. UX administrativa y frontend

1. Vista de auditoria para Super Admin.
2. Preview de banners, avisos y eventos.
3. Mensajes de error recuperables y claros.
4. Mantener header publico sin Admin.
5. Seguir retirando CSS legacy por componente.

## G. Pruebas integrales y despliegue DonWeb

1. Agregar pruebas HTTP/controlador:
   - rutas admin protegidas;
   - CSRF invalido;
   - permisos por rol;
   - formularios con datos invalidos.
2. Agregar pruebas de multimedia:
   - upload valido;
   - MIME invalido;
   - tamano excedido;
   - archivo PHP rechazado;
   - archivo referenciado no se elimina fisicamente.
3. Agregar checklist DonWeb:
   - PHP extensions;
   - `.env` manual;
   - permisos uploads;
   - import schema/migrations;
   - assets Vite compilados;
   - no Node en produccion.

## Orden recomendado

1. Arreglar baseline de pruebas.
2. Archivado logico para Programacion/Equipo.
3. Biblioteca multimedia.
4. Extension de banners a promociones mixtas.
5. Carrusel Home.
6. Eventos.
7. Usuarios admin.
8. Auditoria UI.
9. Pruebas E2E y paquete DonWeb final.
