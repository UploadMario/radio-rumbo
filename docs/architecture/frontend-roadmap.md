# Preparación Frontend Para La Siguiente Fase

La Fase 3.0 no introduce rediseño visual, Motion, shaders, WebGL, React ni dependencias obligatorias de Node en producción.

## Estado Actual

- CSS público en `public/assets/css/main.css`.
- CSS administrativo en `public/assets/css/admin.css`.
- JavaScript vanilla en `public/assets/js/`.
- DonWeb recibe assets estáticos ya listos.

## Ruta Sugerida Para Fase 3.1

Si se decide modularizar assets, usar un pipeline opcional de build local:

```text
src/
  styles/
  scripts/

public/assets/dist/
```

Node/Vite no debe ser requisito en DonWeb. El hosting debe recibir archivos compilados en `public/assets/dist/`.

## Restricciones

- No introducir React para esta web pública.
- No convertir la aplicación en SPA.
- Mantener vistas PHP renderizadas por servidor.
- Priorizar componentes CSS/JS vanilla reutilizables.
