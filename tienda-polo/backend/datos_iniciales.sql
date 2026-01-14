USE tienda_polo;

-- Usuario administrador (contraseña: admin123)
INSERT INTO usuarios (nombre, email, password, telefono, direccion, tipo) VALUES
('Administrador', 'admin@ralphlauren.com', SHA2('admin123', 256), '555-123-4567', 'Av. Principal 123, CDMX', 'admin');

-- Productos de ejemplo
INSERT INTO productos (nombre, descripcion, precio, precio_original, stock, categoria, imagen_url, destacado) VALUES
('Polo Básico Azul Marino', 'Polo de algodón básico color azul marino, cuello acanalado y logo bordado', 89.99, 99.99, 50, 'polos', 'https://dtcralphlauren.scene7.com/is/image/PoloGSI/s7-1279327_alternate10?$plpDeskRF$', TRUE),
('Polo Clásico Blanco', 'Polo blanco clásico de algodón pima, corte moderno y logo bordado', 79.99, 89.99, 75, 'polos', 'https://i.localised.com/img/cc/product/a015d6d1-ef66-4bc5-a60a-e06b573bc73b_LARGE.jpg', TRUE),
('Polo Elegante Negro', 'Polo negro de algodón premium, ideal para ocasiones especiales', 99.99, 119.99, 30, 'polos', 'https://dtcralphlauren.scene7.com/is/image/PoloGSI/s7-1279324_alternate10?$plpDeskRF$', FALSE),
('Polo Vino Tinto', 'Polo color vino tinto, tejido de alta calidad y durabilidad', 85.99, 95.99, 45, 'polos', 'https://ss261.liverpool.com.mx/xl/1158032780.jpg', FALSE);

-- Categorías
INSERT INTO categorias (nombre, descripcion) VALUES
('polos', 'Polos clásicos de Ralph Lauren'),
('camisas', 'Camisas formales y casuales'),
('sudaderas', 'Sudaderas y hoodies');