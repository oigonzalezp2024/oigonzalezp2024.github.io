
-- TABLAS DE PRIMER NIVEL --

CREATE DATABASE IF NOT EXISTS `control_fabricacion`; 

CREATE TABLE IF NOT EXISTS `control_fabricacion`.`personas` (
  `id_persona` int(10) UNSIGNED NOT NULL,
  `documento` varchar(20) DEFAULT NULL,
  `nombre` varchar(120) NOT NULL,
  `rol` enum('CLIENTE','ASESOR','FABRICANTE','OPERARIO') NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `control_fabricacion`.`productos_catalogo` (
  `id_producto` int(10) UNSIGNED NOT NULL,
  `codigo_referencia` varchar(50) NOT NULL,
  `nombre_producto` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `control_fabricacion`.`categorias_insumos` (
  `id_categoria` int(10) UNSIGNED NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLAS DE SEGUNDO NIVEL --

CREATE TABLE IF NOT EXISTS `control_fabricacion`.`materiales` (
  `id_material` int(10) UNSIGNED NOT NULL,
  `codigo_material` varchar(50) NOT NULL,
  `descripcion_material` varchar(200) NOT NULL,
  `unidad_medida` varchar(20) NOT NULL,
  `precio_unitario_defecto` decimal(12,2) NOT NULL DEFAULT 0.00,
  `categoria_id` int(10) UNSIGNED DEFAULT 3,
  `stock_minimo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_actual` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_maximo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `control_fabricacion`.`ordenes_fabricacion` (
  `id_orden` int(10) UNSIGNED NOT NULL,
  `numero_orden` varchar(20) NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `asesor_id` int(10) UNSIGNED NOT NULL,
  `fabricante_id` int(10) UNSIGNED NOT NULL,
  `operario_id` int(10) UNSIGNED DEFAULT NULL,
  `producto_id` int(10) UNSIGNED NOT NULL,
  `unidades` int(11) NOT NULL DEFAULT 1,
  `estado` enum('simulacion','planeacion','activa','en pasillo','en ejecucion','suspendida','cancelada','terminada') NOT NULL DEFAULT 'planeacion',
  `fecha_pedido` date NOT NULL,
  `fecha_entrega` date NOT NULL,
  `costo_subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `costo_mod` decimal(12,2) NOT NULL DEFAULT 0.00,
  `costo_cif` decimal(12,2) NOT NULL DEFAULT 0.00,
  `porcentaje_utilidad` decimal(5,2) NOT NULL DEFAULT 0.00,
  `monto_utilidad` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monto_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLAS DE TERCER NIVEL --

CREATE TABLE IF NOT EXISTS `control_fabricacion`.`orden_fabricacion_detalles` (
  `id_detalle` int(10) UNSIGNED NOT NULL,
  `orden_id` int(10) UNSIGNED NOT NULL,
  `material_id` int(10) UNSIGNED NOT NULL,
  `medidas` varchar(50) DEFAULT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT 1.00,
  `cantidad_consumida` decimal(10,2) DEFAULT NULL,
  `valor_unitario` decimal(12,2) NOT NULL DEFAULT 0.00,
  `valor_total` decimal(12,2) GENERATED ALWAYS AS (`cantidad` * `valor_unitario`) STORED,
  `es_destacado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `control_fabricacion`.`movimientos_inventario` (
  `id_movimiento` int(10) UNSIGNED NOT NULL,
  `tipo_item` enum('material','producto_terminado') NOT NULL,
  `id_item` int(10) UNSIGNED NOT NULL,
  `tipo_movimiento` enum('salida_orden','entrada_produccion','ajuste','entrada_compra') NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `orden_id` int(10) UNSIGNED DEFAULT NULL,
  `observacion` text DEFAULT NULL,
  `fecha_movimiento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `control_fabricacion`.`inventario_producto_terminado` (
  `id_inventario_pt` int(10) UNSIGNED NOT NULL,
  `producto_id` int(10) UNSIGNED NOT NULL,
  `orden_id` int(10) UNSIGNED DEFAULT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ubicacion_bodega` varchar(100) DEFAULT 'Bodega Principal',
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `control_fabricacion`.`categorias_insumos`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre_categoria` (`nombre_categoria`);

ALTER TABLE `control_fabricacion`.`inventario_producto_terminado`
  ADD PRIMARY KEY (`id_inventario_pt`),
  ADD KEY `fk_ipt_producto` (`producto_id`),
  ADD KEY `fk_ipt_orden` (`orden_id`);

ALTER TABLE `control_fabricacion`.`materiales`
  ADD PRIMARY KEY (`id_material`),
  ADD UNIQUE KEY `codigo_material` (`codigo_material`),
  ADD KEY `fk_material_categoria` (`categoria_id`);

ALTER TABLE `control_fabricacion`.`movimientos_inventario`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `fk_mov_orden` (`orden_id`);

ALTER TABLE `control_fabricacion`.`ordenes_fabricacion`
  ADD PRIMARY KEY (`id_orden`),
  ADD UNIQUE KEY `numero_orden` (`numero_orden`),
  ADD KEY `fk_orden_cliente` (`cliente_id`),
  ADD KEY `fk_orden_asesor` (`asesor_id`),
  ADD KEY `fk_orden_fabricante` (`fabricante_id`),
  ADD KEY `fk_orden_operario` (`operario_id`),
  ADD KEY `fk_orden_producto` (`producto_id`);

ALTER TABLE `control_fabricacion`.`orden_fabricacion_detalles`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `fk_detalle_orden` (`orden_id`),
  ADD KEY `fk_detalle_material` (`material_id`);

ALTER TABLE `control_fabricacion`.`productos_catalogo`
  ADD PRIMARY KEY (`id_producto`),
  ADD UNIQUE KEY `codigo_referencia` (`codigo_referencia`);

ALTER TABLE `control_fabricacion`.`personas`
  ADD PRIMARY KEY (`id_persona`),
  ADD KEY `idx_personas_rol` (`rol`);

ALTER TABLE `control_fabricacion`.`categorias_insumos`
  MODIFY `id_categoria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `control_fabricacion`.`inventario_producto_terminado`
  MODIFY `id_inventario_pt` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `control_fabricacion`.`materiales`
  MODIFY `id_material` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `control_fabricacion`.`movimientos_inventario`
  MODIFY `id_movimiento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `control_fabricacion`.`ordenes_fabricacion`
  MODIFY `id_orden` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `control_fabricacion`.`orden_fabricacion_detalles`
  MODIFY `id_detalle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `control_fabricacion`.`productos_catalogo`
  MODIFY `id_producto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `control_fabricacion`.`personas`
  MODIFY `id_persona` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `control_fabricacion`.`inventario_producto_terminado`
  ADD CONSTRAINT `fk_ipt_orden` FOREIGN KEY (`orden_id`) REFERENCES `ordenes_fabricacion` (`id_orden`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ipt_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos_catalogo` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `control_fabricacion`.`materiales`
  ADD CONSTRAINT `fk_material_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_insumos` (`id_categoria`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `control_fabricacion`.`movimientos_inventario`
  ADD CONSTRAINT `fk_mov_orden` FOREIGN KEY (`orden_id`) REFERENCES `ordenes_fabricacion` (`id_orden`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `control_fabricacion`.`ordenes_fabricacion`
  ADD CONSTRAINT `fk_orden_asesor` FOREIGN KEY (`asesor_id`) REFERENCES `personas` (`id_persona`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orden_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `personas` (`id_persona`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orden_fabricante` FOREIGN KEY (`fabricante_id`) REFERENCES `personas` (`id_persona`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orden_operario` FOREIGN KEY (`operario_id`) REFERENCES `personas` (`id_persona`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orden_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos_catalogo` (`id_producto`) ON UPDATE CASCADE;

ALTER TABLE `control_fabricacion`.`orden_fabricacion_detalles`
  ADD CONSTRAINT `fk_detalle_material` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id_material`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_orden` FOREIGN KEY (`orden_id`) REFERENCES `ordenes_fabricacion` (`id_orden`) ON DELETE CASCADE ON UPDATE CASCADE;

-- ------------------------------------------------------------------------------
-- 1. CATEGORÍAS DE INSUMOS
-- ------------------------------------------------------------------------------
INSERT INTO `control_fabricacion`.`categorias_insumos` (`id_categoria`, `nombre_categoria`, `descripcion`) VALUES
(1, 'Madera y Tableros', 'Láminas de melamina, aglomerados y MDF'),
(2, 'Herrajes y Accesorios', 'Correderas, bisagras y jaladores'),
(3, 'Insumos Generales', 'Cantos, pegamentos y tornillería'),
(4, 'Aceros Inoxidables', 'Láminas, tubería y perfiles en AISI 304 y 430'),
(5, 'Componentes Térmicos y Gas', 'Quemadores, válvulas, termostatos y resistencias'),
(6, 'Mecanismos y Rodamientos', 'Ruedas industriales, engranajes, poleas y rodamientos'),
(7, 'Aislamientos y Vidrios', 'Lana de vidrio, fibra cerámica y vidrios templados');

-- ------------------------------------------------------------------------------
-- 2. PRODUCTOS DEL CATÁLOGO
-- ------------------------------------------------------------------------------
INSERT INTO `control_fabricacion`.`productos_catalogo` (`id_producto`, `codigo_referencia`, `nombre_producto`, `descripcion`) VALUES
(1, 'MUE-ELEC-01', 'Escritorio Ejecutivo en L', 'Mobiliario modular en melamina con torre de cajones'),
(2, 'COC-IND-04', 'Estufa Industrial 4 Puestos con Horno', 'Cocina industrial en acero inox 304 con quemadores de alta potencia'),
(3, 'CARR-PER-01', 'Carrito de Perros y Hamburguesas Premium', 'Unidad móvil con plancha, freidora, baño maría y bodega inferior'),
(4, 'MOL-GRA-02', 'Molino de Granos Eléctrico 2HP', 'Molino industrial para maíz, café y cereales con tolva inox'),
(5, 'MOST-PAN-03', 'Mostrador Vitrina Panadería Neutro 1.5m', 'Vitrina exhibidora con vidrios templados y entrepaños de exhibición'),
(6, 'CAL-PAN-01', 'Calentador Exhibidor de Empanadas/Pan', 'Vitrina térmica con control de temperatura y humidificación'),
(7, 'HOR-PAN-08', 'Horno Convector Panadero 8 Bandejas', 'Horno a gas/eléctrico con sistema de inyección de vapor e intercambio térmico');

-- ------------------------------------------------------------------------------
-- 3. personas (CLIENTES, ASESORES, FABRICANTES, OPERARIOS)
-- ------------------------------------------------------------------------------
INSERT INTO `control_fabricacion`.`personas` (`id_persona`, `documento`, `nombre`, `rol`, `telefono`) VALUES
(1, '1090123456', 'Distribuidora Comercial SAS', 'CLIENTE', '3001234567'),
(2, '1090987654', 'Andrés Pérez', 'ASESOR', '3009876543'),
(3, '900123456', 'FabriMuebles del Norte', 'FABRICANTE', '3101234567'),
(4, '88123456', 'Juan Carlos Operario', 'OPERARIO', '3201234567'),
(5, '901345678', 'Panadería y Pastelería La Espiga SAS', 'CLIENTE', '3112345678'),
(6, '900876543', 'Comidas Rápidas El Vagón Urbano', 'CLIENTE', '3129876543'),
(7, '1090111222', 'Laura Gómez', 'ASESOR', '3154445566'),
(8, '900111222', 'Industrias Metalmecánicas del Norte', 'FABRICANTE', '3201112233'),
(9, '88222333', 'Carlos Mario Restrepo (Ensamblador)', 'OPERARIO', '3007778899');

-- ------------------------------------------------------------------------------
-- 4. MATERIALES E INSUMOS
-- ------------------------------------------------------------------------------
INSERT INTO `control_fabricacion`.`materiales` (`id_material`, `codigo_material`, `descripcion_material`, `unidad_medida`, `precio_unitario_defecto`, `categoria_id`, `stock_minimo`, `stock_actual`, `stock_maximo`) VALUES
(1, 'MAT-CANT-01', 'Canto PVC 2mm', 'MTS', 2500.00, 3, 10.00, 100.00, 500.00),
(2, 'MAT-HERR-05', 'Corredera Pesada 45cm', 'PAR', 18000.00, 2, 5.00, 20.00, 100.00),
(3, 'MAT-MEL-18', 'Lámina Melamina RH 18mm Cemento', 'PLIEGO', 185000.00, 1, 2.00, 15.00, 50.00),
(4, 'MAT-INOX-304', 'Lámina Acero Inoxidable 304 1.2mm Cal. 18', 'PLIEGO', 320000.00, 4, 10.00, 45.00, 100.00),
(5, 'MAT-INOX-430', 'Lámina Acero Inoxidable 430 0.9mm Cal. 20', 'PLIEGO', 195000.00, 4, 15.00, 60.00, 120.00),
(6, 'TUB-INOX-15', 'Tubo Cuadrado Inox 1.5 Pulgadas', 'MTS', 42000.00, 4, 30.00, 120.00, 300.00),
(7, 'GAS-QUEM-01', 'Quemador Hierro Fundido Tipo Estrella 8 pulg', 'UND', 65000.00, 5, 8.00, 24.00, 50.00),
(8, 'GAS-VALV-02', 'Válvula de Seguridad para Gas de Alta Presión', 'UND', 38000.00, 5, 12.00, 40.00, 80.00),
(9, 'MEC-RUED-04', 'Rueda Giratoria 4 pulg con Freno Poliuretano', 'UND', 28000.00, 6, 16.00, 48.00, 100.00),
(10, 'AIS-LANA-01', 'Lana de Vidrio con Aluminio 50mm', 'MTS', 18500.00, 7, 25.00, 80.00, 200.00),
(11, 'VID-TEMP-06', 'Vidrio Templado Curvo 6mm Exhibición', 'UND', 145000.00, 7, 5.00, 14.00, 30.00),
(12, 'ELE-MOTOR-2HP', 'Motor Eléctrico Monofásico 2HP 1750 RPM', 'UND', 480000.00, 6, 3.00, 8.00, 20.00),
(13, 'ELE-TERM-300', 'Termostato Digital con Sonda 0-300°C', 'UND', 115000.00, 5, 6.00, 18.00, 40.00);

-- ------------------------------------------------------------------------------
-- 5. ÓRDENES DE FABRICACIÓN
-- ------------------------------------------------------------------------------
INSERT INTO `control_fabricacion`.`ordenes_fabricacion` 
(`id_orden`, `numero_orden`, `cliente_id`, `asesor_id`, `fabricante_id`, `operario_id`, `producto_id`, `unidades`, `estado`, `fecha_pedido`, `fecha_entrega`, `costo_subtotal`, `costo_mod`, `costo_cif`, `porcentaje_utilidad`, `monto_utilidad`, `monto_total`) VALUES
(21, 'ORD-2026-0001', 1, 2, 3, 4, 1, 1, 'planeacion', '2026-08-15', '2026-08-15', 205500.00, 0.00, 0.00, 25.00, 68500.00, 274000.00),
(20, 'ORD-2026-0002', 5, 7, 8, 9, 2, 1, 'activa', '2026-08-16', '2026-08-22', 1516000.00, 220000.00, 95000.00, 30.00, 784714.29, 2615714.29),
(22, 'ORD-2026-0003', 6, 7, 8, 9, 3, 1, 'en ejecucion', '2026-08-16', '2026-08-25', 1839000.00, 310000.00, 120000.00, 28.00, 882388.89, 3151388.89),
(23, 'ORD-2026-0004', 5, 7, 8, NULL, 4, 2, 'planeacion', '2026-08-16', '2026-08-20', 1784000.00, 180000.00, 85000.00, 25.00, 683000.00, 2732000.00),
(24, 'ORD-2026-0005', 5, 7, 8, 9, 5, 1, 'activa', '2026-08-16', '2026-08-21', 1210000.00, 160000.00, 65000.00, 32.00, 675294.12, 2110294.12),
(25, 'ORD-2026-0006', 5, 7, 8, 9, 6, 2, 'simulacion', '2026-08-16', '2026-08-18', 890000.00, 110000.00, 45000.00, 35.00, 562692.31, 2167692.31),
(26, 'ORD-2026-0007', 5, 7, 8, 9, 7, 1, 'en ejecucion', '2026-08-16', '2026-08-30', 3450000.00, 450000.00, 180000.00, 30.00, 1748571.43, 5828571.43);

-- ------------------------------------------------------------------------------
-- 6. DETALLES DE MATERIALES POR ÓRDEN (DETALLE BOM)
-- ------------------------------------------------------------------------------
INSERT INTO `control_fabricacion`.`orden_fabricacion_detalles` (`orden_id`, `material_id`, `medidas`, `cantidad`, `cantidad_consumida`, `valor_unitario`) VALUES
-- Escritorio Mueble
(21, 1, 'mm', 1.00, 1.00, 2500.00),
(21, 2, 'cm', 1.00, 1.00, 18000.00),
(21, 3, 'mm', 1.00, 1.00, 185000.00),
-- Estufa Industrial
(20, 4, '2440x1220 mm', 3.00, 3.00, 320000.00),
(20, 6, '6.0 mts', 4.00, 4.00, 42000.00),
(20, 7, '8 pulg', 4.00, 4.00, 65000.00),
(20, 8, 'N/A', 4.00, 4.00, 38000.00),
-- Carrito Perros
(22, 4, '2440x1220 mm', 2.00, 2.00, 320000.00),
(22, 5, '2440x1220 mm', 3.00, 3.00, 195000.00),
(22, 6, '6.0 mts', 6.00, 5.50, 42000.00),
(22, 9, '4 pulg', 4.00, 4.00, 28000.00),
(22, 8, 'N/A', 6.00, 6.00, 38000.00),
-- Molino de Granos
(23, 4, '2440x1220 mm', 2.00, 2.00, 320000.00),
(23, 12, 'Monofásico', 2.00, 2.00, 480000.00),
(23, 6, '6.0 mts', 2.00, 2.00, 42000.00),
-- Mostrador Vitrina
(24, 5, '2440x1220 mm', 3.00, 3.00, 195000.00),
(24, 6, '6.0 mts', 4.00, 4.00, 42000.00),
(24, 11, '1500x600 mm', 2.00, 2.00, 145000.00),
(24, 9, '4 pulg', 6.00, 6.00, 28000.00),
-- Calentador Exhibidor
(25, 5, '2440x1220 mm', 2.00, 2.00, 195000.00),
(25, 11, '800x500 mm', 2.00, 2.00, 145000.00),
(25, 13, 'Digital', 1.00, 1.00, 115000.00),
(25, 10, '50mm', 5.00, 5.00, 18500.00),
-- Horno Convector
(26, 4, '2440x1220 mm', 5.00, 5.00, 320000.00),
(26, 10, '50mm', 15.00, 15.00, 18500.00),
(26, 13, 'Digital', 2.00, 2.00, 115000.00),
(26, 7, '8 pulg', 6.00, 6.00, 65000.00),
(26, 8, 'N/A', 6.00, 6.00, 38000.00),
(26, 11, 'Ancho 800mm', 2.00, 2.00, 145000.00);

-- ------------------------------------------------------------------------------
-- 7. MOVIMIENTOS DE INVENTARIO
-- ------------------------------------------------------------------------------
INSERT INTO `control_fabricacion`.`movimientos_inventario` (`tipo_item`, `id_item`, `tipo_movimiento`, `cantidad`, `orden_id`, `observacion`) VALUES
('material', 1, 'salida_orden', 1.00, 21, 'Salida de prueba mueble ORD-2026-0001'),
('material', 2, 'salida_orden', 1.00, 21, 'Salida de prueba mueble ORD-2026-0001'),
('material', 3, 'salida_orden', 1.00, 21, 'Salida de prueba mueble ORD-2026-0001'),
('material', 4, 'salida_orden', 3.00, 20, 'Lámina inox 304 estufa ORD-2026-0002'),
('material', 7, 'salida_orden', 4.00, 20, 'Quemadores tipo estrella ORD-2026-0002'),
('material', 4, 'salida_orden', 2.00, 22, 'Lámina cuerpo carrito ORD-2026-0003'),
('material', 6, 'salida_orden', 5.50, 22, 'Chasis tubo cuadrado ORD-2026-0003'),
('material', 5, 'salida_orden', 3.00, 24, 'Lámina 430 vitrina ORD-2026-0005'),
('material', 4, 'salida_orden', 5.00, 26, 'Revestimiento horno ORD-2026-0007'),
('producto_terminado', 1, 'entrada_produccion', 1.00, 21, 'Ingreso a bodega desde orden ORD-2026-0001'),
('producto_terminado', 2, 'entrada_produccion', 1.00, 20, 'Ingreso de estufa terminada ORD-2026-0002');

-- ------------------------------------------------------------------------------
-- 8. INVENTARIO DE PRODUCTO TERMINADO (BODEGA / DESPACHO)
-- ------------------------------------------------------------------------------
INSERT INTO `control_fabricacion`.`inventario_producto_terminado` 
(`id_inventario_pt`, `producto_id`, `orden_id`, `cantidad`, `ubicacion_bodega`) VALUES
(1, 1, 21, 1.00, 'Bodega A - Muebles Terminados'),
(2, 2, 20, 1.00, 'Bodega B - Equipos Gastronómicos'),
(3, 4, NULL, 1.00, 'Showroom Principal'),
(4, 6, NULL, 2.00, 'Bodega B - Equipos Gastronómicos');
