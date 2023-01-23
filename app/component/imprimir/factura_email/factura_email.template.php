<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>FACTURA <?php echo $values['id'] ?></title>
		<style></style>
  </head>
  <body>

    <div class="factura">
        <div class="header header-logo">
            <div class="col">
                <img src="<?php echo $values['logo'] ?>" alt="Logo">
            </div>
            <div class="col align-bottom">
                <div class="row-info">
                    <label>FECHA:</label>
                    <span><?php echo $values['fecha'] ?></span>
                </div>
                <div class="row-info">
                    <label>FACTURA:</label>
                    <span><?php echo $values['num_factura'] ?>_<?php echo $values['factura_year'] ?></span>
                </div>
            </div>
        </div>
        <div class="header">
            <div class="col col-datos">
                <?php echo $values['nombre_comercial'] ?>
                <br>
                <?php echo $values['cif'] ?>
            </div>
            <div class="col col-datos right">
                <strong><?php echo $values['cliente_nombre_apellidos'] ?></strong>
                <br>
                <strong><?php echo $values['cliente_dni_cif'] ?></strong>
            </div>
        </div>
        <div class="header">
            <div class="col col-datos">
                <?php echo $values['direccion'] ?>
                <br>
                <?php echo $values['telefono'] ?> - <?php echo $values['email'] ?>
            </div>
            <div class="col col-datos right">
                <?php echo $values['cliente_direccion'] ?>
                <br>
                <?php echo $values['cliente_codigo_postal'] ?> <?php echo $values['cliente_poblacion'] ?>
            </div>
        </div>

        <table class="datos">
            <thead>
                <tr>
                    <th class="col-concepto">CONCEPTO</th>
                    <th class="col-precio-iva-unidad">PVP</th>
                    <th class="col-precio-sin-iva">BASE UD</th>
                    <th class="col-unidades">UD</th>
                    <th class="col-subtotal">SUBTOTAL</th>
                    <th class="col-iva" colspan="2">IVA</th>
                    <th class="col-descuento">Descuento</th>
                    <th class="col-total">TOTAL</th>
                </tr>
            </thead>
            <tbody>
<?php foreach ($values['list'] as $item): ?>
                  <tr>
                      <td class="col-concepto">
                          <?php echo $item['concepto'] ?> (<?php echo $item['fecha'] ?>)
                      </td>
                      <td class="col-precio-iva-unidad right"><?php echo $item['precio_iva'] ?> <?php if (!is_null($item['precio_iva'])): ?> €<?php endif ?></td>
                      <td class="col-precio-sin-iva right"><?php echo $item['precio_sin_iva'] ?> <?php if (!is_null($item['precio_sin_iva'])): ?> €<?php endif ?></td>
                      <td class="col-unidades center"><?php echo $item['unidades'] ?></td>
                      <td class="col-subtotal right"><?php echo $item['subtotal'] ?> <?php if (!is_null($item['subtotal'])): ?> €<?php endif ?></td>
                      <td class="col-iva-porcentaje right"><?php echo $item['iva'] ?> <?php if (!is_null($item['iva'])): ?> %<?php endif ?></td>
                      <td class="col-iva-importe right"><?php echo $item['iva_importe'] ?> <?php if (!is_null($item['iva_importe'])): ?> €<?php endif ?></td>
                      <td class="col-descuento right"><?php echo $item['descuento'] ?> <?php if (!is_null($item['descuento'])): ?> €<?php endif ?></td>
                      <td class="col-total right"><?php echo $item['total'] ?> <?php if (!is_null($item['total'])): ?> €<?php endif ?></td>
                  </tr>
<?php foreach ($item['lineas'] as $subitem): ?>
                      <tr>
                          <td class="sub-item col-concepto"><?php echo $subitem['concepto'] ?></td>
                          <td class="sub-item col-precio-iva-unidad right"><?php echo $subitem['precio_iva'] ?> €</td>
                          <td class="sub-item col-precio-sin-iva right"><?php echo $subitem['precio_sin_iva'] ?> €</td>
                          <td class="sub-item col-unidades center"><?php echo $subitem['unidades'] ?></td>
                          <td class="sub-item col-subtotal right"><?php echo $subitem['subtotal'] ?> €</td>
                          <td class="sub-item col-iva-porcentaje right"><?php echo $subitem['iva'] ?> %</td>
                          <td class="sub-item col-iva-importe right"><?php echo $subitem['iva_importe'] ?> €</td>
                          <td class="sub-item col-descuento right"><?php echo $subitem['descuento'] ?> €</td>
                          <td class="sub-item col-total right"><?php echo $subitem['total'] ?> €</td>
                      </tr>
<?php endforeach ?>
<?php endforeach ?>
            </tbody>
        </table>

        <div class="totales">
            <table>
                <tr>
                    <td class="label">SUBTOTAL</td>
                    <td class="importe"><?php echo $values['subtotal'] ?> €</td>
                </tr>
<?php foreach ($values['ivas'] as $iva): ?>
                <tr>
                    <td class="label">IVA <?php echo $iva['iva'] ?>%</td>
                    <td class="importe"><?php echo $iva['importe'] ?> €</td>
                </tr>
<?php endforeach ?>
                <tr>
                    <td class="label">DESCUENTO</td>
                    <td class="importe"><?php echo $values['descuento'] ?> €</td>
                </tr>
                <tr>
                    <td class="label importe-total">TOTAL</td>
                    <td class="importe importe-total"><?php echo $values['total'] ?> €</td>
                </tr>
            </table>
        </div>

    </div>

  </body>
</html>
