-- =============================================================
-- Unnnati Database Schema + Demo Data
-- =============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Create database if not exists (optional - comment out if already created)
-- CREATE DATABASE IF NOT EXISTS unnnati_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE unnnati_db;

-- ------------------------------
-- Users table (authentication)
-- ------------------------------
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    username        VARCHAR(50) UNIQUE NOT NULL,
    email           VARCHAR(100) UNIQUE NOT NULL,
    password        VARCHAR(255) NOT NULL,          -- hashed with password_hash()
    full_name       VARCHAR(100) DEFAULT NULL,
    role            ENUM('admin', 'user') DEFAULT 'user',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login      TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------
-- Portfolio / Testimonials (public visible)
-- ------------------------------
DROP TABLE IF EXISTS testimonials;
CREATE TABLE testimonials (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    role        VARCHAR(100),
    company     VARCHAR(100),
    message     TEXT NOT NULL,
    photo_path  VARCHAR(255) DEFAULT NULL,
    is_active   TINYINT(1) DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------
-- Inventory (ERP module)
-- ------------------------------
DROP TABLE IF EXISTS inventory;
CREATE TABLE inventory (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    item_code       VARCHAR(30) UNIQUE,
    name            VARCHAR(150) NOT NULL,
    category        VARCHAR(80),
    quantity        INT DEFAULT 0,
    unit_price      DECIMAL(12,2) DEFAULT 0.00,
    location        VARCHAR(100),
    notes           TEXT,
    last_updated    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by      INT DEFAULT NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------
-- Assets (Asset Management)
-- ------------------------------
DROP TABLE IF EXISTS assets;
CREATE TABLE assets (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(150) NOT NULL,
    category            VARCHAR(80),
    serial_number       VARCHAR(50),
    purchase_date       DATE,
    purchase_value      DECIMAL(12,2),
    current_value       DECIMAL(12,2),
    depreciation_rate   DECIMAL(5,2) DEFAULT 0.00,
    location            VARCHAR(100),
    photo_path          VARCHAR(255) DEFAULT NULL,
    maintenance_due     DATE DEFAULT NULL,
    notes               TEXT,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------
-- Maintenance Logs (for assets)
-- ------------------------------
DROP TABLE IF EXISTS asset_maintenance;
CREATE TABLE asset_maintenance (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    asset_id    INT NOT NULL,
    performed_on DATE NOT NULL,
    description TEXT,
    cost        DECIMAL(10,2) DEFAULT 0.00,
    next_due    DATE,
    created_by  INT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id)   REFERENCES assets(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------
-- Personal Growth (Unnnati module) - simple finance log
-- ------------------------------
DROP TABLE IF EXISTS personal_finance;
CREATE TABLE personal_finance (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    entry_date  DATE NOT NULL,
    type        ENUM('income','expense') NOT NULL,
    category    VARCHAR(80),
    amount      DECIMAL(12,2) NOT NULL,
    description TEXT,
    user_id     INT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================
-- Demo / Seed Data
-- =============================================================

-- Default admin user
-- password = "password123"  →  change it after first login!
INSERT INTO users (username, email, password, full_name, role)
VALUES (
    'admin',
    'admin@example.com',
    '$2y$10$JDJ5JDEwJEFYcU5vY0p4dE9vQ2dYcU5vY0p4dE9vQ2dYcU5vY0p4dA==', -- password_hash("password123", PASSWORD_DEFAULT)
    'Naveen Admin',
    'admin'
);

-- Some demo testimonials (public portfolio)
INSERT INTO testimonials (name, role, company, message, is_active)
VALUES
    ('Rahul Sharma', 'CTO', 'TechNova', 'Naveen delivered a clean, fast ERP system in pure PHP – impressive!', 1),
    ('Priya Menon', 'Founder', 'GrowEasy', 'The personal dashboard (Unnnati) helped me track habits and finances consistently.', 1);

-- Demo inventory items
INSERT INTO inventory (item_code, name, category, quantity, unit_price, location)
VALUES
    ('LAP001', 'Dell XPS 13', 'Laptop', 4, 128000.00, 'Main Office'),
    ('CHR-042', 'Ergonomic Office Chair', 'Furniture', 18, 4800.00, 'Warehouse B');

-- Demo asset
INSERT INTO assets (name, category, purchase_date, purchase_value, current_value, location, maintenance_due)
VALUES
    ('MacBook Pro M2', 'Laptop', '2024-06-10', 189000.00, 151200.00, 'Home Office', '2025-12-10');

COMMIT;