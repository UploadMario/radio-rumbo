USE radio_rumbo;

INSERT INTO roles (name) VALUES
('Super Admin'),
('Admin');

INSERT INTO settings (setting_key, setting_value, description) VALUES
('STREAM_URL', 'https://example.com/stream', 'URL placeholder del stream. Reemplazar durante instalacion.'),
('RADIO_NAME', 'Radio Rumbo', 'Nombre publico del proyecto.'),
('RADIO_TAGLINE', 'La radio que se ve', 'Lema publico confirmado.'),
('RADIO_LOCATION', 'Huancayo, Perú', 'Ubicacion publica confirmada.'),
('WHATSAPP_CABINA', '', 'WhatsApp de cabina. No guardar datos reales en este seed.'),
('WHATSAPP_PUBLICIDAD', '', 'WhatsApp de publicidad. No guardar datos reales en este seed.');

-- Crear usuarios reales durante instalacion usando password_hash().
-- No incluir contrasenas reales ni hashes productivos en este archivo.
