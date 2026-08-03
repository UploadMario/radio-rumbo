# Estrategia De Pruebas

## Pruebas unitarias

Cubren helpers, validadores y servicios puros. La Fase 0 incluye pruebas para slug, escape HTML y roles permitidos.

## Pruebas de integracion

Cubriran flujo entre controladores, servicios y base de datos cuando existan modulos funcionales.

## Pruebas BDD/Gherkin

`tests/BDD` contiene escenarios legibles para negocio. En Fase 0 se documenta un ejemplo de noticias sin integrar todavia un runner BDD.

## QA manual

Cada pantalla nueva debe revisarse en navegador, incluyendo estados vacios, errores, permisos y responsive basico.

## Pruebas de seguridad basicas

Validar CSRF en formularios, escape HTML, consultas preparadas, proteccion de uploads, sesiones y ausencia de credenciales versionadas.

## Cobertura minima inicial

La meta inicial es cubrir helpers y reglas criticas. En fases posteriores se definira una meta numerica cuando existan modulos estables.

## Mutacion

Las pruebas de mutacion quedan como fase futura, una vez que los modulos principales tengan suficiente cobertura y estabilidad.
