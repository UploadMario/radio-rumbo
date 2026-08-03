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

## Objetivo del sistema

Construir una plataforma web administrable para una radio local: portada, transmision en vivo, noticias, programacion, equipo, contacto, publicidad y panel administrativo. En Fase 0 solo se prepara la base tecnica y documental.

## Instalacion local

1. Instalar PHP 8.1+, Composer y extensiones comunes de PHP: PDO, pdo_mysql, mbstring e iconv.
2. Copiar `.env.example` como `.env`.
3. Ajustar variables locales sin usar credenciales reales de produccion.
4. Instalar dependencias:

```bash
composer install
```

5. Crear la base de datos local usando `database/schema.sql` y cargar datos placeholder con `database/seed.sql`.

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
public/         Punto de entrada publico, assets y uploads publicos controlados.
admin/          Placeholder del panel administrativo.
config/         Configuracion de aplicacion y base de datos.
database/       Esquema, seed y futuras migraciones SQL.
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

Abrir `http://localhost:8000`. El panel administrativo esta preparado como placeholder en `admin/index.php`; en una fase posterior se definira su publicacion segura bajo la estructura final del hosting.

## Despliegue en DonWeb

- Publicar solo los archivos necesarios en el hosting.
- Configurar el document root hacia `public/` si el plan lo permite.
- Mantener `.env`, `vendor/`, `database/`, `docs/` y archivos de configuracion sensibles fuera de exposicion publica cuando sea posible.
- DonWeb puede operar como hosting compartido; no asumir procesos permanentes, colas residentes, WebSockets ni Docker.
- Usar MySQL y PHP 8.1+ disponibles en el panel.
- Generar credenciales y hashes de contrasena durante instalacion, nunca desde archivos versionados.
