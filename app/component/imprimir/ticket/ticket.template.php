<?php
  $descuento_total = 0;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Ticket</title>
    <style type="text/css">
      html, body {
        margin: 0;
        font-size: 10px;
        font-family: Helvetica;
      }
      .logo {
        margin: 8px auto;
        text-align: center;
      }
      .header {
        text-align: center;
        line-height: 10px;
      }
      .header-small {
        text-align: center;
        line-height: 10px;
        font-size: 8px;
      }
      .social {
        margin-top: 6px;
        text-align: center;
      }
      .social-item {
        display: inline-block;
        padding-right: 6px;
        box-sizing: border-box;
      }
      .social-item img {
        display: inline-block;
        margin-right: 4px;
      }
      .social-item span {
        line-height: 12px;
      }
      .ticket-info {
        border-top: 2px solid #000000;
        border-bottom: 2px solid #000000;
        margin-top: 8px;
        line-height: 15px;
        width: 100%;
        border-spacing: 0;
      }
      .ticket-info td {
        width: 50%;
        font-size: 6px;
        line-height: 10px;
      }
      .venta {
        width: 100%;
        border-bottom: 2px solid #000000;
        border-spacing: 0;
      }
      .venta thead {
        font-size: 7px;
      }
      .venta td {
        box-sizing: border-box;
        font-size: 8px;
        line-height: 10px;
      }
      .table-articulo {
        width: 50%;
      }
      .table-articulo-wide {
        width: 80%;
      }
      .table-unidades {
        width: 10%;
      }
      .table-unidades-wide {
        width: 20%;
        text-align: right;
      }
      .table-pvp {
        width: 20%;
      }
      .table-total {
        width: 20%;
      }
      .descuento {
        font-style: italic;
      }
      .left {
        text-align: left;
      }
      .center {
        text-align: center;
      }
      .right {
        text-align: right;
      }
      .regalo {
        text-align: center;
        font-weight: bold;
        margin: 8px 0;
      }
      .reserva {
        text-align: center;
        font-weight: bold;
        font-size: 16px;
        margin: 8px 0;
      }
      .total {
        width: 100%;
        margin-top: 4px;
        line-height: 8px;
        font-weight: bold;
        font-size: 8px;
      }
      .total-header {
        font-size: 10px;
      }
      .total-label {
        display: inline-block;
        width: 65%;
        text-align: right;
      }
      .total-amount {
        display: inline-block;
        width: 30%;
        text-align: right;
      }
      .cliente {
        text-align: center;
        font-weight: bold;
        margin: 4px;
        font-size: 8px;
      }
      .iva-incluido {
        text-align: center;
        margin: 2px;
      }
      .iva {
        width: 100%;
        margin-bottom: 2px;
        font-size: 7px;
        border-spacing: 0;
      }
      .iva-header {
        border-bottom: 1px solid #ccc;
      }
      .iva-iva {
        width: 25%;
      }
      .iva-base {
        width: 45%;
      }
      .iva-cuota {
        width: 25%;
        text-align: right;
      }
      .legal {
        text-align: center;
        line-height: 10px;
        font-size: 6px;
        margin-bottom: 2px;
      }
      .qr {
        text-align: center;
      }
      .qr > label {
        display: block;
      }
      .qr > span {
        display: block;
        margin-top: 8px;
      }
    </style>
  </head>
  <body>
    <div class="logo">
      <img src="<?php echo $values['data']['logo'] ?>" width="100">
    </div>
    <div class="header"><?php echo $values['data']['direccion'] ?> <?php echo $values['data']['poblacion'] ?></div>
    <div class="header">Tel: <?php echo $values['data']['telefono'] ?></div>
    <div class="header-small">NIF: <?php echo $values['data']['nif'] ?></div>
    <div class="social">
<?php foreach ($values['data']['social'] as $value): ?>
      <div class="social-item">
        <img src="<?php echo $value[0] ?>" width="10">
        <span><?php echo $value[1] ?></span>
      </div>
<?php endforeach ?>
    </div>
    <table class="ticket-info">
      <tr>
        <td>Fecha: <?php echo $values['data']['date'] ?></td>
        <td class="right">Hora: <?php echo $values['data']['hour'] ?></td>
      </tr>
<?php if ($values['data']['tipo'] !== 'reserva'): ?>
      <tr>
        <td>F. Simp: <?php echo $values['data']['timestamp'].'-'.$values['data']['num_venta'] ?></td>
        <td class="right">Le atendió: <?php echo $values['data']['employee'] ?></td>
      </tr>
<?php endif ?>
    </table>
    <table class="venta">
      <thead>
        <tr>
          <th class="<?php echo ($values['data']['tipo'] !== 'regalo') ? 'table-articulo' : 'table-articulo-wide' ?> left">Artículo</th>
          <th class="<?php echo ($values['data']['tipo'] !== 'regalo') ? 'table-unidades center' : 'table-unidades-wide' ?>">Ud.</th>
          <?php if ($values['data']['tipo'] !== 'regalo'): ?>
          <th class="table-pvp right">PVP (€)</th>
          <th class="table-total right">Total (€)</th>
          <?php endif ?>
        </tr>
      </thead>
      <tbody>
<?php foreach ($values['data']['lineas'] as $i => $linea): ?>
      <tr>
        <td class="table-articulo left"><?php echo $linea->get('nombre_articulo') ?></td>
        <td class="<?php echo ($values['data']['tipo'] !== 'regalo') ? 'table-unidades center' : 'table-unidades-wide' ?>"><?php echo $linea->get('unidades') ?></td>
        <?php if ($values['data']['tipo'] !== 'regalo'): ?>
        <td class="table-pvp right"><?php echo number_format($linea->get('pvp'), 2, ',') ?></td>
        <td class="table-total right"><?php echo number_format($linea->get('unidades') * $linea->get('pvp'), 2, ',') ?></td>
      <?php endif ?>
      </tr>
<?php if (!is_null($linea->get('descuento')) && $linea->get('descuento') > 0 && $values['data']['tipo'] !== 'regalo'): ?>
      <tr>
        <?php if (!$linea->get('regalo')): ?>
        <td class="table-articulo center descuento"><strong>Descuento: <?php echo $linea->get('descuento') ?>%</strong></td>
        <?php else: ?>
        <td class="table-articulo center descuento"><strong>Regalo</strong></td>
        <?php endif ?>
        <td class="table-unidades">&nbsp;</td>
        <td class="table-pvp right descuento">-<?php echo number_format(($linea->get('pvp') * ($linea->get('descuento') / 100)), 2, ',') ?></td>
        <td class="table-total right descuento">-<?php
        echo number_format(($linea->get('unidades') * $linea->get('pvp') * ($linea->get('descuento') / 100)), 2, ',');
        $descuento_total += ($linea->get('unidades') * $linea->get('pvp') * ($linea->get('descuento') / 100));
        ?></td>
      </tr>
<?php endif ?>
<?php endforeach ?>
      </tbody>
    </table>
<?php if ($values['data']['tipo'] == 'venta' || $values['data']['tipo'] == 'reserva'): ?>
    <table class="total">
      <tr class="total-header">
        <td class="total-label">Total:</td>
        <td class="total-amount"><?php echo number_format($values['data']['total'], 2, ',') ?> €</td>
      </tr>
  <?php if ($values['data']['tipo'] !== 'reserva'): ?>
    <?php if (!$values['data']['mixto']): ?>
      <tr>
        <td class="total-label"><?php echo $values['data']['forma_pago'] ?>:</td>
        <td class="total-amount">
          <?php echo number_format(is_null($values['data']['id_tipo_pago']) ? $values['data']['entregado'] : $values['data']['total'], 2, ',') ?> €
        </td>
      </tr>
      <?php if (is_null($values['data']['id_tipo_pago'])): ?>
      <tr>
        <td class="total-label">Cambio:</td>
        <td class="total-amount"><?php echo number_format($values['data']['entregado'] - $values['data']['total'], 2, ',') ?> €</td>
      </tr>
      <?php endif ?>
      <?php if ($descuento_total > 0): ?>
    <tr>
      <td class="total-label">Descuento:</td>
      <td class="total-amount">-<?php echo number_format($descuento_total, 2, ',') ?> €</td>
    </tr>
      <?php endif ?>
    <?php else: ?>
      <tr>
        <td class="total-label"><?php echo $values['data']['forma_pago'] ?>:</td>
        <td class="total-amount"><?php echo number_format($values['data']['entregado_otro'], 2, ',') ?> €</td>
      </tr>
      <tr>
        <td class="total-label">Efectivo:</td>
        <td class="total-amount"><?php echo number_format($values['data']['entregado'], 2, ',') ?> €</td>
      </tr>
      <?php if ($values['data']['total'] != ($values['data']['entregado'] + $values['data']['entregado_otro'])): ?>
      <tr>
        <td class="total-label">Cambio:</td>
        <td class="total-amount"><?php echo number_format(($values['data']['entregado'] + $values['data']['entregado_otro']) - $values['data']['total'], 2, ',') ?> €</td>
      </tr>
      <?php endif ?>
    <?php endif ?>
  <?php endif ?>
    </table>
<?php if (!is_null($values['data']['cliente'])): ?>
    <div class="cliente">Cliente: <?php echo $values['data']['cliente']->get('nombre_apellidos') ?></div>
<?php endif ?>

    <div class="iva-incluido">I.V.A. incluído</div>
    <table class="iva">
      <tr>
        <td class="iva-header iva-iva">IVA</td>
        <td class="iva-header iva-base">Base imponible ( € )</td>
        <td class="iva-header iva-cuota">Cuota ( € )</td>
      </tr>
<?php foreach ($values['data']['ivas'] as $iva): ?>
      <tr>
        <td class="iva-iva"><?php echo $iva['iva'] ?>%</td>
        <td class="iva-base"><?php echo number_format($iva['base'], 2, ',') ?></td>
        <td class="iva-cuota"><?php echo number_format($iva['cuota_iva'], 2, ',') ?></td>
      </tr>
<?php endforeach ?>
    </table>
<?php endif ?>
<?php if ($values['data']['tipo'] == 'regalo'): ?>
    <div class="regalo">TICKET REGALO</div>
<?php endif ?>

<?php if ($values['data']['tipo'] == 'reserva'): ?>
<div class="reserva">RESERVA</div>
<?php endif ?>
<?php if ($values['data']['tipo'] !== 'reserva'): ?>
    <div class="qr">
      <img src="<?php echo $values['data']['qr'] ?>" width="80">
    </div>

    <div class="legal">
      No se admitirán cambios ni devoluciones sin ticket o de productos abiertos o en mal estado
      <br>
      Plazo máximo de 15 días para devoluciones
      <br>
      No se admitirán devoluciones de congelados
      <br>
      Resto de condiciones en tienda
      <br>
      GRACIAS POR SU VISITA
      <br>
      ESKERRIK ASKO ETORTZEAGATIK
    </div>
<?php endif ?>
<?php if (!is_null($values['data']['tbai_qr'])) : ?>
    <div class="qr">
      <label>TICKET BAI</label>
      <img src="data:image/png;base64,<?php echo $values['data']['tbai_qr'] ?>" width="80">
      <span><?php echo $values['data']['tbai_huella'] ?></span>
    </div>
<?php endif ?>
  </body>
</html>
