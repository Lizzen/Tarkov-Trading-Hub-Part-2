-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-05-2023 a las 21:17:30
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `estructura`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_usuario`
--

CREATE TABLE `inventario_usuario` (
  `id_usuario` int(255) NOT NULL,
  `id_inv` int(10) NOT NULL,
  `nombre_item` varchar(255) NOT NULL,
  `pos_x` int(10) NOT NULL,
  `pos_y` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario_usuario`
--

INSERT INTO `inventario_usuario` (`id_usuario`, `id_inv`, `nombre_item`, `pos_x`, `pos_y`) VALUES
(1, 0, 'casco', 16, 5),
(1, 3, 'plate_carrier', 10, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` varchar(255) PRIMARY KEY,
  `nombre` varchar(255) NOT NULL,
  `rareza` varchar(255) NOT NULL,
  `altura` int(10) NOT NULL,
  `anchura` int(10) NOT NULL,
  `tamanyo` varchar(255) NOT NULL,
  --`imagen` image NOT NULL,
  `precioMin` int(10) DEFAULT VALUES 1
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`id`,`nombre`, `rareza`, `altura`, `anchura`, `tamanyo`, `precioMin`) VALUES
('0','casco', 'comun', 2, 2, '2x2', 5),
('1','plate_carrier', 'raro', 3, 3, '3x3', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subastas`
--

CREATE TABLE `subastas` (
  `id_subasta` int(10) NOT NULL,
  `id_usuario` int(255) NOT NULL,
  `nombre_item` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `precio` float NOT NULL,
  `id_licitador` int(255) DEFAULT NULL,
  `fecha_limite` datetime DEFAULT NULL,
  `tiempo_restante` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(255) NOT NULL,
  `nombre_usuario` varchar(255) NOT NULL,
  `rol` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `capacidad_inventario` int(255) NOT NULL,
  `dinero` int(11) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `rol`, `email`, `capacidad_inventario`, `dinero`, `password`) VALUES
(1, 'admin', 1, 'admin@gmail.com', 100, 3400, '$2y$10$pLpmHUCbJ5P6D0qkiyg.P.4pEkFfLdrNWTmORAwasXifrVrLOnTFW'),
(19, 'user', 3, '', 40, 1800, '$2y$10$Nh/z2P.F5x7uVuO//me0EeWk7RSaTbK8Pw98du/46x6aNZDyPhfY.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_mercado`
--

CREATE TABLE `ventas_mercado` (
  `id_venta` int(10) NOT NULL,
  `id_usuario` int(255) NOT NULL,
  `nombre_item` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `precio` int(255) NOT NULL,
  `nombre_intercambio` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas_mercado`
--

INSERT INTO `ventas_mercado` (`id_venta`, `id_usuario`, `nombre_item`, `tipo`, `precio`, `nombre_intercambio`) VALUES
(1, 1, 'casco', 'intercambio', 0, 'casco'),
(2, 1, 'plate_carrier', 'dual', 100, 'casco'),
(6, 19, 'casco', 'dinero', 100, '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `inventario_usuario`
--
ALTER TABLE `inventario_usuario`
  ADD UNIQUE KEY `id_inv` (`id_inv`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_nombre_item` (`nombre_item`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`nombre`);

--
-- Indices de la tabla `subastas`
--
ALTER TABLE `subastas`
  ADD PRIMARY KEY (`id_subasta`);
--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas_mercado`
--
ALTER TABLE `ventas_mercado`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `subastas`
--
ALTER TABLE `subastas`
  MODIFY `id_subasta` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `ventas_mercado`
--
ALTER TABLE `ventas_mercado`
  MODIFY `id_venta` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inventario_usuario`
--
ALTER TABLE `inventario_usuario`
  ADD CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nombre_item` FOREIGN KEY (`nombre_item`) REFERENCES `items` (`nombre`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas_mercado`
--
ALTER TABLE `ventas_mercado`
  ADD CONSTRAINT `fk_id_usuario_mer` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

--
-- Filtros para la tabla `subastas`
--
ALTER TABLE `subastas`
  ADD CONSTRAINT `fk_id_usuario_sub` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
