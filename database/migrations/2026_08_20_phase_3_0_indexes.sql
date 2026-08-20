-- Radio Rumbo - Fase 3.0 indices recomendados
-- Ejecutar solo despues de backup y revision de indices existentes.
-- Compatible con MySQL/MariaDB en hosting compartido.

CREATE INDEX idx_news_status_published_at ON news (status, published_at);
CREATE INDEX idx_programs_active_day_time ON programs (is_active, day_of_week, start_time);
CREATE INDEX idx_contact_messages_status_created ON contact_messages (status, created_at);
CREATE INDEX idx_banners_status_dates ON banners (status, start_at, end_at);
CREATE INDEX idx_urgent_notices_status_dates ON urgent_notices (status, start_at, end_at);
CREATE INDEX idx_audit_logs_user_created ON audit_logs (user_id, created_at);
