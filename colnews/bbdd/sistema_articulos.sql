-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS sistema_articulos;
USE sistema_articulos;

-- Tabla principal de artículos
CREATE TABLE articulos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    autor VARCHAR(255) NOT NULL,
    fecha_publicacion DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de contenidos vinculada a un artículo
CREATE TABLE art_contenidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    articulo_id INT NOT NULL,
    tipo ENUM('paragraph', 'video', 'image') NOT NULL,
    texto TEXT,
    url_recurso VARCHAR(255) DEFAULT 'NA',
    formato VARCHAR(50) DEFAULT 'NA',
    orden INT DEFAULT 0,
    FOREIGN KEY (articulo_id) REFERENCES articulos(id) ON DELETE CASCADE
);

-- 1. Insertar el artículo
INSERT INTO articulos (id, autor, fecha_publicacion) 
VALUES (3, 'Julio Santos', '2026-06-26');

-- 2. Insertar los contenidos asociados
INSERT INTO art_contenidos (articulo_id, tipo, texto, url_recurso, formato) 
VALUES 
(3, 'paragraph', '1este es el contenido de un parrafo', 'NA', 'NA'),
(3, 'video', 'voto-fusil', 'assets/video/duvalier-sanchez-voto-fusil.mp4', 'vertical'),
(3, 'paragraph', '3este es el contenido de un parrafo', 'NA', 'NA');
