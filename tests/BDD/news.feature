Feature: Gestion de noticias
  Como administrador de Radio Rumbo
  Quiero gestionar noticias
  Para publicar informacion local de forma ordenada

  Background:
    Given existe un usuario administrativo autenticado
    And el usuario tiene rol "Admin"

  Scenario: Crear una noticia como borrador
    When el administrador registra una noticia con titulo, resumen y contenido
    Then la noticia queda guardada con estado "draft"
    And la noticia no aparece todavia en la portada publica

  Scenario: Publicar una noticia
    Given existe una noticia en estado "draft"
    When el administrador publica la noticia
    Then la noticia cambia a estado "published"
    And la noticia aparece en el listado publico de noticias

  Scenario: Archivar una noticia publicada
    Given existe una noticia publicada
    When el administrador archiva la noticia
    Then la noticia cambia a estado "archived"
    And la noticia deja de aparecer en la portada publica
