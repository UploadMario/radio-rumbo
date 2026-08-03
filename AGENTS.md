# Instrucciones Para Agentes

## Stack permitido

PHP 8.1+, MySQL, HTML, CSS, JavaScript vanilla, Composer, PHPUnit, PHPStan y PHPCS. No usar Laravel, Symfony, WordPress, React obligatorio, Node.js obligatorio, Docker obligatorio ni microservicios.

## Reglas de seguridad

- No crear ni versionar credenciales reales.
- No guardar contrasenas planas.
- Usar `password_hash()` y `password_verify()` para autenticacion futura.
- Escapar salida HTML con `SecurityHelper::e()`.
- Usar CSRF en formularios administrativos futuros.
- Usar PDO y consultas preparadas para todo acceso a datos.
- No exponer `.env`, backups, logs ni archivos internos en `public/`.

## No modificar sin autorizacion

- Roles permitidos: solo `Super Admin` y `Admin`.
- Estructura base de carpetas.
- Decisiones de compatibilidad con DonWeb.
- Documentacion de requisitos, seguridad, QA e ISO.
- Esquema SQL base cuando afecte migraciones futuras.

## Pruebas

```bash
composer test
composer analyse
composer lint
```

Si las dependencias no estan instaladas, ejecutar primero `composer install`.

## Definition of Done

Una tarea esta terminada cuando cumple el requisito, mantiene compatibilidad con DonWeb, no introduce credenciales, respeta la estructura del proyecto, no rompe pruebas, incluye validacion basica, documenta cambios relevantes y agrega pruebas cuando corresponde.

## Restricciones DonWeb

Asumir hosting compartido tradicional: PHP + MySQL, sin procesos permanentes, sin servicios en segundo plano obligatorios, sin Docker y sin WebSockets complejos.

## Datos y roles

No crear roles adicionales. No inventar datos reales de la emisora, personal, clientes, telefonos, contrasenas ni correos privados.
