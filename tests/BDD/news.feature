Feature: Gestión de noticias
  Como Admin de Radio Rumbo
  Quiero gestionar noticias
  Para publicar información local de forma ordenada

  Background:
    Given existe un usuario administrativo autenticado
    And el usuario tiene rol "Admin"

  Scenario: Crear una noticia como borrador
    When el administrador registra una noticia con título, resumen, contenido y categoría
    Then la noticia queda guardada con estado "draft"
    And la noticia no aparece todavía en la portada pública

  Scenario: Admin publica una noticia
    Given el Admin inició sesión
    When crea una noticia con título, resumen, contenido y categoría
    Then la noticia debe quedar como publicada
    And debe mostrarse en la página pública de noticias

  Scenario: Archivar una noticia publicada
    Given existe una noticia publicada
    When el administrador archiva la noticia
    Then la noticia cambia a estado "archived"
    And la noticia deja de aparecer en la portada pública
