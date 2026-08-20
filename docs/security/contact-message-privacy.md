# Mensajes De Contacto: Minimización Y Retención

Radio Rumbo guarda mensajes enviados desde publicidad/contacto para que el equipo administrativo pueda revisar solicitudes comerciales, consultas generales, noticias o reportes técnicos.

## Datos Guardados

- Nombre, asunto, tipo de consulta y mensaje.
- Correo y teléfono solo si el visitante los proporciona.
- IP y User-Agent con fines de seguridad, trazabilidad básica y mitigación de abuso.
- Estado operativo: `new`, `read` o `archived`.

## Acceso

El acceso queda restringido al panel administrativo protegido por sesión, CSRF y roles permitidos: `Super Admin` y `Admin`.

## Minimización

No se debe solicitar información sensible innecesaria. No se guardan contraseñas, tokens, cookies ni documentos personales en `contact_messages`.

## Retención Recomendada

Revisar y archivar mensajes atendidos periódicamente. Como política técnica inicial, se recomienda depurar o exportar mensajes archivados con más de 12 meses cuando la operación administrativa lo permita.
