CREATE TABLE `reserva` (
	  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada reserva',
	  `id_cliente` INT(11) NOT NULL COMMENT 'Id del cliente',
	  `total` FLOAT NOT NULL DEFAULT 0 COMMENT 'Importe total de la reserva',
	  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
	  `updated_at` DATETIME DEFAULT NULL COMMENT 'Fecha de última modificación del registro',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
	
	CREATE TABLE `linea_reserva` (
		  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id única de cada línea de reserva',
		  `id_reserva` INT(11) NOT NULL COMMENT 'Id de la reserva a la que pertenece la línea',
		  `id_articulo` INT(11) NOT NULL COMMENT 'Id del artículo que está siendo reservado',
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
	
	ALTER TABLE `reserva`
	  ADD KEY `fk_reserva_cliente_idx` (`id_cliente`),
	  ADD CONSTRAINT `fk_reserva_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
	
	ALTER TABLE `linea_reserva`
	  ADD KEY `fk_linea_reserva_reserva_idx` (`id_reserva`),
	  ADD KEY `fk_linea_reserva_articulo_idx` (`id_articulo`),
	  ADD CONSTRAINT `fk_linea_reserva_reserva` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
	  ADD CONSTRAINT `fk_linea_reserva_articulo` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
	