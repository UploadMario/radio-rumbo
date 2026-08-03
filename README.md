# Radio Rumbo

Base Fase 0 para el sistema web de Radio Rumbo, emisora local de Huancayo, Peru. Esta fase crea una estructura profesional, segura y mantenible para crecer sin depender de frameworks pesados ni procesos permanentes.

## Stack tecnico

- PHP 8.1 o superior
- MySQL con PDO
- HTML, CSS y JavaScript vanilla
- Composer para desarrollo local
- PHPUnit para pruebas
- PHPStan para analisis estatico
- PHPCS con PSR-12 para estilo
- phpdotenv para variables de entorno

Extensiones PHP recomendadas: `pdo_mysql`, `mbstring`, `dom`, `xml`, `xmlwriter`, `SimpleXML` e `iconv`.

## Objetivo del sistema

Construir una plataforma web administrable para una radio local: portada, transmision en vivo, noticias, programacion, equipo, contacto, publicidad y panel administrativo. En Fase 0 solo se prepara la base tecnica y documental.

## Instalacion local

1. Instalar PHP 8.1+, Composer y extensiones comunes de PHP: `pdo_mysql`, `mbstring`, `dom`, `xml`, `xmlwriter`, `SimpleXML` e `iconv`.
2. Copiar `.env.example` como `.env`.
3. Ajustar variables locales sin usar credenciales reales de produccion.
4. Instalar dependencias:

```bash
composer install
```

5. Crear la base de datos local. Para MySQL local puede usarse `database/schema.local.sql`; para hosting compartido importar `database/schema.sql` sobre una base ya creada.

## Comandos disponibles

```bash
composer test
composer analyse
composer lint
php -S localhost:8000 -t public
```

- `composer test`: ejecuta PHPUnit.
- `composer analyse`: ejecuta PHPStan sobre `app` y `tests`.
- `composer lint`: ejecuta PHPCS con PSR-12.
- `php -S localhost:8000 -t public`: levanta la pantalla publica minima.

## Estructura del proyecto

```text
app/            Codigo PHP de aplicacion: Core, Controllers, Models, Services, Middleware, Validators y Helpers.
public/         Document root recomendado, entrada publica, /admin, assets y uploads controlados.
admin/          Carpeta interna no publica para preparacion administrativa futura.
config/         Configuracion de aplicacion y base de datos.
database/       Esquema, seed y futuras migraciones SQL.
resources/      Vistas PHP futuras separadas del document root.
tests/          Pruebas unitarias, integracion y BDD documental.
docs/           Requisitos, arquitectura, seguridad, QA e ISO.
```

## Pruebas

Despues de instalar dependencias, ejecutar:

```bash
composer test
```

La Fase 0 incluye pruebas unitarias para helpers de seguridad, slugs y roles permitidos.

## Servidor local

```bash
php -S localhost:8000 -t public
```

Abrir `http://localhost:8000`. El panel administrativo placeholder carga desde `http://localhost:8000/admin/`.

La raiz web local y recomendada para DonWeb es siempre `public/`. Las carpetas internas como `app/`, `config/`, `database/`, `docs/`, `resources/`, `tests/` y `vendor/` no deben quedar expuestas al navegador.

## Despliegue en DonWeb

- Publicar solo los archivos necesarios en el hosting.
- Configurar el document root hacia `public/` si el plan lo permite.
- Si DonWeb no permite cambiar el document root, publicar solo el contenido de `public/` como carpeta web y mantener el resto fuera del area publica cuando sea posible.
- Mantener `.env`, `database/`, `docs/`, `resources/`, `tests/`, `vendor/` y archivos de configuracion sensibles fuera de exposicion publica.
- DonWeb puede operar como hosting compartido; no asumir procesos permanentes, colas residentes, WebSockets ni Docker.
- Usar MySQL y PHP 8.1+ disponibles en el panel.
- Crear la base de datos desde el panel DonWeb e importar `database/schema.sql`.
- `vendor/` no se versiona; puede subirse al hosting como artefacto si no se puede ejecutar Composer en produccion.
- Generar credenciales y hashes de contrasena durante instalacion, nunca desde archivos versionados.
- Ver guia completa en `docs/deployment/donweb.md`.
