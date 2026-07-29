CREATE TABLE rutas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    origen VARCHAR(100),
    destino VARCHAR(100)
);

CREATE TABLE paradas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ruta_id INT,
    nombre VARCHAR(100),
    lat DECIMAL(10,8),
    lng DECIMAL(11,8),
    FOREIGN KEY (ruta_id) REFERENCES rutas(id)
);

CREATE TABLE buses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ruta_id INT,
    lat DECIMAL(10,8),
    lng DECIMAL(11,8),
    timestamp_actualizacion DATETIME,
    activo BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (ruta_id) REFERENCES rutas(id)
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    correo VARCHAR(100) UNIQUE,
    contrasena VARCHAR(255),
    rol ENUM('admin', 'auditor') NOT NULL
);

CREATE TABLE emisores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_acceso VARCHAR(50) UNIQUE,
    bus_id INT,
    FOREIGN KEY (bus_id) REFERENCES buses(id)
);