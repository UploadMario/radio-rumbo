# Instrucciones Para Agentes

## Stack permitido

PHP 8.1+, MySQL, HTML, CSS, JavaScript vanilla, Composer, PHPUnit, PHPStan y PHPCS. No usar Laravel, Symfony, WordPress, React obligatorio, Node.js obligatorio, Docker obligatorio ni microservicios.

Extensiones PHP recomendadas: `pdo_mysql`, `mbstring`, `dom`, `xml`, `xmlwriter`, `SimpleXML` e `iconv`.

## Reglas de seguridad

- No crear ni versionar credenciales reales.
- No guardar contrasenas planas.
- Usar `password_hash()` y `password_verify()` para autenticacion futura.
- Escapar salida HTML con `SecurityHelper::e()`.
- Usar CSRF en formularios administrativos futuros.
- Usar CSRF en formularios administrativos funcionales.
- Usar PDO y consultas preparadas para todo acceso a datos.
- No exponer `.env`, backups, logs ni archivos internos en `public/`.
- Mantener el document root apuntando a `public/`.
- El acceso administrativo publico debe ser `/admin/` desde `public/admin/index.php`; la logica real debe permanecer fuera de `public/`.

## No modificar sin autorizacion

- Roles permitidos: solo `Super Admin` y `Admin`.
- Estructura base de carpetas.
- Decisiones de compatibilidad con DonWeb.
- Estrategia de document root en `public/`.
- Documentacion de requisitos, seguridad, QA e ISO.
- Esquema SQL base cuando afecte migraciones futuras.

## Pruebas

```bash
composer test
composer analyse
composer lint
php -S localhost:8000 -t public
```

Si las dependencias no estan instaladas, ejecutar primero `composer install`.

## Definition of Done

Una tarea esta terminada cuando cumple el requisito, mantiene compatibilidad con DonWeb, no introduce credenciales, respeta la estructura del proyecto, no rompe pruebas, incluye validacion basica, documenta cambios relevantes y agrega pruebas cuando corresponde.

## Restricciones DonWeb

Asumir hosting compartido tradicional: PHP + MySQL, sin procesos permanentes, sin servicios en segundo plano obligatorios, sin Docker y sin WebSockets complejos.

Crear la base de datos desde el panel DonWeb e importar `database/schema.sql`. No depender de `CREATE DATABASE` en produccion. `vendor/` no se versiona, pero puede subirse al hosting como artefacto si no se puede ejecutar Composer.

No exponer `app/`, `config/`, `database/`, `docs/`, `resources/`, `tests/`, `vendor/` ni `.env`.

## Datos y roles

No crear roles adicionales. No inventar datos reales de la emisora, personal, clientes, telefonos, contrasenas ni correos privados.

## Fase 2.0 funcional

- `/admin/login/`, `/admin/dashboard/`, `/admin/logout/` y `/admin/news/` son rutas funcionales.
- Las noticias publicas de `/noticias/` deben leer solo registros `published`.
- Crear usuario inicial con `password_hash()` y `database/create-initial-user.example.sql` como referencia.
- No implementar recuperación de contraseña, usuarios avanzados, uploads ni métricas avanzadas sin nueva autorización.

## Fase 2.0.1 local y medios

- `database/create-local-users.php` solo puede ejecutarse con `APP_ENV=local`.
- Credenciales locales sugeridas son exclusivamente de desarrollo y están prohibidas en producción/DonWeb.
- Audio, video y Facebook se configuran por `settings` o `.env`.
- No usar scraping ni API privada de Facebook.
- Validar URLs externas como HTTPS.
