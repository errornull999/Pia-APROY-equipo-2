-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-05-2026 a las 04:50:34
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de datos: `solace_db`
--
CREATE DATABASE IF NOT EXISTS `solace_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `solace_db`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `fotografos_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada','completada') DEFAULT 'pendiente',
  `total` decimal(10,2) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `usuario_id`, `servicio_id`, `fotografos_id`, `fecha`, `hora`, `estado`, `total`, `fecha_creacion`) VALUES
(4, 9, 6, 1, '2026-07-17', '16:00:00', 'completada', '4500.00', '2026-05-04 01:07:23'),
(5, 9, 1, 1, '2026-06-18', '09:00:00', 'completada', '500.00', '2026-05-04 01:43:42'),
(6, 7, 3, 3, '2026-05-19', '15:00:00', 'completada', '2500.00', '2026-05-04 03:34:35'),
(7, 9, 7, 2, '2026-06-09', '11:00:00', 'confirmada', '2500.00', '2026-05-04 14:39:46'),
(8, 9, 3, 1, '2026-05-19', '14:00:00', 'confirmada', '2500.00', '2026-05-04 14:40:53'),
(9, 10, 5, 2, '2026-05-29', '12:00:00', 'confirmada', '5000.00', '2026-05-04 15:10:12'),
(10, 9, 1, 1, '2026-05-07', '09:00:00', 'confirmada', '500.00', '2026-05-04 15:49:49'),
(11, 10, 3, 3, '2026-05-12', '15:00:00', 'completada', '2500.00', '2026-05-12 16:08:43'),
(12, 10, 4, 2, '2026-05-28', '14:00:00', 'confirmada', '3000.00', '2026-05-12 16:43:06'),
(13, 11, 6, 1, '2026-05-28', '14:00:00', 'confirmada', '4500.00', '2026-05-12 16:47:15'),
(14, 11, 3, 2, '2026-05-14', '09:00:00', 'confirmada', '2500.00', '2026-05-14 17:30:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotografos`
--

CREATE TABLE `fotografos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `experiencia_anos` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `fotografos`
--

INSERT INTO `fotografos` (`id`, `nombre`, `especialidad`, `experiencia_anos`, `activo`) VALUES
(1, 'Carlos Mendoza', 'Fotógrafo profesional con 10 años de experiencia', 10, 1),
(2, 'Ana García', 'Especialista en iluminación natural', 8, 1),
(3, 'mariana', 'especialista en enfibhrt', 2, 1),
(99, 'Pendiente de Asignar', 'La administración asignará un fotógrafo pronto', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `cita_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia') DEFAULT 'efectivo',
  `estado_pago` enum('pendiente','pagado','reembolsado') DEFAULT 'pendiente',
  `fecha_pago` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `duracion_minutos` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `duracion_minutos`, `precio`, `activo`) VALUES
(1, 'Bebés', 'Sesión fotográfica para recién nacidos y bebés hasta 2 años.', 60, '500.00', 1),
(2, 'Bodas', '', 120, '4000.00', 1),
(3, 'Graduación', 'Fotos profesionales de graduación.', 60, '2500.00', 1),
(4, 'Parejas', 'Sesión romántica para parejas.', 90, '3000.00', 1),
(5, 'Productos', 'Fotografía profesional de productos comerciales.', 120, '5000.00', 1),
(6, 'Familia', 'Sesión familiar para todos los integrantes.', 90, '4500.00', 1),
(7, '15 Años', '', 60, '2500.00', 1),
(8, 'Individuales', 'Fotos personales para una sola persona ', 60, '2000.00', 1),
(9, 'Otro', 'Cualquier otro tipo de secion que estara sujeta a aprobación previa, con tiempo de duracion y precio variable', 120, '5000.00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('cliente','admin') DEFAULT 'cliente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_completo`, `email`, `password`, `rol`, `fecha_registro`) VALUES
(7, 'administrador', 'admin@admin.com', '$2y$10$UPDMCkm0ucxzamb62w9a9udhZEyIJLUaEbZNB9AG41zrMyBDg7//K', 'admin', '2026-05-04 00:00:03'),
(8, 'marina', 'mj3712653@gmail.com', '$2y$10$A.WhCUkoTHK4.WnMBdizFOPCEEpACHG1QoyiPnSiIvv5mwcrOBYky', 'cliente', '2026-05-04 00:14:34'),
(9, 'mariana', 'mm@mari.com', '$2y$10$tMWqMm7XqVduPeaCOs.z3.7VrA1KdULMtTorx92qv1iSUyBXx.9Yu', 'cliente', '2026-05-04 00:19:50'),
(10, 'isaid', 'hola@m', '$2y$10$CYthIhXm8Bx2LdObVN7nLea35fPPEB4N6ZyIjcBJaWT/.4LrjbQ6W', 'cliente', '2026-05-04 15:08:59'),
(11, 'Prueba', 'hprueba@s', '$2y$10$qvPHeX.m63cMwf1IfrfDHO0e9IqaFmaNOyuVW1j70UHSSusXVuNjW', 'cliente', '2026-05-12 16:45:33'),
(12, 'Isaid Harim', 'prueba@gmail', '$2y$10$l9JwfdM3zdy.MJ3VdFQr4.W2BYYbmgQlZv1azydOlIxx1MD/1z/uK', 'cliente', '2026-05-15 18:20:23');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_citas_completas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_citas_completas` (
`cita_id` int(11)
,`cliente` varchar(100)
,`cliente_email` varchar(100)
,`servicio` varchar(50)
,`duracion_minutos` int(11)
,`precio_servicio` decimal(10,2)
,`fotografo` varchar(100)
,`fecha` date
,`hora` time
,`estado` enum('pendiente','confirmada','cancelada','completada')
,`total` decimal(10,2)
,`estado_pago` enum('pendiente','pagado','reembolsado')
,`monto_pagado` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_citas_completas`
--
DROP TABLE IF EXISTS `vista_citas_completas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_citas_completas`  AS SELECT `c`.`id` AS `cita_id`, `u`.`nombre_completo` AS `cliente`, `u`.`email` AS `cliente_email`, `s`.`nombre` AS `servicio`, `s`.`duracion_minutos` AS `duracion_minutos`, `s`.`precio` AS `precio_servicio`, `f`.`nombre` AS `fotografo`, `c`.`fecha` AS `fecha`, `c`.`hora` AS `hora`, `c`.`estado` AS `estado`, `c`.`total` AS `total`, `p`.`estado_pago` AS `estado_pago`, `p`.`monto` AS `monto_pagado` FROM ((((`citas` `c` join `usuarios` `u` on(`c`.`usuario_id` = `u`.`id`)) join `servicios` `s` on(`c`.`servicio_id` = `s`.`id`)) join `fotografos` `f` on(`c`.`fotografos_id` = `f`.`id`)) left join `pagos` `p` on(`c`.`id` = `p`.`cita_id`))  ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `servicio_id` (`servicio_id`),
  ADD KEY `fotografos_id` (`fotografos_id`);

--
-- Indices de la tabla `fotografos`
--
ALTER TABLE `fotografos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cita_id` (`cita_id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `fotografos`
--
ALTER TABLE `fotografos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`),
  ADD CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`fotografos_id`) REFERENCES `fotografos` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`) ON DELETE CASCADE;
COMMIT;
