/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

CREATE TABLE `codigo_barras` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada código de barras',
  `id_articulo` INT(11) NOT NULL COMMENT 'Id del artículo al que pertenece el código de barras',
  `codigo_barras` VARCHAR(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Código de barras del artículo',
  `por_defecto` TINYINT(1) COMMENT 'Indica si es el código de barras asignado por defecto por el TPV 1 o añadido a mano 1',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `articulo_etiqueta_web` (
  `id_articulo` INT(11) NOT NULL COMMENT 'Id del artículo',
  `id_etiqueta_web` INT(11) NOT NULL COMMENT 'Id de la etiqueta web',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_articulo`,`id_etiqueta_web`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `empleado` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada empleado',
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Nombre del empleado',
  `pass` VARCHAR(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contraseña cifrada del empleado',
  `color` VARCHAR(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Código de color hexadecimal para distinguir a cada empleado',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Fecha de borrado del cliente',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `vista_pedido` (
  `id_pedido` INT(11) NOT NULL COMMENT 'Id del pedido',
  `id_column` INT(11) NOT NULL COMMENT 'Id de la columna a mostrar/ocultar',
  `status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Indica si la columna se tiene que mostrar 1 o no 0',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_pedido`,`id_column`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `linea_pedido` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada línea de un pedido',
  `id_pedido` INT(11) NOT NULL COMMENT 'Id del pedido al que pertenece la línea',
  `id_articulo` INT(11) NOT NULL COMMENT 'Id del artículo recibido',
  `nombre_articulo` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre del artículo',
  `codigo_barras` INT(11) DEFAULT NULL COMMENT 'Nuevo código de barras para el artículo',
  `unidades` INT(11) DEFAULT NULL COMMENT 'Número de unidades recibidas',
  `palb` FLOAT DEFAULT NULL COMMENT 'Precio de albarán del artículo',
  `pvp` FLOAT DEFAULT NULL COMMENT 'PVP del artículo',
  `margen` FLOAT DEFAULT NULL COMMENT 'Porcentaje de margen del artículo',
  `iva` FLOAT DEFAULT NULL COMMENT 'IVA del artículo',
  `re` FLOAT DEFAULT NULL COMMENT 'RE del artículo',
  `descuento` FLOAT DEFAULT NULL COMMENT 'Porcentaje de descuento del artículo',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `reserva` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada reserva',
  `id_cliente` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id del cliente',
  `total` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total de la reserva',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `linea_venta` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id única de cada línea de venta',
  `id_venta` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id de la venta a la que pertenece la línea',
  `id_articulo` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id del artículo que está siendo vendido',
  `nombre_articulo` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre del artículo',
  `puc` FLOAT NOT NULL DEFAULT 0 COMMENT 'Precio Unitario de Compra del artículo en el momento de su venta',
  `pvp` FLOAT NOT NULL DEFAULT 0 COMMENT 'Precio de Venta al Público del artículo en el momento de su venta',
  `iva` INT(11) NOT NULL DEFAULT 0 COMMENT 'IVA del artículo en el momento de su venta',
  `importe` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total de la línea',
  `descuento` INT(11) DEFAULT NULL COMMENT 'Porcentaje de descuento aplicado',
  `importe_descuento` FLOAT DEFAULT NULL COMMENT 'Importe directo en descuento',
  `devuelto` INT(11) NOT NULL DEFAULT 0 COMMENT 'Cantidad de artículos devueltos',
  `unidades` INT(11) NOT NULL DEFAULT 0 COMMENT 'Cantidad de artículos vendidos',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `proveedor` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada proveedor',
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Nombre del proveedor',
  `id_foto` INT(11) NOT NULL DEFAULT NULL COMMENT 'Foto del proveedor',
  `direccion` VARCHAR(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dirección física del proveedor',
  `telefono` VARCHAR(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono del proveedor',
  `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dirección de email del proveedor',
  `web` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dirección de la página web del proveedor',
  `observaciones` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observaciones o notas personales del proveedor',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Fecha de borrado del proveedor',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `venta` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada venta',
  `num_venta` INT(11) NOT NULL COMMENT 'Número de venta',
  `id_empleado` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id del empleado que realiza la venta',
  `id_cliente` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id del cliente',
  `total` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total de la venta',
  `entregado` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe entregado por el cliente',
  `pago_mixto` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Indica si se ha hecho un pago mixto',
  `id_tipo_pago` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id del tipo de pago',
  `entregado_otro` FLOAT DEFAULT NULL COMMENT 'Cantidad pagada mediante tipo de pago alternativo',
  `saldo` FLOAT DEFAULT NULL COMMENT 'Saldo en caso de que el ticket sea un vale',
  `facturada` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Indica si la venta ha sido facturada',
  `tbai_huella` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Huella de TicketBai',
  `tbai_qr` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Código QR de TicketBai',
  `tbai_url` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL del ticket en Batuz',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Fecha de borrado de la venta',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `pdf_pedido` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada PDF',
  `id_pedido` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id del pedido al que pertenece el PDF',
  `nombre` VARCHAR(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre del archivo PDF',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `pedido` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada pedido',
  `id_proveedor` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id del proveedor del pedido',
  `metodo_pago` INT(11) DEFAULT NULL COMMENT 'Método de pago del pedido',
  `tipo` VARCHAR(10) COLLATE utf8mb4_unicode_ci COMMENT 'Indica si se trata de un albarán, una factura o un abono',
  `num` VARCHAR(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Albarán / factura / abono del pedido',
  `importe` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total del pedido',
  `portes` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe de los portes del pedido',
  `descuento` INT(11) NOT NULL DEFAULT 0 COMMENT 'Porcentaje de descuento en el pedido',
  `fecha_pago` DATETIME DEFAULT NULL COMMENT 'Fecha de pago del pedido',
  `fecha_pedido` DATETIME DEFAULT NULL COMMENT 'Fecha del pedido',
  `fecha_recepcionado` DATETIME DEFAULT NULL COMMENT 'Fecha del momento de la recepcion del pedido',
  `re` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Indica si el pedido tiene RE 1 o no 0',
  `europeo` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Indica si se rata de un pedido europeo 1 o no 0',
  `faltas` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Indica si hay faltas en el pedido 1 o no 0',
  `recepcionado` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Indica si se ha recepcionado el pedido 1 o si está pendiente 0',
  `observaciones` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observaciones o notas sobre el pedido',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `categoria` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada categoría',
  `id_padre` INT(11) DEFAULT NULL COMMENT 'Id de la categoría padre en caso de ser una subcategoría',
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Nombre de la categoría',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `caja` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada cierre de caja',
  `apertura` DATETIME NOT NULL DEFAULT NULL COMMENT 'Fecha de apertura de la caja',
  `cierre` DATETIME DEFAULT NULL COMMENT 'Fecha de cierre de la caja',
  `ventas` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total de ventas para el período de la caja',
  `beneficios` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total de beneficios para el período de la caja',
  `venta_efectivo` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total vendido en efectivo',
  `operaciones_efectivo` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de operaciones hechas en efectivo',
  `descuento_efectivo` FLOAT NOT NULL DEFAULT 0 COMMENT 'Descuento total de las ventas en efectivo',
  `venta_otros` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total vendido mediante tipos de pago alternativos',
  `operaciones_otros` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de operaciones hechas mediante tipos de pago alternativos',
  `descuento_otros` FLOAT NOT NULL DEFAULT 0 COMMENT 'Descuento total de las ventas hechas mediante tipos de pago alternativos',
  `importe_pagos_caja` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total en pagos de caja',
  `num_pagos_caja` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de pagos de caja',
  `importe_apertura` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total en efectivo en la caja al momento de la apertura',
  `importe_cierre` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total en efectivo en la caja al momento del cierre',
  `importe_cierre_real` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe real en efectivo en la caja al momento del cierre',
  `importe1c` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de monedas de 1 centimo',
  `importe2c` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de monedas de 2 centimos',
  `importe5c` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de monedas de 5 centimos',
  `importe10c` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de monedas de 10 centimos',
  `importe20c` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de monedas de 20 centimos',
  `importe50c` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de monedas de 50 centimos',
  `importe1` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de monedas de 1 euro',
  `importe2` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de monedas de 2 euros',
  `importe5` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de billetes de 5 euros',
  `importe10` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de billetes de 10 euros',
  `importe20` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de billetes de 20 euros',
  `importe50` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de billetes de 50 euros',
  `importe100` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de billetes de 100 euros',
  `importe200` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de billetes de 200 euros',
  `importe500` INT(11) NOT NULL DEFAULT 0 COMMENT 'Número de billetes de 500 euros',
  `importe_retirado` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe retirado de la caja al momento del cierre',
  `importe_entrada` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe añadido a la caja al momento del cierre',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `foto` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada foto',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `empleado_rol` (
  `id_empleado` INT(11) NOT NULL COMMENT 'Id del empleado',
  `id_rol` INT(11) NOT NULL COMMENT 'Id del permiso que se le otorga al empleado',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_empleado`,`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `marca` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada marca',
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Nombre de la marca',
  `direccion` VARCHAR(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dirección física de la marca',
  `telefono` VARCHAR(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono de la marca',
  `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dirección de email de la marca',
  `web` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dirección de la página web de la marca',
  `observaciones` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observaciones o notas personales de la marca',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Fecha de borrado de la marca',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `articulo` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada artículo',
  `localizador` INT(11) NOT NULL DEFAULT NULL COMMENT 'Localizador único de cada artículo',
  `nombre` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Nombre del artículo',
  `slug` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Slug del nombre del artículo',
  `id_categoria` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id de la categoría en la que se engloba el artículo',
  `id_marca` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id de la marca del artículo',
  `id_proveedor` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id del proveedor del artículo',
  `referencia` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Referencia original del proveedor',
  `palb` FLOAT NOT NULL DEFAULT 0 COMMENT 'Precio del artículo en el albarán',
  `puc` FLOAT NOT NULL DEFAULT 0 COMMENT 'Precio Unitario de Compra del artículo',
  `pvp` FLOAT NOT NULL DEFAULT 0 COMMENT 'Precio de Venta al Público del artículo',
  `iva` INT(11) NOT NULL DEFAULT NULL COMMENT 'IVA del artículo',
  `re` FLOAT NOT NULL DEFAULT NULL COMMENT 'Recargo de equivalencia',
  `margen` FLOAT NOT NULL DEFAULT 0 COMMENT 'Margen de beneficio del artículo',
  `stock` INT(11) NOT NULL DEFAULT 0 COMMENT 'Stock actual del artículo',
  `stock_min` INT(11) NOT NULL DEFAULT 0 COMMENT 'Stock mínimo del artículo',
  `stock_max` INT(11) NOT NULL DEFAULT 0 COMMENT 'Stock máximo del artículo',
  `lote_optimo` INT(11) NOT NULL DEFAULT 0 COMMENT 'Lote óptimo para realizar pedidos del artículo',
  `venta_online` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Indica si el producto está disponible desde la web 1 o no 0',
  `fecha_caducidad` DATETIME DEFAULT NULL COMMENT 'Fecha de caducidad del artículo',
  `mostrar_en_web` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Indica si debe ser mostrado en la web 1 o no 0',
  `desc_corta` VARCHAR(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Descripción corta para la web',
  `descripcion` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Descripción larga para la web',
  `observaciones` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observaciones o notas sobre el artículo',
  `mostrar_obs_pedidos` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Mostrar observaciones en pedidos 0 no 1 si',
  `mostrar_obs_ventas` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Mostrar observaciones en ventas 0 no 1 si',
  `acceso_directo` INT(11) DEFAULT NULL COMMENT 'Acceso directo al artículo',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Fecha de borrado del artículo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `etiqueta` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada etiqueta',
  `texto` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Texto de la etiqueta',
  `slug` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug del texto de la etiqueta',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `tipo_pago` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada tipo de pago',
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Nombre del tipo de pago',
  `slug` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Slug del nombre del tipo de pago',
  `afecta_caja` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Indica si el tipo de pago afecta a la caja',
  `orden` INT(11) NOT NULL DEFAULT NULL COMMENT 'Orden del tipo de pago en la lista completa',
  `fisico` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Indica si el tipo de pago es para tienda física',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Fecha de borrado del proveedor',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `event_log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada empleado',
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Nombre del empleado',
  `pass` VARCHAR(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contraseña cifrada del empleado',
  `color` VARCHAR(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Código de color hexadecimal para distinguir a cada empleado',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Fecha de borrado del cliente',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `factura_venta` (
  `id_factura` INT(11) NOT NULL COMMENT 'Id de la factura',
  `id_venta` INT(11) NOT NULL COMMENT 'Id de la venta',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_factura`,`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `etiqueta_web` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada etiqueta web',
  `texto` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Texto de la etiqueta web',
  `slug` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug del texto de la etiqueta web',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `articulo_etiqueta` (
  `id_articulo` INT(11) NOT NULL COMMENT 'Id del artículo',
  `id_etiqueta` INT(11) NOT NULL COMMENT 'Id de la etiqueta',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_articulo`,`id_etiqueta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `comercial` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada comercial',
  `id_proveedor` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id del proveedor para el que trabaja el comercial',
  `nombre` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Nombre del comercial',
  `telefono` VARCHAR(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono del comercial',
  `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dirección de email del comercial',
  `observaciones` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observaciones o notas personales del comercial',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Fecha de borrado del cliente',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `linea_reserva` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id única de cada línea de reserva',
  `id_reserva` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id de la reserva a la que pertenece la línea',
  `id_articulo` INT(11) NOT NULL DEFAULT NULL COMMENT 'Id del artículo que está siendo reservado',
  `nombre_articulo` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre del artículo',
  `puc` FLOAT NOT NULL DEFAULT 0 COMMENT 'Precio Unitario de Compra del artículo en el momento de su venta',
  `pvp` FLOAT NOT NULL DEFAULT 0 COMMENT 'Precio de Venta al Público del artículo en el momento de su venta',
  `iva` INT(11) NOT NULL DEFAULT 0 COMMENT 'IVA del artículo en el momento de su venta',
  `importe` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total de la línea',
  `descuento` INT(11) DEFAULT NULL COMMENT 'Porcentaje de descuento aplicado',
  `importe_descuento` FLOAT DEFAULT NULL COMMENT 'Importe directo en descuento',
  `unidades` INT(11) NOT NULL DEFAULT 0 COMMENT 'Cantidad de artículos vendidos',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `proveedor_marca` (
  `id_proveedor` INT(11) NOT NULL COMMENT 'Id del proveedor',
  `id_marca` INT(11) NOT NULL COMMENT 'Id de la marca',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_proveedor`,`id_marca`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `cliente` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada cliente',
  `nombre_apellidos` VARCHAR(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Nombre y apellidos del cliente',
  `dni_cif` VARCHAR(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'DNI/CIF del cliente',
  `telefono` VARCHAR(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono del cliente',
  `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email del cliente',
  `direccion` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dirección del cliente',
  `codigo_postal` VARCHAR(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Código postal del cliente',
  `poblacion` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Población del cliente',
  `provincia` INT(11) DEFAULT NULL COMMENT 'Id de la provincia del cliente',
  `fact_igual` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Indica si los datos de facturación son iguales a los del cliente',
  `fact_nombre_apellidos` VARCHAR(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Nombre y apellidos del cliente para la facturación',
  `fact_dni_cif` VARCHAR(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'DNI/CIF del cliente para la facturación',
  `fact_telefono` VARCHAR(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono del cliente para la facturación',
  `fact_email` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email del cliente para la facturación',
  `fact_direccion` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dirección del cliente para la facturación',
  `fact_codigo_postal` VARCHAR(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Código postal del cliente para la facturación',
  `fact_poblacion` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Población del cliente para la facturación',
  `fact_provincia` INT(11) DEFAULT NULL COMMENT 'Id de la provincia del cliente para la facturación',
  `observaciones` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Campo libre para observaciones personales del cliente',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Fecha de borrado del cliente',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `factura` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada factura',
  `id_cliente` INT(11) NOT NULL COMMENT 'Id del cliente al que se le emite la factura',
  `num_factura` INT(11) DEFAULT NULL COMMENT 'Número de factura',
  `nombre_apellidos` VARCHAR(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre y apellidos del cliente',
  `dni_cif` VARCHAR(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'DNI/CIF del cliente',
  `telefono` VARCHAR(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono del cliente',
  `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email del cliente',
  `direccion` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dirección del cliente',
  `codigo_postal` VARCHAR(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Código postal del cliente',
  `poblacion` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Población del cliente',
  `provincia` INT(11) DEFAULT NULL COMMENT 'Id de la provincia del cliente',
  `importe` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total de la factura',
  `impresa` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Indica si la factura ha sido impresa 1 o no 0',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Fecha de borrado de la factura',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `pago_caja` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada pago de caja',
  `concepto` VARCHAR(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT NULL COMMENT 'Concepto del pago',
  `importe` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe de dinero sacado de la caja para realizar el pago',
  `descripcion` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Descripción larga del concepto del pago',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `articulo_foto` (
  `id_foto` INT(11) NOT NULL COMMENT 'Id único para cada foto',
  `id_articulo` INT(11) NOT NULL COMMENT 'Id del artículo al que pertenece la foto',
  `orden` INT(11) NOT NULL DEFAULT 0 COMMENT 'Orden de la foto entre todas las fotos de un artículo',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_foto`,`id_articulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `caja_tipo` (
  `id_caja` INT(11) NOT NULL COMMENT 'Id de la caja del desglose',
  `id_tipo_pago` INT(11) NOT NULL COMMENT 'Id del tipo de pago',
  `operaciones` INT(11) NOT NULL DEFAULT 0 COMMENT 'Numero de operaciones por tipo de pago',
  `importe_total` FLOAT DEFAULT 0 COMMENT 'Importe del tipo de pago',
  `importe_real` FLOAT DEFAULT 0 COMMENT 'Importe real del tipo de pago',
  `importe_descuento` FLOAT DEFAULT 0 COMMENT 'Importe total de descuentos para un tipo de pago',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_caja`,`id_tipo_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `codigo_barras`
  ADD KEY `fk_codigo_barras_articulo_idx` (`id_articulo`),
  ADD CONSTRAINT `fk_codigo_barras_articulo` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `articulo_etiqueta_web`
  ADD KEY `fk_articulo_etiqueta_web_articulo_idx` (`id_articulo`),
  ADD KEY `fk_articulo_etiqueta_web_etiqueta_web_idx` (`id_etiqueta_web`),
  ADD CONSTRAINT `fk_articulo_etiqueta_web_articulo` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_articulo_etiqueta_web_etiqueta_web` FOREIGN KEY (`id_etiqueta_web`) REFERENCES `etiqueta_web` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `vista_pedido`
  ADD KEY `fk_vista_pedido_pedido_idx` (`id_pedido`),
  ADD CONSTRAINT `fk_vista_pedido_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `linea_pedido`
  ADD KEY `fk_linea_pedido_pedido_idx` (`id_pedido`),
  ADD KEY `fk_linea_pedido_articulo_idx` (`id_articulo`),
  ADD CONSTRAINT `fk_linea_pedido_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_linea_pedido_articulo` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `reserva`
  ADD KEY `fk_reserva_cliente_idx` (`id_cliente`),
  ADD CONSTRAINT `fk_reserva_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `linea_venta`
  ADD KEY `fk_linea_venta_venta_idx` (`id_venta`),
  ADD KEY `fk_linea_venta_articulo_idx` (`id_articulo`),
  ADD CONSTRAINT `fk_linea_venta_venta` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_linea_venta_articulo` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `proveedor`
  ADD KEY `fk_proveedor_foto_idx` (`id_foto`),
  ADD CONSTRAINT `fk_proveedor_foto` FOREIGN KEY (`id_foto`) REFERENCES `foto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `venta`
  ADD KEY `fk_venta_empleado_idx` (`id_empleado`),
  ADD KEY `fk_venta_cliente_idx` (`id_cliente`),
  ADD KEY `fk_venta_tipo_pago_idx` (`id_tipo_pago`),
  ADD CONSTRAINT `fk_venta_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_venta_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_venta_tipo_pago` FOREIGN KEY (`id_tipo_pago`) REFERENCES `tipo_pago` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `pdf_pedido`
  ADD KEY `fk_pdf_pedido_pedido_idx` (`id_pedido`),
  ADD CONSTRAINT `fk_pdf_pedido_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `pedido`
  ADD KEY `fk_pedido_proveedor_idx` (`id_proveedor`),
  ADD CONSTRAINT `fk_pedido_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `empleado_rol`
  ADD KEY `fk_empleado_rol_empleado_idx` (`id_empleado`),
  ADD CONSTRAINT `fk_empleado_rol_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `articulo`
  ADD KEY `fk_articulo_marca_idx` (`id_marca`),
  ADD KEY `fk_articulo_proveedor_idx` (`id_proveedor`),
  ADD CONSTRAINT `fk_articulo_marca` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_articulo_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `factura_venta`
  ADD KEY `fk_factura_venta_factura_idx` (`id_factura`),
  ADD KEY `fk_factura_venta_venta_idx` (`id_venta`),
  ADD CONSTRAINT `fk_factura_venta_factura` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_factura_venta_venta` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `articulo_etiqueta`
  ADD KEY `fk_articulo_etiqueta_articulo_idx` (`id_articulo`),
  ADD KEY `fk_articulo_etiqueta_etiqueta_idx` (`id_etiqueta`),
  ADD CONSTRAINT `fk_articulo_etiqueta_articulo` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_articulo_etiqueta_etiqueta` FOREIGN KEY (`id_etiqueta`) REFERENCES `etiqueta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `comercial`
  ADD KEY `fk_comercial_proveedor_idx` (`id_proveedor`),
  ADD CONSTRAINT `fk_comercial_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `linea_reserva`
  ADD KEY `fk_linea_reserva_reserva_idx` (`id_reserva`),
  ADD KEY `fk_linea_reserva_articulo_idx` (`id_articulo`),
  ADD CONSTRAINT `fk_linea_reserva_reserva` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_linea_reserva_articulo` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `proveedor_marca`
  ADD KEY `fk_proveedor_marca_proveedor_idx` (`id_proveedor`),
  ADD KEY `fk_proveedor_marca_marca_idx` (`id_marca`),
  ADD CONSTRAINT `fk_proveedor_marca_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_proveedor_marca_marca` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `factura`
  ADD KEY `fk_factura_cliente_idx` (`id_cliente`),
  ADD CONSTRAINT `fk_factura_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `articulo_foto`
  ADD KEY `fk_articulo_foto_foto_idx` (`id_foto`),
  ADD KEY `fk_articulo_foto_articulo_idx` (`id_articulo`),
  ADD CONSTRAINT `fk_articulo_foto_foto` FOREIGN KEY (`id_foto`) REFERENCES `foto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_articulo_foto_articulo` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `caja_tipo`
  ADD KEY `fk_caja_tipo_caja_idx` (`id_caja`),
  ADD KEY `fk_caja_tipo_tipo_pago_idx` (`id_tipo_pago`),
  ADD CONSTRAINT `fk_caja_tipo_caja` FOREIGN KEY (`id_caja`) REFERENCES `caja` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_caja_tipo_tipo_pago` FOREIGN KEY (`id_tipo_pago`) REFERENCES `tipo_pago` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
