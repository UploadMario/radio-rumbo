Feature: Autenticación administrativa
  Como usuario administrativo de Radio Rumbo
  Quiero iniciar sesión de forma segura
  Para acceder al panel administrativo

  Scenario: Admin inicia sesión correctamente
    Given existe un usuario Admin activo
    When ingresa credenciales válidas
    Then debe acceder al dashboard administrativo

  Scenario: Usuario no autenticado intenta entrar al dashboard
    Given no existe una sesión activa
    When intenta acceder al dashboard
    Then debe ser redirigido al login
