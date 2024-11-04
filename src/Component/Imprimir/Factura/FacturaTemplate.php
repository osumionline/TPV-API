<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>FACTURA <?php echo $id ?></title>
		<style>
      body {
        font-family: Roboto, "Helvetica Neue", sans-serif;
				font-size: 12px;
      }
      .factura {
        padding: 16px;
      }
      .factura .header {
        border: 0;
				width: 100%;
        margin-bottom: 6px;
      }
      .factura .header td {
        width: 50%;
      }
			.factura .header td.right {
        text-align: right;
      }
      .factura .header .col.align-bottom {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
      }
      .factura .header img {
        width: 300px;
				margin-bottom: 32px;
      }
      .factura .header .row-info {
        line-height: 24px;
        display: flex;
      }
      .factura .header .row-info label {
        flex: 1;
        font-weight: bold;
        text-align: right;
      }
      .factura .header .row-info span {
        width: 150px;
        text-align: right;
      }
      .factura .header .col-datos {
        font-size: 14px;
      }
      .factura .header-logo {
        margin-bottom: 32px;
      }
      .factura .datos {
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
        border-right: 1px solid #000;
        border-spacing: 0;
        margin: 32px 0;
        width: 100%;
      }
      .factura .datos th {
        border-bottom: 1px solid #000;
      }
      .factura .datos th, .factura .datos td {
        border-left: 1px solid #000;
        padding: 6px;
      }
      .factura .datos th.right, .factura .datos td.right {
        text-align: right;
      }
      .factura .datos th.center, .factura .datos td.center {
        text-align: center;
      }
      .factura .datos th.col-concepto, .factura .datos td.col-concepto {
        width: 33%;
      }
      .factura .datos th.col-precio-iva-unidad, .factura .datos td.col-precio-iva-unidad {
        width: 9%;
      }
      .factura .datos th.col-precio-sin-iva, .factura .datos td.col-precio-sin-iva {
        width: 9%;
      }
      .factura .datos th.col-unidades, .factura .datos td.col-unidades {
        width: 5%;
      }
      .factura .datos th.col-subtotal, .factura .datos td.col-subtotal {
        width: 9%;
      }
      .factura .datos th.col-iva, .factura .datos td.col-iva {
        width: 18%;
      }
      .factura .datos th.col-iva-porcentaje, .factura .datos td.col-iva-porcentaje {
        width: 9%;
      }
      .factura .datos th.col-iva-importe, .factura .datos td.col-iva-importe {
        width: 9%;
      }
      .factura .datos th.col-descuento, .factura .datos td.col-descuento {
        width: 9%;
      }
      .factura .datos th.col-total, .factura .datos td.col-total {
        width: 9%;
      }
      .factura .datos th.sub-item, .factura .datos td.sub-item {
        background-color: #eee;
      }
      .factura .datos th.sub-item.col-concepto, .factura .datos td.sub-item.col-concepto {
        padding-left: 16px;
      }
      .factura .totales table {
        border: 0;
        border-spacing: 0;
        width: 100%;
        box-sizing: border-box;
      }
      .factura .totales table th, .factura .totales table td {
        padding: 6px;
      }
			.factura .totales table th.label, .factura .totales table td.filler {
        width: 70%;
      }
      .factura .totales table th.label, .factura .totales table td.label {
        text-align: right;
				border-left: 1px solid #000;
      }
			.factura .totales table th.label, .factura .totales table tr:first-child td.label {
				border-top: 1px solid #000;
			}
			.factura .totales table th.label, .factura .totales table tr:last-child td.label {
				border-bottom: 1px solid #000;
			}
      .factura .totales table th.importe, .factura .totales table td.importe {
        text-align: right;
        background-color: #ccc;
				border-right: 1px solid #000;
      }
			.factura .totales table th.label, .factura .totales table tr:first-child td.importe {
				border-top: 1px solid #000;
			}
			.factura .totales table th.label, .factura .totales table tr:last-child td.importe {
				border-bottom: 1px solid #000;
			}
      .factura .totales table th.importe-total, .factura .totales table td.importe-total {
        font-weight: bold;
        border-top: 1px solid #000;
        font-size: 1.2em;
      }
      .factura .pagado {
        padding: 10px 0;
        border-top: 2px solid #000;
        border-bottom: 2px solid #000;
        width: 40%;
        margin: 48px auto;
        text-align: center;
        font-size: 2em;
        color: #888;
      }
    </style>
  </head>
  <body>

    <div class="factura">
				<table class="header">
					<tbody>
						<tr>
							<td><img src="<?php echo $logo ?>" alt="Logo"></td>
							<td class="right">
								<div class="row-info">
										<label>FECHA:</label>
										<span><?php echo $fecha ?></span>
								</div>
								<div class="row-info">
										<label>FACTURA:</label>
										<span><?php echo $num_factura ?>_<?php echo $factura_year ?></span>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $nombre_comercial ?>
								<br>
								<?php echo $cif ?>
							</td>
							<td class="right">
								<strong><?php echo $cliente_nombre_apellidos ?></strong>
								<br>
								<strong><?php echo $cliente_dni_cif ?></strong>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $direccion ?>
								<br>
								<?php echo $telefono ?> - <?php echo $email ?>
							</td>
							<td class="right">
								<?php echo $cliente_direccion ?>
								<br>
								<?php echo $cliente_codigo_postal ?> - <?php echo $cliente_poblacion ?>
							</td>
						</tr>
					</tbody>
				</table>

        <table class="datos">
            <thead>
                <tr>
                    <th class="col-concepto">CONCEPTO</th>
                    <th class="col-precio-iva-unidad">PVP</th>
                    <th class="col-precio-sin-iva">BASE UD</th>
                    <th class="col-unidades">UD</th>
                    <th class="col-subtotal">SUBT.</th>
                    <th class="col-iva" colspan="2">IVA</th>
                    <th class="col-descuento">DESC.</th>
                    <th class="col-total">TOTAL</th>
                </tr>
            </thead>
            <tbody>
<?php foreach ($list as $item): ?>
                  <tr>
                      <td class="col-concepto">
                          <?php echo $item['concepto'] ?> (<?php echo $item['fecha'] ?>)
                      </td>
                      <td class="col-precio-iva-unidad right">
                        <?php echo number_format($item['precio_iva'], 2, ',', '') ?> <?php if (!is_null($item['precio_iva'])): ?> €<?php endif ?>
                      </td>
                      <td class="col-precio-sin-iva right">
                        <?php echo number_format($item['precio_sin_iva'], 2, ',', '') ?> <?php if (!is_null($item['precio_sin_iva'])): ?> €<?php endif ?>
                      </td>
                      <td class="col-unidades center"></td>
                      <td class="col-subtotal right">
                        <?php echo number_format($item['subtotal'], 2, ',', '') ?> <?php if (!is_null($item['subtotal'])): ?> €<?php endif ?>
                      </td>
                      <td class="col-iva-porcentaje right">
                        <?php echo !is_null($item['iva']) ? number_format($item['iva'], 2, ',', '') : '' ?> <?php if (!is_null($item['iva'])): ?> %<?php endif ?>
                      </td>
                      <td class="col-iva-importe right">
                        <?php echo number_format($item['iva_importe'], 2, ',', '') ?> <?php if (!is_null($item['iva_importe'])): ?> €<?php endif ?>
                      </td>
                      <td class="col-descuento right">
                        <?php echo number_format($item['descuento'], 2, ',', '') ?> <?php if (!is_null($item['descuento'])): ?> €<?php endif ?>
                      </td>
                      <td class="col-total right">
                        <?php echo number_format($item['total'], 2, ',', '') ?> <?php if (!is_null($item['total'])): ?> €<?php endif ?>
                      </td>
                  </tr>
<?php foreach ($item['lineas'] as $subitem): ?>
                      <tr>
                          <td class="sub-item col-concepto"><?php echo $subitem['concepto'] ?></td>
                          <td class="sub-item col-precio-iva-unidad right">
                            <?php echo number_format($subitem['precio_iva'], 2, ',', '') ?> €
                          </td>
                          <td class="sub-item col-precio-sin-iva right">
                            <?php echo number_format($subitem['precio_sin_iva'], 2, ',', '') ?> €
                          </td>
                          <td class="sub-item col-unidades center">
                            <?php echo $subitem['unidades'] ?>
                          </td>
                          <td class="sub-item col-subtotal right">
                            <?php echo number_format($subitem['subtotal'], 2, ',', '') ?> €
                          </td>
                          <td class="sub-item col-iva-porcentaje right">
                            <?php echo number_format($subitem['iva'], 2, ',', '') ?> %
                          </td>
                          <td class="sub-item col-iva-importe right">
                            <?php echo number_format($subitem['iva_importe'], 2, ',', '') ?> €
                          </td>
                          <td class="sub-item col-descuento right">
                            <?php echo number_format($subitem['descuento'], 2, ',', '') ?> €
                          </td>
                          <td class="sub-item col-total right">
                            <?php echo number_format($subitem['total'], 2, ',', '') ?> €
                          </td>
                      </tr>
<?php endforeach ?>
<?php endforeach ?>
            </tbody>
        </table>

        <div class="totales">
            <table>
                <tr>
										<td class="filler">&nbsp;</td>
                    <td class="label">SUBTOTAL</td>
                    <td class="importe">
                      <?php echo number_format($subtotal, 2, ',', '') ?> €
                    </td>
                </tr>
<?php foreach ($ivas as $iva): ?>
                <tr>
										<td class="filler">&nbsp;</td>
                    <td class="label">IVA <?php echo $iva['iva'] ?>%</td>
                    <td class="importe">
                      <?php echo number_format($iva['cuota_iva'], 2, ',', '') ?> €
                    </td>
                </tr>
<?php endforeach ?>
<?php if ($descuento != 0): ?>
                <tr>
										<td class="filler">&nbsp;</td>
                    <td class="label">DESCUENTO</td>
                    <td class="importe">
                      <?php echo number_format($descuento, 2, ',', '') ?> €
                    </td>
                </tr>
<?php endif ?>
                <tr>
										<td class="filler">&nbsp;</td>
                    <td class="label importe-total">TOTAL</td>
                    <td class="importe importe-total">
                      <?php echo number_format($total, 2, ',', '') ?> €
                    </td>
                </tr>
            </table>
        </div>

        <div class="pagado">PAGADO</div>

    </div>

  </body>
</html>
