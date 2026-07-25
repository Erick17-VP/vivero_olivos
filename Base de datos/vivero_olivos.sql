-- 1. Eliminar la base de datos anterior si existe
DROP DATABASE IF EXISTS sistema_inventario_taha;

-- 2. Crear la base de datos desde cero
CREATE DATABASE sistema_inventario_taha;

-- 3. Seleccionar la base de datos
USE sistema_inventario_taha;

-- 4. Crear la tabla de Usuarios (Esta era la que faltaba)
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(20) NOT NULL
);

-- 5. Crear la tabla de Productos
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(100) NOT NULL,
    demanda_anual DECIMAL(10,2) NOT NULL,
    costo_pedido DECIMAL(10,2) NOT NULL,
    costo_mantenimiento DECIMAL(10,2) NOT NULL,
    demanda_promedio_lead DECIMAL(10,2) NOT NULL,
    desviacion_lead DECIMAL(10,2) NOT NULL
);

-- 6. Crear la tabla de Resultados
CREATE TABLE resultados_modelos (
    id_calculo INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    cantidad_optima_eoq DECIMAL(10,2),
    costo_total_anual DECIMAL(10,2),
    inventario_seguridad DECIMAL(10,2),
    punto_reorden DECIMAL(10,2),
    fecha_calculo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

-- 7. Insertar usuarios de prueba (Gerente y Empleado)
INSERT INTO usuarios (usuario, password, rol) VALUES 
('jefe', '1234', 'gerente'),
('empleado1', '1234', 'empleado');

-- 8. Insertar un producto de prueba
INSERT INTO productos (nombre_producto, demanda_anual, costo_pedido, costo_mantenimiento, demanda_promedio_lead, desviacion_lead) 
VALUES ('Producto de Prueba A', 1000.00, 10.00, 2.50, 50.00, 5.00);