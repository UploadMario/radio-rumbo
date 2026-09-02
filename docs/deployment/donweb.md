# Despliegue En DonWeb

## Objetivo

Publicar Radio Rumbo en un hosting tradicional DonWeb manteniendo separadas las carpetas publicas de las internas. La meta es reducir exposicion de configuracion, codigo interno, dependencias, SQL, pruebas y documentacion.

## Estructura recomendada

El document root del dominio debe apuntar a:

```text
radio-rumbo/public
```

Con esta estrategia, el navegador puede acceder a:

- `/`
- `/admin/`
- `/assets/`
- `/uploads/`

Y no deberia acceder directamente a:

- `/app`
- `/config`
- `/database`
- `/docs`
- `/resources`
- `/tests`
- `/vendor`
- `/.env`

## Alternativa si DonWeb no permite cambiar document root

Si el plan no permite apuntar el dominio a `public/`, subir el proyecto fuera de la carpeta publica cuando el panel lo permita y publicar solo el contenido de `public/` dentro del directorio web del dominio.

Si todo el proyecto queda dentro del directorio publico por limitacion del hosting, mantener los `.htaccess` incluidos y verificar manualmente que las carpetas internas devuelvan error 403. Esta alternativa tiene mas riesgo y debe evitarse cuando sea posible.

## Manejo de `.env`

- `.env` real nunca se sube a Git.
- Crear `.env` en el hosting a partir de `.env.example`.
- Usar credenciales generadas desde el panel DonWeb.
- No usar las credenciales locales `*.local@radiorumbo.test` en DonWeb.
- No compartir `.env` por correo ni chat.
- Verificar que `/.env` no sea accesible desde el navegador.

## Manejo de `vendor/`

- `vendor/` no se versiona.
- En desarrollo se genera con `composer install`.
- En produccion, si DonWeb permite ejecutar Composer, correr `composer install --no-dev --optimize-autoloader`.
- Si DonWeb no permite Composer, se puede subir `vendor/` por FTP/File Manager como artefacto de despliegue, cuidando que no quede navegable publicamente.

## Subida por FTP o File Manager

1. Preparar una copia limpia del proyecto.
2. Excluir `.git`, caches, logs y archivos temporales.
3. Subir el codigo respetando la estructura.
4. Configurar el dominio para apuntar a `public/`.
5. Crear `.env` real en el hosting.
6. Verificar permisos de `public/uploads/`.
7. Probar `/` y `/admin/`.

## Base de datos

1. Crear la base de datos desde el panel DonWeb.
2. Crear usuario y contrasena desde el panel.
3. Asignar permisos a la base.
4. Importar `database/schema.sql` desde phpMyAdmin o herramienta equivalente.
5. Cargar `database/seed.sql` solo si corresponde.
6. Copiar los datos de conexion al `.env` real.

`schema.sql` no crea la base ni ejecuta `USE`, porque DonWeb suele asignar nombres propios a las bases de datos.

## Configuracion PHP

Usar PHP 8.1 o superior y confirmar estas extensiones:

- pdo_mysql
- mbstring
- dom
- xml
- xmlwriter
- SimpleXML
- iconv

## Medios En Vivo

Configurar en `.env` o en tabla `settings`:

- `STREAM_AUDIO_URL`: `https://mediastreamm.com/8008/stream;`
- `LIVE_VIDEO_WIDGET_URL`: `https://servilive.com/live-stream-video-widget/tvrumbo`
- `FACEBOOK_PAGE_URL`: `https://www.facebook.com/radiorumboperu`
- `FACEBOOK_PAGE_ID`: `217453608287467`
- `FACEBOOK_PAGE_PLUGIN_URL`: iframe oficial del plugin de página.

Facebook puede bloquear embeds por cookies, navegador o políticas externas. El sistema no hace scraping ni usa API privada de Facebook.

## Permisos de uploads

`public/uploads/` debe permitir escritura por la aplicacion cuando existan modulos de subida. No debe permitir ejecucion PHP. El archivo `public/uploads/.htaccess` bloquea extensiones PHP conocidas, pero tambien se deberan validar extension, MIME, tamano y nombre en codigo.

## Riesgos

- Exponer `.env` compromete credenciales.
- Exponer `database/` revela estructura SQL.
- Exponer `vendor/` puede revelar librerias y superficie de ataque.
- Exponer `tests/` y `docs/` puede revelar informacion interna.
- Permitir PHP en uploads puede ejecutar archivos maliciosos.
- Usar document root incorrecto aumenta el riesgo de fuga de informacion.

## Checklist previo a produccion

- [ ] Dominio apunta a `public/`.
- [ ] `/.env` no responde publicamente.
- [ ] `/app`, `/config`, `/database`, `/docs`, `/resources`, `/tests` y `/vendor` no responden publicamente.
- [ ] `/` carga correctamente.
- [ ] `/admin/` carga el placeholder administrativo.
- [ ] PHP 8.1+ activo.
- [ ] Extensiones requeridas disponibles.
- [ ] Base creada desde panel DonWeb.
- [ ] `schema.sql` importado correctamente.
- [ ] `.env` real creado fuera de Git.
- [ ] `public/uploads/` tiene permisos minimos necesarios.
- [ ] No hay contrasenas planas ni datos reales inventados en archivos versionados.

## Paquete seguro de despliegue

La Fase 3.0 agrega `scripts/build-deploy.ps1` para generar un ZIP orientado a DonWeb. El paquete excluye `.env`, `.git`, pruebas, logs, caches y archivos temporales.

```powershell
powershell -ExecutionPolicy Bypass -File scripts/build-deploy.ps1
```

Crear `.env` de produccion manualmente en el servidor. Nunca subir el `.env` local. Si DonWeb no permite ejecutar Composer, el directorio `vendor/` puede subirse como parte del artefacto, siempre fuera de exposicion publica directa.

## Assets frontend Fase 3.1

El build frontend se genera localmente:

```bash
npm install
npm run build
```

DonWeb no necesita Node. Subir `public/assets/dist/` junto con el resto del proyecto preparado. No subir `node_modules/` ni `.npm-cache/`.
