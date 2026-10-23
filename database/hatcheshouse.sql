CREATE DATABASE IF NOT EXISTS hatcheshouse CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hatcheshouse;

CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default login:
-- Email: admin@hatcheshouse.com
-- Password: ChangeMe123!
INSERT INTO admins (name,email,password_hash)
VALUES ('HatchesHouse Admin','admin@hatcheshouse.com','$2y$12$ScsPX3Ug1wp33b9dp6xaz.Hv9vUPwWLjcGnxqwro45.fJ2iiRH8T.')
ON DUPLICATE KEY UPDATE email=email;

CREATE TABLE events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(190) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    short_description VARCHAR(500) NULL,
    description TEXT NOT NULL,
    cover_image VARCHAR(255) NULL,
    event_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NULL,
    venue VARCHAR(190) NOT NULL,
    city VARCHAR(100) NULL,
    status ENUM('draft','published','cancelled') NOT NULL DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_events_status_date(status,event_date)
);

CREATE TABLE ticket_types (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id INT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(12,2) NOT NULL DEFAULT 0,
    quantity INT UNSIGNED NOT NULL DEFAULT 0,
    sold INT UNSIGNED NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_ticket_event FOREIGN KEY(event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_ticket_event(event_id)
);

CREATE TABLE registrations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id INT UNSIGNED NOT NULL,
    ticket_type_id INT UNSIGNED NOT NULL,
    name VARCHAR(190) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    total_amount DECIMAL(12,2) NOT NULL,
    payment_reference VARCHAR(100) NOT NULL UNIQUE,
    status ENUM('pending','paid','failed','cancelled') NOT NULL DEFAULT 'pending',
    paid_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reg_event FOREIGN KEY(event_id) REFERENCES events(id) ON DELETE CASCADE,
    CONSTRAINT fk_reg_ticket FOREIGN KEY(ticket_type_id) REFERENCES ticket_types(id) ON DELETE RESTRICT,
    INDEX idx_reg_status(status),
    INDEX idx_reg_event(event_id)
);

CREATE TABLE tickets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    registration_id BIGINT UNSIGNED NOT NULL,
    ticket_code VARCHAR(80) NOT NULL UNIQUE,
    status ENUM('valid','used','cancelled') NOT NULL DEFAULT 'valid',
    checked_in_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_ticket_registration FOREIGN KEY(registration_id) REFERENCES registrations(id) ON DELETE CASCADE
);

CREATE TABLE contact_messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(190) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(50) NULL,
    message TEXT NOT NULL,
    status ENUM('new','read','closed') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
