-- Radio Rumbo - Migracion Fase 2.2
-- Revisar backup antes de aplicar en una instalacion existente.
-- No elimina datos existentes; adapta banners y crea avisos urgentes si faltan.

ALTER TABLE banners
    ADD COLUMN subtitle VARCHAR(255) NULL AFTER title,
    MODIFY COLUMN image_path VARCHAR(255) NULL,
    CHANGE COLUMN target_url link_url VARCHAR(255) NULL,
    CHANGE COLUMN placement position ENUM('home_hero', 'home_middle', 'sidebar', 'footer') NOT NULL DEFAULT 'home_middle',
    MODIFY COLUMN status ENUM('draft', 'active', 'inactive', 'archived') NOT NULL DEFAULT 'draft',
    CHANGE COLUMN starts_at start_at DATETIME NULL,
    CHANGE COLUMN ends_at end_at DATETIME NULL,
    ADD COLUMN display_order INT UNSIGNED NOT NULL DEFAULT 0 AFTER end_at;

CREATE TABLE IF NOT EXISTS urgent_notices (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(160) NOT NULL,
    message VARCHAR(500) NOT NULL,
    type ENUM('info', 'warning', 'urgent', 'event') NOT NULL DEFAULT 'info',
    status ENUM('draft', 'active', 'inactive', 'archived') NOT NULL DEFAULT 'draft',
    start_at DATETIME NULL,
    end_at DATETIME NULL,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings (setting_key, setting_value, description) VALUES
('RADIO_EMAIL', '', 'Correo institucional pendiente.'),
('RADIO_ABOUT', 'Radio Rumbo, La radio que se ve, conecta Huancayo con informacion y entretenimiento local.', 'Texto institucional breve.')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), description = VALUES(description);
