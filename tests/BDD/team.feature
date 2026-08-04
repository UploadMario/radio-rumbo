Feature: Equipo de la radio

Scenario: Admin publica un integrante del equipo
  Given el Admin inicio sesion
  When registra un integrante activo
  Then el integrante debe mostrarse en la pagina publica de equipo
