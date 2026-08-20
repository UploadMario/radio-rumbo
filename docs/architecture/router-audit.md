# Auditoría Del Router

`App\Core\Router` existe como base técnica simple, pero el proyecto actual resuelve la mayor parte de rutas con entry points físicos bajo `public/` y `public/admin/`.

## Decisión Fase 3.0

No se reescribe el routing. La estrategia actual es compatible con DonWeb y reduce riesgo en hosting compartido.

## Estado

- `/` se atiende desde `public/index.php`.
- `/admin/` se atiende desde `public/admin/index.php`.
- Rutas administrativas específicas se atienden desde carpetas físicas bajo `public/admin/`.
- `Router` se conserva como componente disponible para futuras rutas simples, pero no se fuerza su uso global.

## Criterio Futuro

Solo migrar a un routing más centralizado si elimina duplicación real sin romper compatibilidad con DonWeb ni seguridad de rutas internas.
