DROP DATABASE IF EXISTS localizador_buses;

CREATE DATABASE localizador_buses
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE localizador_buses;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'auditor', 'propietario') NOT NULL,
    debe_cambiar_contrasena BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE historial_contrasenas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    contrasena_anterior VARCHAR(255) NOT NULL,
    fecha_cambio DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE buses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    origen VARCHAR(100),
    destino VARCHAR(100),
    propietario_id INT NULL,
    lat DECIMAL(10,8),
    lng DECIMAL(11,8),
    timestamp_actualizacion DATETIME,
    activo BOOLEAN DEFAULT FALSE,
    habilitado BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (propietario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

CREATE TABLE paradas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    lat DECIMAL(10,8) NOT NULL,
    lng DECIMAL(11,8) NOT NULL,
    orden INT DEFAULT 0,
    FOREIGN KEY (bus_id) REFERENCES buses(id) ON DELETE CASCADE
);

CREATE TABLE emisores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_acceso VARCHAR(50) UNIQUE NOT NULL,
    bus_id INT NOT NULL,
    FOREIGN KEY (bus_id) REFERENCES buses(id) ON DELETE CASCADE
);

CREATE TABLE usuarios_finales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) UNIQUE NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hora_apertura TIME NOT NULL DEFAULT '06:00:00',
    hora_cierre TIME NOT NULL DEFAULT '20:00:00'
);

INSERT INTO configuracion (hora_apertura, hora_cierre) VALUES ('06:00:00', '20:00:00');

CREATE TABLE historial_posiciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_id INT NOT NULL,
    lat DECIMAL(10,8) NOT NULL,
    lng DECIMAL(11,8) NOT NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bus_id) REFERENCES buses(id) ON DELETE CASCADE
);

CREATE TABLE registro_accesos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_usuario VARCHAR(30) NOT NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE limite_peticiones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identificador VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_busqueda (identificador, tipo, fecha_hora)
);

CREATE TABLE intentos_login_fallidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(100) NOT NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_correo (correo, fecha_hora)
);

CREATE TABLE solicitudes_reseteo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(100) NOT NULL,
    fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    atendida BOOLEAN DEFAULT FALSE
);