/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

CREATE TABLE `linea_venta` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id única de cada línea de venta',
  `id_venta` INT(11) NOT NULL COMMENT 'Id de la venta a la que pertenece la línea',
  `id_articulo` INT(11) NOT NULL COMMENT 'Id del artículo que está siendo vendido',
  `puc` FLOAT NOT NULL DEFAULT '0' COMMENT 'Precio Unitario de Compra del artículo en el momento de su venta',
  `pvp` FLOAT NOT NULL DEFAULT '0' COMMENT 'Precio de Venta al Público del artículo en el momento de su venta',
  `iva` INT(11) NOT NULL DEFAULT '0' COMMENT 'IVA del artículo en el momento de su venta',
  `re` INT(11) NOT NULL DEFAULT '0' COMMENT 'Recargo de equivalencia del artículo en el momento de su venta',
  `descuento` INT(11) NOT NULL DEFAULT '0' COMMENT 'Porcentaje de descuento aplicado',
  `devuelto` INT(11) NOT NULL DEFAULT '0' COMMENT 'Cantidad de artículos devueltos',
  `unidades` INT(11) NOT NULL DEFAULT '0' COMMENT 'Cantidad de artículos vendidos',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `foto` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada foto',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `marca` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada marca',
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la marca',
  `id_foto` INT(11) NOT NULL COMMENT 'Foto/logo de la marca',
  `direccion` VARCHAR(200) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Dirección física de la marca',
  `telefono` VARCHAR(15) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Teléfono de la marca',
  `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Dirección de email de la marca',
  `web` VARCHAR(100) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Dirección de la página web de la marca',
  `observaciones` TEXT COLLATE utf8mb4_unicode_ci NULL COMMENT 'Observaciones o notas personales de la marca',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `tarjeta` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada tarjeta',
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la tarjeta',
  `slug` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug del nombre de la tarjeta',
  `comision` FLOAT NOT NULL DEFAULT '0' COMMENT 'Porcentaje de comision que se cobra en cada venta',
  `por_defecto` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Indica si es el tipo de tarjeta por defecto 1 o no 0',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `categoria` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada categoría',
  `id_padre` INT(11) NULL COMMENT 'Id de la categoría padre en caso de ser una subcategoría',
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la categoría',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `proveedor_marca` (
  `id_proveedor` INT(11) NOT NULL COMMENT 'Id del proveedor',
  `id_marca` INT(11) NOT NULL COMMENT 'Id de la marca',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_proveedor`,`id_marca`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `pago_caja` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada pago de caja',
  `concepto` VARCHAR(250) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Concepto del pago',
  `importe` FLOAT NOT NULL DEFAULT '0' COMMENT 'Importe de dinero sacado de la caja para realizar el pago',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `articulo_foto` (
  `id_foto` INT(11) NOT NULL COMMENT 'Id único para cada foto',
  `id_articulo` INT(11) NOT NULL COMMENT 'Id del artículo al que pertenece la foto',
  `orden` INT(11) NOT NULL DEFAULT '0' COMMENT 'Orden de la foto entre todas las fotos de un artículo',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_foto`,`id_articulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `venta` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada venta',
  `id_cliente` INT(11) NOT NULL COMMENT 'Id del cliente',
  `total` FLOAT NOT NULL DEFAULT '0' COMMENT 'Importe total de la venta',
  `entregado` FLOAT NOT NULL DEFAULT '0' COMMENT 'Importe entregado por el cliente',
  `tipo_pago` INT(11) NOT NULL DEFAULT '0' COMMENT 'Tipo de pago 0 metálico 1 tarjeta 2 web',
  `id_tarjeta` INT(11) NOT NULL COMMENT 'Id del tipo de tarjeta usada en el pago',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `caja` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada cierre de caja',
  `apertura` DATETIME NOT NULL COMMENT 'Fecha de apertura de la caja',
  `cierre` DATETIME NULL COMMENT 'Fecha de cierre de la caja',
  `diferencia` FLOAT NULL COMMENT 'Diferencia entre el importe que debería haber y el que realmente hay',
  `ventas` FLOAT NULL COMMENT 'Importe total de ventas para el período de la caja',
  `beneficios` FLOAT NULL COMMENT 'Importe total de beneficios para el período de la caja',
  `venta_efectivo` FLOAT NULL COMMENT 'Importe total vendido en efectivo',
  `venta_tarjetas` FLOAT NULL COMMENT 'Importe total vendido mediante tarjetas',
  `efectivo_apertura` FLOAT NULL COMMENT 'Importe total en efectivo en la caja al momento de la apertura',
  `efectivo_cierre` FLOAT NULL COMMENT 'Importe total en efectivo en la caja al momento del cierre',
  `1c` INT(11) NULL COMMENT 'Cantidad de monedas de un centimo',
  `2c` INT(11) NULL COMMENT 'Cantidad de monedas de dos centimos',
  `5c` INT(11) NULL COMMENT 'Cantidad de monedas de cinco centimos',
  `10c` INT(11) NULL COMMENT 'Cantidad de monedas de diez centimos',
  `20c` INT(11) NULL COMMENT 'Cantidad de monedas de veinte centimos',
  `50c` INT(11) NULL COMMENT 'Cantidad de monedas de cincuenta centimos',
  `1e` INT(11) NULL COMMENT 'Cantidad de monedas de un euro',
  `2e` INT(11) NULL COMMENT 'Cantidad de monedas de dos euros',
  `5e` INT(11) NULL COMMENT 'Cantidad de billetes de cinco euros',
  `10e` INT(11) NULL COMMENT 'Cantidad de billetes de diez euros',
  `20e` INT(11) NULL COMMENT 'Cantidad de billetes de veinte euros',
  `50e` INT(11) NULL COMMENT 'Cantidad de billetes de cincuenta euros',
  `100e` INT(11) NULL COMMENT 'Cantidad de billetes de cien euros',
  `200e` INT(11) NULL COMMENT 'Cantidad de billetes de doscientos euros',
  `500e` INT(11) NULL COMMENT 'Cantidad de billetes de quinientos euros',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `codigo_barras` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada código de barras',
  `id_articulo` INT(11) NOT NULL COMMENT 'Id del artículo al que pertenece el código de barras',
  `codigo_barras` INT(11) NOT NULL COMMENT 'Código de barras del artículo',
  `por_defecto` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Indica si es el código de barras asignado por defecto por el TPV 1 o añadido a mano 1',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `comercial` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada comercial',
  `id_proveedor` INT(11) NOT NULL COMMENT 'Id del proveedor para el que trabaja el comercial',
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del comercial',
  `telefono` VARCHAR(15) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Teléfono del comercial',
  `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Dirección de email del comercial',
  `observaciones` TEXT COLLATE utf8mb4_unicode_ci NULL COMMENT 'Observaciones o notas personales del comercial',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `proveedor` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada proveedor',
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del proveedor',
  `id_foto` INT(11) NOT NULL COMMENT 'Foto del proveedor',
  `direccion` VARCHAR(200) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Dirección física del proveedor',
  `telefono` VARCHAR(15) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Teléfono del proveedor',
  `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Dirección de email del proveedor',
  `web` VARCHAR(100) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Dirección de la página web del proveedor',
  `observaciones` TEXT COLLATE utf8mb4_unicode_ci NULL COMMENT 'Observaciones o notas personales del proveedor',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `cliente` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada cliente',
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del cliente',
  `apellidos` VARCHAR(100) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Apellidos del cliente',
  `dni_cif` VARCHAR(10) COLLATE utf8mb4_unicode_ci NULL COMMENT 'DNI/CIF del cliente',
  `telefono` VARCHAR(15) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Teléfono del cliente',
  `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Email del cliente',
  `direccion` VARCHAR(100) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Dirección del cliente',
  `codigo_postal` VARCHAR(10) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Código postal del cliente',
  `poblacion` VARCHAR(50) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Población del cliente',
  `provincia` INT(11) NULL COMMENT 'Id de la provincia del cliente',
  `observaciones` TEXT COLLATE utf8mb4_unicode_ci NULL COMMENT 'Campo libre para observaciones personales del cliente',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `articulo` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada artículo',
  `localizador` INT(11) NOT NULL COMMENT 'Localizador único de cada artículo',
  `nombre` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del artículo',
  `slug` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug del nombre del artículo',
  `id_categoria` INT(11) NOT NULL COMMENT 'Id de la categoría en la que se engloba el artículo',
  `id_marca` INT(11) NOT NULL COMMENT 'Id de la marca del artículo',
  `id_proveedor` INT(11) NOT NULL COMMENT 'Id del proveedor del artículo',
  `referencia` VARCHAR(50) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Referencia original del proveedor',
  `puc` FLOAT NOT NULL DEFAULT '0' COMMENT 'Precio Unitario de Compra del artículo',
  `pvp` FLOAT NOT NULL DEFAULT '0' COMMENT 'Precio de Venta al Público del artículo',
  `palb` FLOAT NOT NULL DEFAULT '0' COMMENT 'Precio del artículo en el albarán',
  `iva` INT(11) NOT NULL COMMENT 'IVA del artículo',
  `re` INT(11) NOT NULL COMMENT 'Recargo de equivalencia',
  `margen` FLOAT NOT NULL DEFAULT '0' COMMENT 'Margen de beneficio del artículo',
  `stock` INT(11) NOT NULL DEFAULT '0' COMMENT 'Stock actual del artículo',
  `stock_min` INT(11) NOT NULL DEFAULT '0' COMMENT 'Stock mínimo del artículo',
  `stock_max` INT(11) NOT NULL DEFAULT '0' COMMENT 'Stock máximo del artículo',
  `lote_optimo` INT(11) NOT NULL DEFAULT '0' COMMENT 'Lote óptimo para realizar pedidos del artículo',
  `venta_online` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Indica si el producto está disponible desde la web 1 o no 0',
  `mostrar_feccad` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Mostrar fecha de caducidad 0 no 1 si',
  `fecha_caducidad` DATETIME NULL COMMENT 'Fecha de caducidad del artículo',
  `observaciones` TEXT COLLATE utf8mb4_unicode_ci NULL COMMENT 'Observaciones o notas sobre el artículo',
  `mostrar_obs_pedidos` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Mostrar observaciones en pedidos 0 no 1 si',
  `mostrar_obs_ventas` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Mostrar observaciones en ventas 0 no 1 si',
  `mostrar_en_web` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Indica si debe ser mostrado en la web 1 o no 0',
  `desc_corta` VARCHAR(250) COLLATE utf8mb4_unicode_ci NULL COMMENT 'Descripción corta para la web',
  `descripcion` TEXT COLLATE utf8mb4_unicode_ci NULL COMMENT 'Descripción larga para la web',
  `activo` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Indica si el artículo está en alta 1 o dado de baja 0',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `linea_venta`
  ADD KEY `fk_linea_venta_venta_idx` (`id_venta`),
  ADD KEY `fk_linea_venta_articulo_idx` (`id_articulo`),
  ADD CONSTRAINT `fk_linea_venta_venta` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_linea_venta_articulo` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `marca`
  ADD KEY `fk_marca_foto_idx` (`id_foto`),
  ADD CONSTRAINT `fk_marca_foto` FOREIGN KEY (`id_foto`) REFERENCES `foto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `proveedor_marca`
  ADD KEY `fk_proveedor_marca_proveedor_idx` (`id_proveedor`),
  ADD KEY `fk_proveedor_marca_marca_idx` (`id_marca`),
  ADD CONSTRAINT `fk_proveedor_marca_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_proveedor_marca_marca` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `articulo_foto`
  ADD KEY `fk_articulo_foto_foto_idx` (`id_foto`),
  ADD KEY `fk_articulo_foto_articulo_idx` (`id_articulo`),
  ADD CONSTRAINT `fk_articulo_foto_foto` FOREIGN KEY (`id_foto`) REFERENCES `foto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_articulo_foto_articulo` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `venta`
  ADD KEY `fk_venta_cliente_idx` (`id_cliente`),
  ADD KEY `fk_venta_tarjeta_idx` (`id_tarjeta`),
  ADD CONSTRAINT `fk_venta_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_venta_tarjeta` FOREIGN KEY (`id_tarjeta`) REFERENCES `tarjeta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `codigo_barras`
  ADD KEY `fk_codigo_barras_articulo_idx` (`id_articulo`),
  ADD CONSTRAINT `fk_codigo_barras_articulo` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `comercial`
  ADD KEY `fk_comercial_proveedor_idx` (`id_proveedor`),
  ADD CONSTRAINT `fk_comercial_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `proveedor`
  ADD KEY `fk_proveedor_foto_idx` (`id_foto`),
  ADD CONSTRAINT `fk_proveedor_foto` FOREIGN KEY (`id_foto`) REFERENCES `foto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `articulo`
  ADD KEY `fk_articulo_marca_idx` (`id_marca`),
  ADD KEY `fk_articulo_proveedor_idx` (`id_proveedor`),
  ADD CONSTRAINT `fk_articulo_marca` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_articulo_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
