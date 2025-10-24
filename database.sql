-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS login_db;
USE login_db;

-- Crear la tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar un usuario de prueba
-- Usuario: admin
-- Contraseña: admin123
INSERT INTO users (username, password) VALUES 
('admin', '$2y$10$8AQK9.BeLKSAJ9kf6nZ4/.wzG9mPUaJfgzzrMxl0Pc0QhVABAO3F.');