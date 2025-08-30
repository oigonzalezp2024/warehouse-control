CREATE DATABASE `empresa`;

CREATE TABLE `empresa`.`usuarios` (
  `id_usuario` int(11) AUTO_INCREMENT PRIMARY KEY,
  `nombre` varchar(50) DEFAULT NULL,
  `apellidos` varchar(50) DEFAULT NULL,
  `celular` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `empresa`.`clientes` (
  `id_cliente` int(11) AUTO_INCREMENT PRIMARY KEY,
  `nombre` varchar(100) NOT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `empresa`.`bodega` (
  `id_item` int(11) AUTO_INCREMENT PRIMARY KEY,
  `proveedor_id` int(11) DEFAULT NULL,
  `accion` varchar(25) NOT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` varchar(55) DEFAULT NULL,
  `fecha_creacion` varchar(35) DEFAULT NULL,
  `fecha_modificacion` varchar(35) DEFAULT NULL,
  `servicio` int(11) NOT NULL DEFAULT 0,
  `usuario_id` int(11) DEFAULT NULL,
  `ruta_foto` text NOT NULL,
  `precio` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `empresa`.`super_admin` (
  `id_super_admin` int(11) AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `empresa`.`tablas` (
  `id_tabla` int(11) AUTO_INCREMENT PRIMARY KEY,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` varchar(190) DEFAULT NULL,
  `super_admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `empresa`.`campos` (
  `id_campo` int(11) AUTO_INCREMENT PRIMARY KEY,
  `nombre_campo` varchar(50) DEFAULT NULL,
  `descripcion` varchar(190) DEFAULT NULL,
  `tabla_id` int(11) DEFAULT NULL,
  `super_admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `empresa`.`servicios` (
  `id_servicio` int(11) AUTO_INCREMENT PRIMARY KEY,
  `servicio_nombre` varchar(55) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `empresa`.`productos` (
  `id_producto` int(11) AUTO_INCREMENT PRIMARY KEY,
  `producto_nombre` varchar(255) DEFAULT NULL,
  `bodegas` varchar(100) NOT NULL,
  `fecha_creacion` varchar(55) DEFAULT NULL,
  `fecha_modificacion` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `empresa`.`proveedores` (
  `id_proveedor` int(11) AUTO_INCREMENT PRIMARY KEY,
  `nombre` varchar(100) NOT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `empresa`.`bodegas` (
  `id_item` int(11) AUTO_INCREMENT PRIMARY KEY,
  `nombre_bodega` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `empresa`.`bodega_h` (
  `id_item` int(11) AUTO_INCREMENT PRIMARY KEY,
  `proveedor_id` int(11) DEFAULT NULL,
  `accion` varchar(25) NOT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` varchar(55) DEFAULT NULL,
  `fecha_creacion` varchar(35) DEFAULT NULL,
  `fecha_modificacion` varchar(35) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `empresa`.`bodega`
  ADD KEY `cliente_id` (`proveedor_id`),
  ADD KEY `servicio_id` (`servicio_id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `usuario_id` (`usuario_id`);

ALTER TABLE `empresa`.`bodegas`
  ADD UNIQUE KEY `nombre_bodega` (`nombre_bodega`),
  ADD KEY `idx_nombre_bodega` (`nombre_bodega`);

ALTER TABLE `empresa`.`bodega_h`
  ADD KEY `cliente_id` (`proveedor_id`),
  ADD KEY `servicio_id` (`servicio_id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `usuario_id` (`usuario_id`);

ALTER TABLE `empresa`.`campos`
  ADD KEY `tabla_id` (`tabla_id`),
  ADD KEY `super_admin_id` (`super_admin_id`);

ALTER TABLE `empresa`.`clientes`
  ADD KEY `usuario_id` (`usuario_id`);

ALTER TABLE `empresa`.`productos`
  ADD KEY `usuario_id` (`usuario_id`);

ALTER TABLE `empresa`.`proveedores`
  ADD KEY `usuario_id` (`usuario_id`);

ALTER TABLE `empresa`.`servicios`
  ADD KEY `usuario_id` (`usuario_id`);

ALTER TABLE `empresa`.`tablas`
  ADD KEY `super_admin_id` (`super_admin_id`);

ALTER TABLE `empresa`.`usuarios`
  ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `empresa`.`bodega`
  ADD CONSTRAINT `bodega_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id_proveedor`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bodega_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id_servicio`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bodega_ibfk_3` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id_producto`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bodega_ibfk_4` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;

ALTER TABLE `empresa`.`campos`
  ADD CONSTRAINT `campos_ibfk_1` FOREIGN KEY (`tabla_id`) REFERENCES `tablas` (`id_tabla`) ON UPDATE CASCADE,
  ADD CONSTRAINT `campos_ibfk_2` FOREIGN KEY (`super_admin_id`) REFERENCES `super_admin` (`id_super_admin`) ON UPDATE CASCADE;

ALTER TABLE `empresa`.`productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;

ALTER TABLE `empresa`.`proveedores`
  ADD CONSTRAINT `proveedores_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;

ALTER TABLE `empresa`.`servicios`
  ADD CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;

ALTER TABLE `empresa`.`tablas`
  ADD CONSTRAINT `tablas_ibfk_1` FOREIGN KEY (`super_admin_id`) REFERENCES `super_admin` (`id_super_admin`) ON UPDATE CASCADE;

INSERT INTO `empresa`.`usuarios` (`id_usuario`, `nombre`, `apellidos`, `celular`, `username`, `pass`) VALUES
(39, 'Oscar Ivan', 'Gonzalez Peña', '3212962876', 'oigonzalezp2024@gmail.com', '');

INSERT INTO `empresa`.`clientes` (`id_cliente`, `nombre`, `contacto`, `telefono`, `direccion`, `usuario_id`, `fecha_creacion`, `fecha_modificacion`) VALUES
(2, 'Oscar', 'Oscar Gutierez', '3134563202', 'ygdtb', 43, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

INSERT INTO `empresa`.`bodegas` (`id_item`, `nombre_bodega`, `fecha_creacion`, `fecha_modificacion`) VALUES
(1, 'bodega Almacen', '2025-04-23 22:33:33', '2025-04-23 22:33:33');

INSERT INTO `empresa`.`super_admin` (`id_super_admin`, `usuario_id`) VALUES
(1, 1);

INSERT INTO `empresa`.`tablas` (`id_tabla`, `nombre`, `descripcion`, `super_admin_id`) VALUES
(1, 'usuarios', 'lleva un control de los usuarios en el sistema', 1);

INSERT INTO `empresa`.`campos` (`id_campo`, `nombre_campo`, `descripcion`, `tabla_id`, `super_admin_id`) VALUES
(1, 'id_usuario', 'llave primaria de la tabla usuarios', 1, 1),
(2, 'nombre', 'nombre de usuario', 1, 1),
(3, 'apellidos', 'nombre del usuario', 1, 1),
(4, 'celular', 'nombre del usuario', 1, 1),
(5, 'username', 'nombre del usuario', 1, 1),
(6, 'pass', 'nombre del usuario', 1, 1),
(7, 'id_cliente', 'llave primaria', 1, 1);

INSERT INTO `empresa`.`servicios` (`id_servicio`, `servicio_nombre`, `usuario_id`) VALUES
(2, 'produccion', 39);

INSERT INTO `empresa`.`productos` (`id_producto`, `producto_nombre`, `bodegas`, `fecha_creacion`, `fecha_modificacion`, `usuario_id`) VALUES
(17, 'Pantalon', 'limpio, aglutinado, peletizado', '', 0, 39);

INSERT INTO `empresa`.`proveedores` (`id_proveedor`, `nombre`, `contacto`, `telefono`, `direccion`, `usuario_id`, `fecha_creacion`, `fecha_modificacion`) VALUES
(1, 'Mundo 30', 'Mario Castañeda', '5555', '5', 39, '2025-03-27 17:58:09', '2025-05-09 19:45:14');
