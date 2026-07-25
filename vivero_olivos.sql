-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-07-2026 a las 18:43:54
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `vivero_olivos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventarios`
--

CREATE TABLE `inventarios` (
  `id_inventario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `dias_surtido` int(11) NOT NULL,
  `presupuesto_mensual` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventarios`
--

INSERT INTO `inventarios` (`id_inventario`, `nombre`, `dias_surtido`, `presupuesto_mensual`) VALUES
(1, 'Plantas', 20, NULL),
(2, 'Plásticos', 30, 5000.00),
(3, 'Herramientas', 30, 25000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `id_inventario` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio_costo` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock_actual` int(11) NOT NULL,
  `punto_reorden` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `id_inventario`, `nombre`, `precio_costo`, `precio_venta`, `stock_actual`, `punto_reorden`) VALUES
(1, 1, 'Rosal Rojo', 50.00, 120.00, 45, 15),
(2, 1, 'Ficus', 150.00, 300.00, 12, 5),
(3, 1, 'Palma de interiores', 800.00, 1500.00, 3, 2),
(4, 2, 'Maceta Plástico 5L', 15.00, 35.00, 150, 50),
(5, 2, 'Rollo Plástico Invernadero', 300.00, 600.00, 8, 3),
(6, 3, 'Tijeras de podar', 120.00, 250.00, 25, 10),
(7, 3, 'Pala jardinera', 80.00, 180.00, 15, 5),
(8, 3, 'Carretilla Truper', 1200.00, 2500.00, 4, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD PRIMARY KEY (`id_inventario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_inventario` (`id_inventario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_inventario`) REFERENCES `inventarios` (`id_inventario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
