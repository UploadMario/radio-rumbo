Feature: Contacto y publicidad

Scenario: Visitante envia mensaje de publicidad
  Given un visitante esta en la pagina de publicidad y contacto
  When envia un formulario valido
  Then el mensaje debe guardarse como nuevo
  And el Admin debe poder verlo en el panel
