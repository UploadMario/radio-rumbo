Feature: Endurecimiento administrativo

Scenario: Sesión administrativa expira por inactividad
  Given un usuario Admin inició sesión
  And supera el tiempo de inactividad configurado
  When intenta abrir el dashboard administrativo
  Then debe volver al login
  And no debe ver información interna del sistema

Scenario: Login bloquea intentos repetidos
  Given no existe una sesión activa
  When una misma combinación de correo e IP falla varias veces dentro de la ventana configurada
  Then el sistema debe rechazar temporalmente nuevos intentos
  And debe mostrar un mensaje genérico

Scenario: Logout requiere POST y CSRF
  Given un usuario Admin inició sesión
  When solicita cerrar sesión con un formulario válido
  Then la sesión debe cerrarse correctamente
  But una visita GET a la ruta de logout no debe cerrar la sesión silenciosamente

Scenario: Programa actual usa timezone de aplicación
  Given la aplicación usa el timezone America/Lima
  When se calcula la programación del día
  Then el programa actual y el siguiente deben resolverse con la hora de Lima
