<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Ticket</title>
    <style type="text/css">
      html, body {
        margin: 4px;
        font-size: 10px;
        font-family: Helvetica;
      }
      .logo {
        margin: 8px auto;
        text-align: center;
      }
      .center {
        text-align: center;
        line-height: 15px;
      }
      .social {
        margin-top: 8px;
        text-align: center;
      }
      .social-item {
        display: inline-block;
        padding-right: 8px;
        box-sizing: border-box;
      }
      .social-item img {
        display: inline-block;
        margin-right: 4px;
      }
      .social-item span {
        line-height: 14px;
      }
      .ticket-info {
        border-top: 2px solid #000000;
        border-bottom: 2px solid #000000;
        margin-top: 8px;
        line-height: 15px;
        width: 100%;
      }
      .ticket-info td {
        width: 50%;
        font-size: 8px;
      }
      .venta {
        width: 100%;
        border-bottom: 2px solid #000000;
      }
      .venta thead {
        font-size: 7px;
      }
      .venta td {
        box-sizing: border-box;
        font-size: 8px;
      }
      .table-articulo {
        width: 50%;
      }
      .table-unidades {
        width: 10%;
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
      .total {
        width: 100%;
        margin-top: 8px;
        line-height: 15px;
        font-weight: bold;
      }
      .total-header {
        font-size: 14px;
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
        margin: 8px;
      }
      .iva-incluido {
        text-align: center;
        margin: 8px;
      }
      .iva {
        width: 100%;
        margin-bottom: 4px;
        font-size: 7px;
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
        line-height: 14px;
        font-size: 6px;
        margin-bottom: 16px;
      }
      .qr {
        text-align: center;
      }
    </style>
  </head>
  <body>
    <div class="logo">
      <img src="<?php echo $values['data']['logo'] ?>" width="100">
    </div>
    <div class="center"><?php echo $values['data']['direccion'] ?></div>
    <div class="center">Tel: <?php echo $values['data']['telefono'] ?> - NIF: <?php echo $values['data']['nif'] ?></div>
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
      <tr>
        <td>F. Simp.: <?php echo $values['data']['id'] ?></td>
        <td class="right">Vendedor: <?php echo $values['data']['employee'] ?></td>
      </tr>
    </table>
    <table class="venta">
      <thead>
        <tr>
          <th class="table-articulo left">Artículo</th>
          <th class="table-unidades center">Ud.</th>
          <th class="table-pvp right">PVP (€)</th>
          <th class="table-total right">Total (€)</th>
        </tr>
      </thead>
      <tbody>
<?php foreach ($values['data']['lineas'] as $i => $linea): ?>
      <tr>
        <td class="table-articulo left"><?php echo $linea->get('nombre_articulo') ?></td>
        <td class="table-unidades center"><?php echo $linea->get('unidades') ?></td>
        <td class="table-pvp right"><?php echo !$values['data']['regalo'] ? number_format($linea->get('pvp'), 2, ',') : '-' ?></td>
        <td class="table-total right"><?php echo !$values['data']['regalo'] ? number_format($linea->get('unidades') * $linea->get('pvp'), 2, ',') : '-' ?></td>
      </tr>
<?php if (!is_null($linea->get('descuento')) && $linea->get('descuento') > 0 && !$values['data']['regalo']): ?>
      <tr>
        <td class="table-articulo center descuento"><strong>Descuento: <?php echo $linea->get('descuento') ?>%</strong></td>
        <td class="table-unidades">&nbsp;</td>
        <td class="table-pvp right descuento">-<?php echo $linea->get('pvp') * ($linea->get('descuento') / 100) ?></td>
        <td class="table-total right descuento">-<?php echo $linea->get('unidades') * $linea->get('pvp') * ($linea->get('descuento') / 100) ?></td>
      </tr>
<?php endif ?>
<?php endforeach ?>
      </tbody>
    </table>
<?php if (!$values['data']['regalo']): ?>
    <table class="total">
      <tr class="total-header">
        <td class="total-label">Total:</td>
        <td class="total-amount"><?php echo number_format($values['data']['total'], 2, ',') ?> €</td>
      </tr>
<?php if (!$values['data']['mixto']): ?>
      <tr>
        <td class="total-label"><?php echo $values['data']['forma_pago'] ?>:</td>
        <td class="total-amount"><?php echo number_format($values['data']['entregado'], 2, ',') ?> €</td>
      </tr>
  <?php if ($values['data']['total'] != $values['data']['entregado']): ?>
      <tr>
        <td class="total-label">Cambio:</td>
        <td class="total-amount"><?php echo number_format($values['data']['entregado'] - $values['data']['total'], 2, ',') ?> €</td>
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
    </table>
<?php if (!is_null($values['data']['cliente'])): ?>
    <div class="cliente">Cliente: <?php echo $values['data']['cliente']->get('nombre_apellidos') ?></div>
<?php endif ?>

    <div class="iva-incluido">I.V.A. incluído</div>
    <table class="iva">
      <tr>
        <td class="iva-iva">IVA</td>
        <td class="iva-base">Base imponible ( € )</td>
        <td class="iva-cuota">Cuota ( € )</td>
      </tr>
<?php foreach ($values['data']['ivas'] as $iva): ?>
      <tr>
        <td class="iva-iva"><?php echo $iva['iva'] ?>%</td>
        <td class="iva-base"><?php echo number_format($iva['base'], 2, ',') ?></td>
        <td class="iva-cuota"><?php echo number_format($iva['cuota_iva'], 2, ',') ?></td>
      </tr>
    <?php if ($iva['re'] != 0): ?>
      <tr>
        <td class="iva-iva">R.E.: <?php echo $iva['re'] ?>%</td>
        <td class="iva-base"><?php echo number_format($iva['base'], 2, ',') ?></td>
        <td class="iva-cuota"><?php echo number_format($iva['cuota_re'], 2, ',') ?></td>
      </tr>
  <?php endif ?>
<?php endforeach ?>
    </table>
<?php else: ?>
    <div class="regalo">TICKET REGALO</div>
<?php endif ?>
    <div class="legal">
      No se admitirán cambios ni devoluciones sin ticket o sin caja
      <br>
      Plazo máximo de 15 días para devoluciones
      <br>
      No se admitirán devoluciones de complementos
      <br>
      Resto de condiciones en tienda
      <br>
      GRACIAS POR SU VISITA
      <br>
      ESKERRIK ASKO ETORTZEAGATIK
    </div>

    <div class="qr">
      <img src="<?php echo $values['data']['qr'] ?>" width="80">
    </div>
  </body>
</html>
