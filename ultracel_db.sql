-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-03-2026 a las 18:03:56
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ultracel_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taller_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `taller_id`, `nombre`, `telefono`, `email`, `direccion`, `created_at`, `updated_at`) VALUES
(1, 1, 'Juan Pérez (Cliente Prueba)', '555-123-4567', 'cliente@test.com', NULL, '2026-02-16 23:51:40', '2026-02-16 23:51:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taller_id` bigint(20) UNSIGNED NOT NULL,
  `proveedor_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `folio_factura_proveedor` varchar(255) DEFAULT NULL,
  `fecha_compra` date NOT NULL,
  `total_pagado` decimal(10,2) NOT NULL,
  `notas` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_detalles`
--

CREATE TABLE `compra_detalles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `compra_id` bigint(20) UNSIGNED NOT NULL,
  `inventario_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo_unitario` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taller_id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED NOT NULL,
  `tipo` enum('celular','tablet','laptop','otro') NOT NULL,
  `marca` varchar(255) NOT NULL,
  `modelo` varchar(255) NOT NULL,
  `imei_serie` varchar(255) DEFAULT NULL,
  `contrasena_desbloqueo` varchar(255) DEFAULT NULL,
  `detalles_fisicos` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taller_id` bigint(20) UNSIGNED NOT NULL,
  `codigo_barras` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `costo_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock_actual` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 2,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `taller_id`, `codigo_barras`, `nombre`, `descripcion`, `costo_compra`, `precio_venta`, `stock_actual`, `stock_minimo`, `created_at`, `updated_at`) VALUES
(1, 1, 'LCD-IPH-11', 'Pantalla iPhone 11 Original', NULL, 800.00, 1500.00, 10, 2, '2026-02-16 23:51:40', '2026-02-16 23:51:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_02_16_173301_create_saas_tables', 1),
(2, '2026_02_16_173303_create_operational_tables', 1),
(3, '2026_02_16_173307_create_transactional_tables', 1),
(4, '2026_02_16_175401_create_supply_chain_tables', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes`
--

CREATE TABLE `planes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio_mensual` decimal(10,2) NOT NULL,
  `max_usuarios` int(11) NOT NULL DEFAULT 1,
  `max_sucursales` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `planes`
--

INSERT INTO `planes` (`id`, `nombre`, `precio_mensual`, `max_usuarios`, `max_sucursales`, `created_at`, `updated_at`) VALUES
(1, 'Plan Emprendedor', 299.00, 3, 1, '2026-02-16 23:51:39', '2026-02-16 23:51:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taller_id` bigint(20) UNSIGNED NOT NULL,
  `nombre_empresa` varchar(255) NOT NULL,
  `nombre_contacto` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `rfc_tax_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reparaciones`
--

CREATE TABLE `reparaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taller_id` bigint(20) UNSIGNED NOT NULL,
  `equipo_id` bigint(20) UNSIGNED NOT NULL,
  `tecnico_id` bigint(20) UNSIGNED DEFAULT NULL,
  `falla_reportada` varchar(255) NOT NULL,
  `diagnostico_tecnico` text DEFAULT NULL,
  `estado` enum('recibido','diagnosticado','espera_pieza','listo','entregado','cancelado') NOT NULL DEFAULT 'recibido',
  `mano_obra_costo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_estimado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_promesa` timestamp NULL DEFAULT NULL,
  `fecha_entrega_real` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reparacion_insumos`
--

CREATE TABLE `reparacion_insumos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reparacion_id` bigint(20) UNSIGNED NOT NULL,
  `inventario_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_cobrado_historico` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talleres`
--

CREATE TABLE `talleres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `plan_id` bigint(20) UNSIGNED NOT NULL,
  `nombre_negocio` varchar(255) NOT NULL,
  `rfc_tax_id` varchar(255) DEFAULT NULL,
  `configuracion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`configuracion`)),
  `fecha_vencimiento_licencia` date DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `talleres`
--

INSERT INTO `talleres` (`id`, `plan_id`, `nombre_negocio`, `rfc_tax_id`, `configuracion`, `fecha_vencimiento_licencia`, `activo`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ultracel Demo Lab', 'XAXX010101000', NULL, '2027-02-16', 1, '2026-02-16 23:51:39', '2026-02-16 23:51:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taller_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(255) NOT NULL DEFAULT 'vendedor',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `taller_id`, `name`, `email`, `email_verified_at`, `password`, `rol`, `activo`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin Ultracel', 'admin@ultracel.com', NULL, '$2y$12$IpwE2cNhBBE54WrJSZLyUOfMq.qJ52/DvL5y39hWBZqAyizoqCkJq', 'admin', 1, NULL, '2026-02-16 23:51:40', '2026-02-16 23:51:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taller_id` bigint(20) UNSIGNED NOT NULL,
  `vendedor_id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_detalles`
--

CREATE TABLE `venta_detalles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venta_id` bigint(20) UNSIGNED NOT NULL,
  `inventario_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario_historico` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clientes_taller_id_foreign` (`taller_id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compras_taller_id_foreign` (`taller_id`),
  ADD KEY `compras_proveedor_id_foreign` (`proveedor_id`),
  ADD KEY `compras_usuario_id_foreign` (`usuario_id`);

--
-- Indices de la tabla `compra_detalles`
--
ALTER TABLE `compra_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compra_detalles_compra_id_foreign` (`compra_id`),
  ADD KEY `compra_detalles_inventario_id_foreign` (`inventario_id`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipos_taller_id_foreign` (`taller_id`),
  ADD KEY `equipos_cliente_id_foreign` (`cliente_id`),
  ADD KEY `equipos_imei_serie_index` (`imei_serie`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventario_taller_id_foreign` (`taller_id`),
  ADD KEY `inventario_codigo_barras_index` (`codigo_barras`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `planes`
--
ALTER TABLE `planes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proveedores_taller_id_foreign` (`taller_id`);

--
-- Indices de la tabla `reparaciones`
--
ALTER TABLE `reparaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reparaciones_taller_id_foreign` (`taller_id`),
  ADD KEY `reparaciones_equipo_id_foreign` (`equipo_id`),
  ADD KEY `reparaciones_tecnico_id_foreign` (`tecnico_id`);

--
-- Indices de la tabla `reparacion_insumos`
--
ALTER TABLE `reparacion_insumos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reparacion_insumos_reparacion_id_foreign` (`reparacion_id`),
  ADD KEY `reparacion_insumos_inventario_id_foreign` (`inventario_id`);

--
-- Indices de la tabla `talleres`
--
ALTER TABLE `talleres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talleres_plan_id_foreign` (`plan_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_taller_id_foreign` (`taller_id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ventas_taller_id_foreign` (`taller_id`),
  ADD KEY `ventas_vendedor_id_foreign` (`vendedor_id`),
  ADD KEY `ventas_cliente_id_foreign` (`cliente_id`);

--
-- Indices de la tabla `venta_detalles`
--
ALTER TABLE `venta_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_detalles_venta_id_foreign` (`venta_id`),
  ADD KEY `venta_detalles_inventario_id_foreign` (`inventario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compra_detalles`
--
ALTER TABLE `compra_detalles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `planes`
--
ALTER TABLE `planes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reparaciones`
--
ALTER TABLE `reparaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reparacion_insumos`
--
ALTER TABLE `reparacion_insumos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `talleres`
--
ALTER TABLE `talleres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `venta_detalles`
--
ALTER TABLE `venta_detalles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `compras_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `compras_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `compra_detalles`
--
ALTER TABLE `compra_detalles`
  ADD CONSTRAINT `compra_detalles_compra_id_foreign` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `compra_detalles_inventario_id_foreign` FOREIGN KEY (`inventario_id`) REFERENCES `inventario` (`id`);

--
-- Filtros para la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `equipos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `equipos_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD CONSTRAINT `proveedores_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reparaciones`
--
ALTER TABLE `reparaciones`
  ADD CONSTRAINT `reparaciones_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`),
  ADD CONSTRAINT `reparaciones_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`),
  ADD CONSTRAINT `reparaciones_tecnico_id_foreign` FOREIGN KEY (`tecnico_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `reparacion_insumos`
--
ALTER TABLE `reparacion_insumos`
  ADD CONSTRAINT `reparacion_insumos_inventario_id_foreign` FOREIGN KEY (`inventario_id`) REFERENCES `inventario` (`id`),
  ADD CONSTRAINT `reparacion_insumos_reparacion_id_foreign` FOREIGN KEY (`reparacion_id`) REFERENCES `reparaciones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `talleres`
--
ALTER TABLE `talleres`
  ADD CONSTRAINT `talleres_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `planes` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `ventas_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`),
  ADD CONSTRAINT `ventas_vendedor_id_foreign` FOREIGN KEY (`vendedor_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `venta_detalles`
--
ALTER TABLE `venta_detalles`
  ADD CONSTRAINT `venta_detalles_inventario_id_foreign` FOREIGN KEY (`inventario_id`) REFERENCES `inventario` (`id`),
  ADD CONSTRAINT `venta_detalles_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
