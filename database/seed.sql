INSERT INTO roles (name) VALUES
('Super Admin'),
('Admin');

INSERT INTO settings (setting_key, setting_value, description) VALUES
('STREAM_URL', 'https://example.com/stream', 'URL placeholder del stream. Reemplazar durante instalacion.'),
('stream_audio_url', 'https://mediastreamm.com/8008/stream;', 'URL real de audio en vivo.'),
('live_video_widget_url', 'https://servilive.com/live-stream-video-widget/tvrumbo', 'Widget real de video en vivo.'),
('facebook_page_url', 'https://www.facebook.com/radiorumboperu', 'Pagina oficial de Facebook.'),
('facebook_page_id', '217453608287467', 'ID publico de la pagina oficial de Facebook.'),
('facebook_page_plugin_url', 'https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fradiorumboperu&tabs=timeline&width=500&height=650&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true', 'Plugin oficial de pagina Facebook.'),
('RADIO_NAME', 'Radio Rumbo', 'Nombre publico del proyecto.'),
('RADIO_TAGLINE', 'La radio que se ve', 'Lema publico confirmado.'),
('RADIO_LOCATION', 'Huancayo, Perú', 'Ubicacion publica confirmada.'),
('WHATSAPP_CABINA', '', 'WhatsApp de cabina. No guardar datos reales en este seed.'),
('WHATSAPP_PUBLICIDAD', '', 'WhatsApp de publicidad. No guardar datos reales en este seed.');

-- Crear usuarios reales durante instalacion usando password_hash().
-- No incluir contrasenas reales ni hashes productivos en este archivo.

INSERT INTO programs (name, slug, description, host_name, day_of_week, start_time, end_time, category, is_active, display_order) VALUES
('Programa matinal', 'programa-matinal', 'Espacio informativo y musical de inicio del dia.', 'Conductor principal', 'monday', '06:00:00', '09:00:00', 'Magazine local', 1, 10),
('Rumbo Noticias', 'rumbo-noticias', 'Resumen informativo local preparado para desarrollo.', 'Equipo de prensa', 'monday', '12:00:00', '13:00:00', 'Informacion', 1, 20),
('Magazine local', 'magazine-local', 'Contenido local, musica y entrevistas en preparacion.', 'Conductor principal', 'friday', '16:00:00', '18:00:00', 'Entretenimiento', 1, 30);

INSERT INTO team_members (name, role, program_name, bio, photo_path, facebook_url, instagram_url, is_active, display_order) VALUES
('Conductor principal', 'Conduccion', 'Programa matinal', 'Perfil placeholder para reemplazar con datos oficiales.', '', '', '', 1, 10),
('Equipo de prensa', 'Prensa', 'Rumbo Noticias', 'Equipo placeholder para contenidos informativos.', '', '', '', 1, 20),
('Area comercial', 'Publicidad', 'Publicidad local', 'Contacto comercial placeholder sin datos personales reales.', '', '', '', 1, 30);

INSERT INTO settings (setting_key, setting_value, description) VALUES
('RADIO_EMAIL', '', 'Correo institucional pendiente.'),
('RADIO_ABOUT', 'Radio Rumbo, La radio que se ve, conecta Huancayo con informacion y entretenimiento local.', 'Texto institucional breve.');

INSERT INTO banners (title, subtitle, image_path, link_url, position, status, start_at, end_at, display_order) VALUES
('Publicidad local', 'Espacios radiales y web para comercios de Huancayo.', '', '', 'home_middle', 'active', NULL, NULL, 10);

INSERT INTO urgent_notices (title, message, type, status, start_at, end_at, display_order) VALUES
('Aviso importante', 'La programacion especial se actualizara durante la transmision.', 'info', 'active', NULL, NULL, 10);
