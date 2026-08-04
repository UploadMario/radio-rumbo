-- Ejemplo para crear usuario inicial sin guardar contrasenas planas.
-- 1. Generar un hash local:
--    php -r "echo password_hash('CAMBIAR_ESTA_CONTRASENA_LOCALMENTE', PASSWORD_DEFAULT), PHP_EOL;"
-- 2. Reemplazar HASH_GENERADO_LOCALMENTE por el hash obtenido.
-- 3. Ejecutar este INSERT manualmente en la base creada desde DonWeb.
-- 4. No versionar archivos con credenciales reales ni hashes productivos.

INSERT INTO users (role_id, name, email, password_hash, status)
SELECT roles.id, 'Admin', 'admin@example.invalid', 'HASH_GENERADO_LOCALMENTE', 'active'
FROM roles
WHERE roles.name = 'Super Admin'
LIMIT 1;
