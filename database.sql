-- Base de datos CRUD Empleados (Normalizada en 3FN)
-- ====================================================

-- Crear base de datos con charset UTF-8
CREATE DATABASE IF NOT EXISTS crud_empleados CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crud_empleados;

CREATE TABLE nacionalidades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tipos_sangre (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(5) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE estado_civil (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE discapacidades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tipos_cuenta (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bancos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    code VARCHAR(10) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE profesiones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE empleados (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    numero_documento VARCHAR(50) NOT NULL UNIQUE,
    tipo_documento ENUM('CC', 'TI', 'CE', 'PASSPORT') NOT NULL DEFAULT 'CC',
    fecha_expedicion_documento DATE NOT NULL,
    tipo_cuenta_id INT NOT NULL,
    numero_cuenta VARCHAR(50) NOT NULL UNIQUE,
    banco_id INT NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100),
    discapacidad_id INT,
    nacionalidad_id INT NOT NULL,
    tipo_sangre_id INT NOT NULL,
    estado_civil_id INT NOT NULL,
    profesion_id INT NOT NULL,
    descripcion TEXT,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tipo_cuenta_id) REFERENCES tipos_cuenta(id) ON DELETE RESTRICT,
    FOREIGN KEY (banco_id) REFERENCES bancos(id) ON DELETE RESTRICT,
    FOREIGN KEY (discapacidad_id) REFERENCES discapacidades(id) ON DELETE SET NULL,
    FOREIGN KEY (nacionalidad_id) REFERENCES nacionalidades(id) ON DELETE RESTRICT,
    FOREIGN KEY (tipo_sangre_id) REFERENCES tipos_sangre(id) ON DELETE RESTRICT,
    FOREIGN KEY (estado_civil_id) REFERENCES estado_civil(id) ON DELETE RESTRICT,
    FOREIGN KEY (profesion_id) REFERENCES profesiones(id) ON DELETE RESTRICT
);

CREATE INDEX idx_numero_documento ON empleados(numero_documento);
CREATE INDEX idx_estado ON empleados(estado);
CREATE INDEX idx_banco_id ON empleados(banco_id);
CREATE INDEX idx_nacionalidad_id ON empleados(nacionalidad_id);

-- Datos de prueba
INSERT INTO nacionalidades (name) VALUES ('Colombiano'), ('Venezolano'), ('Ecuatoriano'), ('Peruano'), ('Chileno'), ('Argentino');
INSERT INTO tipos_sangre (name) VALUES ('O+'), ('O-'), ('A+'), ('A-'), ('B+'), ('B-'), ('AB+'), ('AB-');
INSERT INTO estado_civil (name) VALUES ('Soltero'), ('Casado'), ('Divorciado'), ('Viudo'), ('Unión Libre');
INSERT INTO discapacidades (name, description) VALUES ('Ninguna', 'Sin discapacidad'), ('Auditiva', 'Sordera o pérdida auditiva'), ('Visual', 'Ceguera o pérdida visual'), ('Motora', 'Limitaciones de movimiento'), ('Cognitiva', 'Limitaciones en capacidades mentales');
INSERT INTO tipos_cuenta (name) VALUES ('Corriente'), ('Ahorros'), ('Nómina');
INSERT INTO bancos (name, code) VALUES ('Banco de Bogotá', 'BOG'), ('BBVA', 'BBVA'), ('Scotiabank', 'SCOT'), ('Banco Caja Social', 'BCS'), ('Banco Occidente', 'BO'), ('Inter Banco', 'INTER'), ('Davivienda', 'DAV'), ('ICBC', 'ICBC');
INSERT INTO profesiones (name) VALUES ('Ingeniero'), ('Abogado'), ('Contador'), ('Médico'), ('Profesor'), ('Técnico'), ('Diseñador'), ('Administrador'), ('Vendedor'), ('Operario');

INSERT INTO empleados (nombre, apellido, numero_documento, tipo_documento, fecha_expedicion_documento, tipo_cuenta_id, numero_cuenta, banco_id, direccion, telefono, correo, discapacidad_id, nacionalidad_id, tipo_sangre_id, estado_civil_id, profesion_id, descripcion)
VALUES
('Juan', 'García López', '1234567890', 'CC', '2015-05-10', 1, '1234567890123', 1, 'Calle 123 #45-67, Bogotá', '3105551234', 'juan@example.com', 1, 1, 3, 1, 1, 'Empleado de prueba del sistema'),
('María', 'Rodríguez Pérez', '9876543210', 'CC', '2016-08-15', 2, '9876543210456', 2, 'Carrera 50 #12-34, Medellín', '3167778899', 'maria@example.com', 1, 1, 4, 2, 2, 'Segunda empleada de prueba');
