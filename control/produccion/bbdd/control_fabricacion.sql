-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-08-2026 a las 18:32:08
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
-- Base de datos: `control_fabricacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_insumos`
--

CREATE TABLE `categorias_insumos` (
  `id_categoria` int(10) UNSIGNED NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias_insumos`
--

INSERT INTO `categorias_insumos` (`id_categoria`, `nombre_categoria`, `descripcion`) VALUES
(1, 'aceros', 'Insumos y perfiles de acero'),
(2, 'aluminios', 'Insumos y perfiles de aluminio'),
(3, 'de coleccion general', 'Artículos y materiales de colección general');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_producto_terminado`
--

CREATE TABLE `inventario_producto_terminado` (
  `id_inventario_pt` int(10) UNSIGNED NOT NULL,
  `id_producto` int(10) UNSIGNED NOT NULL,
  `id_orden` int(10) UNSIGNED DEFAULT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ubicacion_bodega` varchar(100) DEFAULT 'Bodega Principal',
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materiales`
--

CREATE TABLE `materiales` (
  `id_material` int(10) UNSIGNED NOT NULL,
  `codigo_material` varchar(50) NOT NULL,
  `descripcion_material` varchar(200) NOT NULL,
  `unidad_medida` varchar(20) NOT NULL,
  `precio_unitario_defecto` decimal(12,2) NOT NULL DEFAULT 0.00,
  `id_categoria` int(10) UNSIGNED DEFAULT 3,
  `stock_minimo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_actual` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_maximo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `materiales`
--

INSERT INTO `materiales` (`id_material`, `codigo_material`, `descripcion_material`, `unidad_medida`, `precio_unitario_defecto`, `id_categoria`, `stock_minimo`, `stock_actual`, `stock_maximo`, `creado_en`) VALUES
(1, 'MAT-MEL-18', 'Lámina Melamina RH 18mm Cemento', 'PLIEGO', 185000.00, 3, 5.00, 20.00, 50.00, '2026-08-14 18:28:39'),
(2, 'MAT-CANT-01', 'Canto PVC 2mm', 'MTS', 2500.00, 3, 20.00, 15.00, 100.00, '2026-08-14 18:28:39'),
(3, 'MAT-HERR-05', 'Corredera Pesada 45cm', 'PAR', 18000.00, 1, 4.00, 12.00, 30.00, '2026-08-14 18:28:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `id_movimiento` int(10) UNSIGNED NOT NULL,
  `tipo_item` enum('material','producto_terminado') NOT NULL,
  `id_item` int(10) UNSIGNED NOT NULL,
  `tipo_movimiento` enum('salida_orden','entrada_produccion','ajuste','entrada_compra') NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `id_orden` int(10) UNSIGNED DEFAULT NULL,
  `observacion` text DEFAULT NULL,
  `fecha_movimiento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_fabricacion`
--

CREATE TABLE `ordenes_fabricacion` (
  `id_orden` int(10) UNSIGNED NOT NULL,
  `numero_orden` varchar(20) NOT NULL,
  `id_cliente` int(10) UNSIGNED NOT NULL,
  `id_asesor` int(10) UNSIGNED NOT NULL,
  `id_fabricante` int(10) UNSIGNED NOT NULL,
  `id_operario` int(10) UNSIGNED DEFAULT NULL,
  `id_producto` int(10) UNSIGNED NOT NULL,
  `unidades` int(11) NOT NULL DEFAULT 1,
  `estado` enum('simulacion','planeacion','activa','en pasillo','en ejecucion','suspendida','cancelada','terminada') NOT NULL DEFAULT 'planeacion',
  `fecha_pedido` date NOT NULL,
  `fecha_entrega` date NOT NULL,
  `costo_subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `costo_mod` decimal(12,2) NOT NULL DEFAULT 0.00,
  `costo_cif` decimal(12,2) NOT NULL DEFAULT 0.00,
  `porcentaje_utilidad` decimal(5,2) NOT NULL DEFAULT 0.00,
  `monto_utilidad` decimal(12,2) GENERATED ALWAYS AS (
    CASE 
      WHEN `porcentaje_utilidad` >= 100 THEN 0.00
      ELSE ROUND(((`costo_subtotal` + `costo_mod` + `costo_cif`) / (1 - (`porcentaje_utilidad` / 100))) - (`costo_subtotal` + `costo_mod` + `costo_cif`), 2)
    END
  ) STORED,
  `monto_total` decimal(12,2) GENERATED ALWAYS AS (
    CASE 
      WHEN `porcentaje_utilidad` >= 100 THEN (`costo_subtotal` + `costo_mod` + `costo_cif`)
      ELSE ROUND((`costo_subtotal` + `costo_mod` + `costo_cif`) / (1 - (`porcentaje_utilidad` / 100)), 2)
    END
  ) STORED,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ordenes_fabricacion`
--

INSERT INTO `ordenes_fabricacion` (`id_orden`, `numero_orden`, `id_cliente`, `id_asesor`, `id_fabricante`, `id_operario`, `id_producto`, `unidades`, `estado`, `fecha_pedido`, `fecha_entrega`, `costo_subtotal`, `costo_mod`, `costo_cif`, `porcentaje_utilidad`, `creado_en`) VALUES
(19, 'ORD-2026-0001', 1, 2, 3, NULL, 1, 1, 'planeacion', '2026-08-15', '2026-08-15', 390500.00, 0.00, 0.00, 25.00, '2026-08-15 16:29:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_fabricacion_detalles`
--

CREATE TABLE `orden_fabricacion_detalles` (
  `id_detalle` int(10) UNSIGNED NOT NULL,
  `id_orden` int(10) UNSIGNED NOT NULL,
  `id_material` int(10) UNSIGNED NOT NULL,
  `medidas` varchar(50) DEFAULT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT 1.00,
  `cantidad_consumida` decimal(10,2) DEFAULT NULL,
  `valor_unitario` decimal(12,2) NOT NULL DEFAULT 0.00,
  `valor_total` decimal(12,2) GENERATED ALWAYS AS (`cantidad` * `valor_unitario`) STORED,
  `es_destacado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `orden_fabricacion_detalles`
--

INSERT INTO `orden_fabricacion_detalles` (`id_detalle`, `id_orden`, `id_material`, `medidas`, `cantidad`, `cantidad_consumida`, `valor_unitario`, `es_destacado`) VALUES
(18, 19, 2, '2400 mm', 1.00, 1.00, 2500.00, 0),
(19, 19, 3, '45 cm', 1.00, 1.00, 18000.00, 0),
(20, 19, 1, '2440x1830 mm', 2.00, 2.00, 185000.00, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_catalogo`
--

CREATE TABLE `productos_catalogo` (
  `id_producto` int(10) UNSIGNED NOT NULL,
  `codigo_referencia` varchar(50) NOT NULL,
  `nombre_producto` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos_catalogo`
--

INSERT INTO `productos_catalogo` (`id_producto`, `codigo_referencia`, `nombre_producto`, `descripcion`, `creado_en`) VALUES
(1, 'MUE-ELEC-01', 'Escritorio Ejecutivo en L', 'Escritorio con acabado en melamina de 18mm y patas metálicas', '2026-08-14 18:28:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `terceros`
--

CREATE TABLE `terceros` (
  `id_tercero` int(10) UNSIGNED NOT NULL,
  `documento` varchar(20) DEFAULT NULL,
  `nombre` varchar(120) NOT NULL,
  `rol` enum('CLIENTE','ASESOR','FABRICANTE','OPERARIO') NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `terceros`
--

INSERT INTO `terceros` (`id_tercero`, `documento`, `nombre`, `rol`, `telefono`, `creado_en`) VALUES
(1, '1090123456', 'Distribuidora del Norte SAS', 'CLIENTE', '3001234567', '2026-08-14 18:28:39'),
(2, '1098765432', 'Carlos Mendoza', 'ASESOR', '3109876543', '2026-08-14 18:28:39'),
(3, '9005554441', 'Taller Central de Carpintería', 'FABRICANTE', '3205554444', '2026-08-14 18:28:39'),
(4, '8812345678', 'Juan Pérez (Planta)', 'OPERARIO', '3151112233', '2026-08-14 18:28:39');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias_insumos`
--
ALTER TABLE `categorias_insumos`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre_categoria` (`nombre_categoria`);

--
-- Indices de la tabla `inventario_producto_terminado`
--
ALTER TABLE `inventario_producto_terminado`
  ADD PRIMARY KEY (`id_inventario_pt`),
  ADD KEY `fk_ipt_producto` (`id_producto`),
  ADD KEY `fk_ipt_orden` (`id_orden`);

--
-- Indices de la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD PRIMARY KEY (`id_material`),
  ADD UNIQUE KEY `codigo_material` (`codigo_material`),
  ADD KEY `fk_material_categoria` (`id_categoria`);

--
-- Indices de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `fk_mov_orden` (`id_orden`);

--
-- Indices de la tabla `ordenes_fabricacion`
--
ALTER TABLE `ordenes_fabricacion`
  ADD PRIMARY KEY (`id_orden`),
  ADD UNIQUE KEY `numero_orden` (`numero_orden`),
  ADD KEY `fk_orden_cliente` (`id_cliente`),
  ADD KEY `fk_orden_asesor` (`id_asesor`),
  ADD KEY `fk_orden_fabricante` (`id_fabricante`),
  ADD KEY `fk_orden_operario` (`id_operario`),
  ADD KEY `fk_orden_producto` (`id_producto`);

--
-- Indices de la tabla `orden_fabricacion_detalles`
--
ALTER TABLE `orden_fabricacion_detalles`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `fk_detalle_orden` (`id_orden`),
  ADD KEY `fk_detalle_material` (`id_material`);

--
-- Indices de la tabla `productos_catalogo`
--
ALTER TABLE `productos_catalogo`
  ADD PRIMARY KEY (`id_producto`),
  ADD UNIQUE KEY `codigo_referencia` (`codigo_referencia`);

--
-- Indices de la tabla `terceros`
--
ALTER TABLE `terceros`
  ADD PRIMARY KEY (`id_tercero`),
  ADD KEY `idx_terceros_rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias_insumos`
--
ALTER TABLE `categorias_insumos`
  MODIFY `id_categoria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `inventario_producto_terminado`
--
ALTER TABLE `inventario_producto_terminado`
  MODIFY `id_inventario_pt` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id_material` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `id_movimiento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ordenes_fabricacion`
--
ALTER TABLE `ordenes_fabricacion`
  MODIFY `id_orden` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `orden_fabricacion_detalles`
--
ALTER TABLE `orden_fabricacion_detalles`
  MODIFY `id_detalle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `productos_catalogo`
--
ALTER TABLE `productos_catalogo`
  MODIFY `id_producto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `terceros`
--
ALTER TABLE `terceros`
  MODIFY `id_tercero` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inventario_producto_terminado`
--
ALTER TABLE `inventario_producto_terminado`
  ADD CONSTRAINT `fk_ipt_orden` FOREIGN KEY (`id_orden`) REFERENCES `ordenes_fabricacion` (`id_orden`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ipt_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos_catalogo` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD CONSTRAINT `fk_material_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias_insumos` (`id_categoria`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD CONSTRAINT `fk_mov_orden` FOREIGN KEY (`id_orden`) REFERENCES `ordenes_fabricacion` (`id_orden`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `ordenes_fabricacion`
--
ALTER TABLE `ordenes_fabricacion`
  ADD CONSTRAINT `fk_orden_asesor` FOREIGN KEY (`id_asesor`) REFERENCES `terceros` (`id_tercero`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orden_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `terceros` (`id_tercero`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orden_fabricante` FOREIGN KEY (`id_fabricante`) REFERENCES `terceros` (`id_tercero`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orden_operario` FOREIGN KEY (`id_operario`) REFERENCES `terceros` (`id_tercero`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orden_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos_catalogo` (`id_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `orden_fabricacion_detalles`
--
ALTER TABLE `orden_fabricacion_detalles`
  ADD CONSTRAINT `fk_detalle_material` FOREIGN KEY (`id_material`) REFERENCES `materiales` (`id_material`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_orden` FOREIGN KEY (`id_orden`) REFERENCES `ordenes_fabricacion` (`id_orden`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
