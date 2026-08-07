# Estrategia De Pruebas

## Pruebas unitarias

Cubren helpers, validadores y servicios puros. La suite actual incluye pruebas para slug, escape HTML, roles permitidos, renderizado seguro de vistas y estructura de datos mock.

## Pruebas de integracion

Cubriran flujo entre controladores, servicios y base de datos cuando existan modulos funcionales.

## Pruebas BDD/Gherkin

`tests/BDD` contiene escenarios legibles para negocio. En Fase 0 se documenta un ejemplo de noticias sin integrar todavia un runner BDD.

## QA manual

Cada pantalla nueva debe revisarse en navegador, incluyendo estados vacios, errores, permisos y responsive basico. En Fase 1 se debe revisar `/`, `/en-vivo/`, `/programacion/`, `/noticias/`, `/podcasts/`, `/equipo/`, `/publicidad-contacto/`, `/admin/` y `/admin/?view=login`.

## Pruebas de seguridad basicas

Validar CSRF en formularios, escape HTML, consultas preparadas, proteccion de uploads, sesiones y ausencia de credenciales versionadas.

En Fase 1 los formularios son visuales y no envian informacion real. Aun asi, los datos mock impresos deben escaparse y no deben incluir credenciales ni datos sensibles.

En Fase 2.0 se agregan pruebas para validadores de login/noticias, autenticación con hash, creación de noticias y consulta pública limitada a noticias publicadas. Las pruebas de integración usan SQLite en memoria cuando la extensión está disponible.

En Fase 2.0.1 se agregan pruebas para validar URLs HTTPS y fallback de settings. QA manual debe verificar audio en vivo con interacción del usuario, iframe de video, bloque de Facebook o su fallback, login local y logout.

En Fase 2.1 se agregan pruebas de validadores y servicios para programacion, equipo y mensajes con SQLite en memoria cuando esta disponible. QA manual debe verificar crear programa, ver programacion publica, crear integrante, ver equipo publico, enviar contacto, ver mensaje admin y archivar.

En Fase 2.2 se agregan pruebas para validadores de configuracion, banners y avisos. QA manual debe verificar Super Admin en configuracion, Admin solo lectura, crear/desactivar banners, crear/desactivar avisos y visibilidad publica.

## Cobertura minima inicial

La meta inicial es cubrir helpers y reglas criticas. En fases posteriores se definira una meta numerica cuando existan modulos estables.

## Mutacion

Las pruebas de mutacion quedan como fase futura, una vez que los modulos principales tengan suficiente cobertura y estabilidad.
