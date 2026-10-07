-- Schema for LEQs AIoT Application & Resource Portal
-- Database: app_portal.db (SQLite3)

CREATE TABLE IF NOT EXISTS apps (
    id TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    sub_title TEXT NOT NULL,
    version TEXT NOT NULL,
    filename TEXT NOT NULL,
    filesize_bytes INTEGER NOT NULL,
    category TEXT NOT NULL,
    badge_label TEXT NOT NULL,
    badge_color TEXT NOT NULL,
    description TEXT NOT NULL,
    highlights TEXT NOT NULL, -- JSON or bullet points
    target_platform TEXT NOT NULL,
    icon_type TEXT NOT NULL,
    download_count INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS registrations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    reg_token TEXT UNIQUE NOT NULL,
    full_name TEXT NOT NULL,
    organization TEXT NOT NULL,
    workshop_group TEXT NOT NULL, -- กลุ่มที่ 1 - 15 หรือ ทั่วไป
    email TEXT NOT NULL,
    phone TEXT NOT NULL,
    purpose TEXT NOT NULL,
    notes TEXT,
    ip_address TEXT,
    user_agent TEXT,
    download_count_user INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_active DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS download_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    reg_id INTEGER,
    reg_token TEXT NOT NULL,
    app_id TEXT NOT NULL,
    app_name TEXT NOT NULL,
    filename TEXT NOT NULL,
    ip_address TEXT,
    user_agent TEXT,
    downloaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(reg_id) REFERENCES registrations(id),
    FOREIGN KEY(app_id) REFERENCES apps(id)
);

CREATE INDEX IF NOT EXISTS idx_apps_id ON apps(id);
CREATE INDEX IF NOT EXISTS idx_reg_token ON registrations(reg_token);
CREATE INDEX IF NOT EXISTS idx_download_app ON download_logs(app_id);
CREATE INDEX IF NOT EXISTS idx_download_reg ON download_logs(reg_id);
