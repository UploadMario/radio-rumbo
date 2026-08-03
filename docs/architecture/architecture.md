# Arquitectura

## Arquitectura general

Radio Rumbo se plantea como una aplicacion PHP MVC simple, sin framework pesado. El objetivo es mantener bajo acoplamiento entre interfaz, logica, datos y configuracion para facilitar crecimiento gradual. La raiz web recomendada es `public/`.

## Estructura de carpetas

- `app/Core`: router, base de datos y soporte comun.
- `app/Controllers`: coordinan peticiones y respuestas.
- `app/Models`: representan entidades y acceso futuro a datos.
- `app/Services`: contienen reglas de negocio reutilizables.
- `app/Middleware`: autenticacion, roles y controles transversales.
- `app/Validators`: validacion de entrada.
- `app/Helpers`: funciones auxiliares seguras.
- `public`: entrada publica y assets.
- `public/admin`: entrada publica placeholder para `/admin/`.
- `admin`: carpeta interna protegida para preparacion administrativa futura.
- `config`: configuracion desacoplada.
- `database`: esquema, seed y migraciones.
- `resources/views`: vistas PHP futuras fuera del document root.
- `tests`: pruebas automatizadas y BDD documental.
- `docs`: documentacion viva del proyecto.

## Flujo publico

El usuario ingresa por `public/index.php`. En fases futuras, el router resolvera rutas publicas como home, noticias, programacion, equipo y contacto. La configuracion publica, como `STREAM_URL`, se obtendra desde entorno o base de datos.

## Flujo administrativo

`public/admin/index.php` es el placeholder publico de Fase 0.1 para `/admin/`. La logica administrativa real debe permanecer fuera de `public/`. En Fase 1 se agregara login real, sesiones, CSRF, roles y dashboard. Los roles permitidos son exclusivamente `Super Admin` y `Admin`.

## Decisiones tecnicas

- PHP nativo con Composer para autoload.
- PDO con `utf8mb4`, excepciones y prepared statements.
- PHPCS PSR-12 para estilo.
- PHPStan para analisis estatico.
- PHPUnit para pruebas.
- Sin framework pesado para ajustarse a hosting compartido.

## Restricciones por DonWeb

DonWeb se asume como hosting tradicional compartido. Por eso no se requieren procesos residentes, contenedores, microservicios, colas permanentes ni WebSockets complejos.

El document root debe apuntar a `public/`. Si el plan no lo permite, se debe evitar publicar carpetas internas o confirmar que los `.htaccess` bloqueen accesos directos.

## Separacion de responsabilidades

- GUI: HTML, CSS y JavaScript vanilla en `public` o vistas futuras.
- Logica: controladores, servicios, validadores y middleware en `app`.
- Datos: modelos y SQL en `database`.
- Configuracion: `.env` y archivos de `config`.
