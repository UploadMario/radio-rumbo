# Arquitectura

## Arquitectura general

Radio Rumbo se plantea como una aplicacion PHP MVC simple, sin framework pesado. El objetivo es mantener bajo acoplamiento entre interfaz, logica, datos y configuracion para facilitar crecimiento gradual. La raiz web recomendada es `public/`.

La Fase 1.1 incorpora una capa visual conectable: layouts públicos/admin, parciales reutilizables, vistas PHP por pantalla, logo real y datos mock centralizados. Esta capa prepara la experiencia de usuario sin activar todavía persistencia ni administración real.

La Fase 2.0 incorpora controladores, modelos, servicios y validadores para autenticación y noticias. El acceso público sigue entrando por `public/`, mientras la lógica real permanece en `app/`.

## Estructura de carpetas

- `app/Core`: router, base de datos y soporte comun.
- `app/Controllers`: coordinan peticiones y respuestas.
- `app/Models`: encapsulan acceso mínimo a datos con PDO.
- `app/Services`: contienen reglas de negocio reutilizables para autenticación, settings, sesión y noticias.
- `app/Middleware`: autenticacion, roles y controles transversales.
- `app/Validators`: validacion de entrada.
- `app/Helpers`: funciones auxiliares seguras.
- `public`: entrada publica y assets.
- `public/admin`: entrada publica placeholder para `/admin/`.
- `public/assets/css`: estilos publicos y administrativos.
- `public/assets/js`: interacciones vanilla.
- `admin`: carpeta interna protegida para preparacion administrativa futura.
- `config`: configuracion desacoplada.
- `config/mock-data.php`: datos visuales placeholder para Fase 1.
- `database`: esquema, seed y migraciones.
- `resources/views`: vistas PHP futuras fuera del document root.
- `tests`: pruebas automatizadas y BDD documental.
- `docs`: documentacion viva del proyecto.

## Flujo publico

El usuario ingresa por `public/index.php`. En Fase 1 existen rutas visuales para home, en vivo, programacion, noticias, podcasts, equipo y publicidad/contacto. La configuracion publica, como `STREAM_URL`, se obtendra desde entorno o base de datos en fases posteriores.

## Flujo administrativo

`public/admin/index.php` es la entrada publica para `/admin/`. En Fase 1 muestra dashboard visual y login visual opcional por query string. La logica administrativa real debe permanecer fuera de `public/`. En fases posteriores se agregaran login real, sesiones, CSRF, roles y dashboard funcional. Los roles permitidos son exclusivamente `Super Admin` y `Admin`.

## Decisiones tecnicas

- PHP nativo con Composer para autoload.
- PDO con `utf8mb4`, excepciones y prepared statements.
- PHPCS PSR-12 para estilo.
- PHPStan para analisis estatico.
- PHPUnit para pruebas.
- Sin framework pesado para ajustarse a hosting compartido.
- Datos mock centralizados para evitar inventar informacion real.
- JavaScript vanilla solo para interacciones visuales.

## Restricciones por DonWeb

DonWeb se asume como hosting tradicional compartido. Por eso no se requieren procesos residentes, contenedores, microservicios, colas permanentes ni WebSockets complejos.

El document root debe apuntar a `public/`. Si el plan no lo permite, se debe evitar publicar carpetas internas o confirmar que los `.htaccess` bloqueen accesos directos.

## Separacion de responsabilidades

- GUI: HTML, CSS y JavaScript vanilla en `resources/views` y `public/assets`.
- Logica: controladores, servicios, validadores y middleware en `app`.
- Datos: mocks en `config/mock-data.php` durante Fase 1; modelos y SQL en `database` para fases posteriores.
- Datos Fase 2.0: noticias, usuarios, roles y settings usan MySQL por PDO.
- Configuracion: `.env` y archivos de `config`.
