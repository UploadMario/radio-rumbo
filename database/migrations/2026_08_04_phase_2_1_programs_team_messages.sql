-- Radio Rumbo - Migracion Fase 2.1
-- Ejecutar solo en instalaciones que ya tengan el esquema de fases anteriores.
-- Revisar backups antes de aplicar en hosting compartido.

ALTER TABLE programs
    DROP COLUMN schedule_text,
    DROP COLUMN status,
    ADD COLUMN day_of_week ENUM('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday') NOT NULL DEFAULT 'monday' AFTER host_name,
    ADD COLUMN start_time TIME NOT NULL DEFAULT '06:00:00' AFTER day_of_week,
    ADD COLUMN end_time TIME NOT NULL DEFAULT '09:00:00' AFTER start_time,
    ADD COLUMN category VARCHAR(80) NULL AFTER end_time,
    ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER category,
    ADD COLUMN display_order INT UNSIGNED NOT NULL DEFAULT 0 AFTER is_active;

ALTER TABLE team_members
    CHANGE COLUMN role_title role VARCHAR(140) NOT NULL,
    ADD COLUMN program_name VARCHAR(140) NULL AFTER role,
    ADD COLUMN facebook_url VARCHAR(255) NULL AFTER photo_path,
    ADD COLUMN instagram_url VARCHAR(255) NULL AFTER facebook_url,
    DROP COLUMN status,
    DROP COLUMN sort_order,
    ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER instagram_url,
    ADD COLUMN display_order INT UNSIGNED NOT NULL DEFAULT 0 AFTER is_active;

ALTER TABLE contact_messages
    MODIFY COLUMN subject VARCHAR(160) NOT NULL,
    ADD COLUMN type ENUM('general', 'advertising', 'news', 'technical') NOT NULL DEFAULT 'general' AFTER message,
    MODIFY COLUMN status ENUM('new', 'read', 'archived') NOT NULL DEFAULT 'new',
    ADD COLUMN ip_address VARCHAR(45) NULL AFTER status,
    ADD COLUMN user_agent VARCHAR(255) NULL AFTER ip_address;
