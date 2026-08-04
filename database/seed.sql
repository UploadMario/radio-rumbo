USE radio_rumbo;

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
