Feature: Programacion radial

Scenario: Admin crea un programa
  Given el Admin inicio sesion
  When crea un programa con nombre, dia, hora de inicio y hora de fin
  Then el programa debe mostrarse en la programacion publica
