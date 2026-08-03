# Guia De Trabajo Con Codex

## Arquitectura

Radio Rumbo usa una arquitectura MVC simple sin framework pesado. `public/index.php` actua como entrada publica, `admin/index.php` como placeholder administrativo y `app/` concentra la logica PHP.

Capas principales:

- `app/Core`: infraestructura minima como router, base de datos y vistas.
- `app/Controllers`: controladores HTTP.
- `app/Models`: representacion de entidades y acceso futuro a datos.
- `app/Services`: reglas de negocio.
- `app/Middleware`: autenticacion, roles y validaciones transversales.
- `app/Validators`: validaciones de entrada.
- `app/Helpers`: utilidades puras.
- `config`: configuracion desacoplada de codigo.
- `database`: SQL versionable y migraciones futuras.
- `tests`: pruebas unitarias, integracion y BDD documental.

## Convenciones

- PHP con `declare(strict_types=1);`.
- Namespaces PSR-4 bajo `App\`.
- Clases en PascalCase, metodos en camelCase.
- Salida HTML escapada por defecto.
- Sin credenciales reales en codigo, seeds ni documentacion.
- Roles administrativos limitados a `Super Admin` y `Admin`.

## Comandos

```bash
composer install
composer test
composer analyse
composer lint
php -S localhost:8000 -t public
```

## Reglas de carpetas

- No colocar logica de negocio dentro de `public/`.
- No colocar configuracion sensible dentro de assets.
- Guardar archivos subidos solo en `public/uploads/` con validaciones futuras.
- Documentar decisiones tecnicas en `docs/architecture/`.
- Documentar riesgos en `docs/security/`.
- Documentar pruebas y criterios de aceptacion en `docs/qa/`.

## Estandares de codigo

- PSR-12 mediante PHPCS.
- Analisis estatico con PHPStan nivel 6 inicial.
- Dependencias minimas y justificadas.
- Preferir PHP nativo y PDO antes que librerias innecesarias.

## Seguridad

- PDO con excepciones y charset `utf8mb4`.
- Consultas preparadas como regla obligatoria.
- `password_hash()` para contrasenas futuras.
- CSRF para formularios administrativos.
- Escape HTML para toda salida no confiable.
- Validacion de extensiones, MIME, tamano y nombre para uploads futuros.
- Auditoria futura de acciones administrativas en `audit_logs`.

## Proceso de QA

1. Confirmar requisito.
2. Agregar o actualizar pruebas cuando aplique.
3. Ejecutar `composer test`.
4. Ejecutar `composer analyse`.
5. Ejecutar `composer lint`.
6. Revisar que no haya credenciales ni datos reales.
7. Actualizar documentacion si cambia arquitectura, seguridad o alcance.

## Como crear nuevos modulos

1. Definir requisito en `docs/requirements/requirements.md`.
2. Crear controlador en `app/Controllers`.
3. Crear modelo si hay persistencia.
4. Crear servicio si hay reglas de negocio.
5. Agregar validadores para entrada.
6. Usar middleware si requiere autenticacion o rol.
7. Crear pruebas unitarias o de integracion.
8. Documentar rutas, riesgos y criterios de aceptacion.

## Como documentar cambios

- Cambios funcionales: actualizar requisitos.
- Cambios de arquitectura: actualizar `docs/architecture/architecture.md`.
- Cambios de seguridad: actualizar matriz de riesgos.
- Cambios de pruebas: actualizar estrategia o Definition of Done si aplica.

## Criterios minimos para aceptar una tarea

- Requisito implementado sin sobredimensionar.
- Compatible con PHP 8.1+ y hosting compartido DonWeb.
- Sin frameworks pesados.
- Sin credenciales reales.
- Roles sin cambios no autorizados.
- Pruebas y herramientas ejecutadas o limitacion documentada.
- Codigo legible, tipado y alineado a PSR-12.
