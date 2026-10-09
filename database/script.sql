CREATE DATABASE IF NOT EXISTS `reportes-urbanos`;
USE `reportes-urbanos`;

-- Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  rol ENUM('ciudadano', 'administrador') DEFAULT 'ciudadano',
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categorías
CREATE TABLE IF NOT EXISTS categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  descripcion VARCHAR(200)
);

-- Reportes
CREATE TABLE IF NOT EXISTS reportes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  categoria_id INT NOT NULL,
  descripcion TEXT,
  foto VARCHAR(255),
  latitud DECIMAL(10, 8),
  longitud DECIMAL(11, 8),
  estado ENUM('recibido', 'en proceso', 'resuelto') DEFAULT 'recibido',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Verificaciones
CREATE TABLE IF NOT EXISTS verificaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reporte_id INT NOT NULL,
  usuario_id INT NOT NULL,
  tipo ENUM('verificacion', 'denuncia') NOT NULL,
  comentario TEXT,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (reporte_id) REFERENCES reportes(id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Categorías
INSERT INTO categorias (nombre, descripcion) VALUES
('Luminarias dañadas', 'Luminarias que no funcionan o están en mal estado'),
('Basura acumulada', 'Acumulación de basura en calles o espacios públicos'),
('Aceras y calzadas en mal estado', 'Aceras o calzadas deterioradas, con baches o grietas'),
('Obras públicas inconclusas', 'Obras públicas que no han sido terminadas');