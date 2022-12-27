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
      }
      .ticket-row {
        width: 100%;
        font-size: 8px;
      }
      .ticket-row-left {
        text-align: left;
        width: 46%;
        display: inline-block
      }
      .ticket-row-right {
        text-align: right;
        width: 52%;
        display: inline-block;
      }
      .ticket-date {
        display: inline-block;
        width: 45%;
        box-sizing: border-box;
        text-align: right;
      }
      table {
        width: 100%;
        border-bottom: 2px solid #000000;
      }
      td {
        box-sizing: border-box;
        font-size: 8px;
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
      .total {
        margin-top: 8px;
        line-height: 15px;
        font-size: 14px;
        font-weight: bold;
      }
      .total-label {
        display: inline-block;
        width: 65%;
        text-align: right;
      }
      .total-amount {
        display: inline-block;
        margin-left: 8px;
      }
      .forma-pago {
        line-height: 15px;
      }
      .forma-pago-label {
        display: inline-block;
        width: 65%;
        text-align: right;
      }
      .forma-pago-forma {
        display: inline-block;
        margin-left: 8px;
        text-align: right;
        width: 25%;
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
      .iva-row {
        margin-bottom: 4px;
        font-size: 7px;
      }
      .iva-row-iva {
        display: inline-block;
        width: 25%;
      }
      .iva-row-base {
        display: inline-block;
        width: 45%;
      }
      .iva-row-cuota {
        display: inline-block;
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
    <div class="ticket-info">
      <div class="ticket-row">
        <div class="ticket-row-left">Fecha: <?php echo $values['data']['date'] ?></div>
        <div class="ticket-row-right">Hora: <?php echo $values['data']['hour'] ?></div>
      </div>
      <div class="ticket-row">
        <div class="ticket-row-left">F. Simp.: <?php echo $values['data']['id'] ?></div>
        <div class="ticket-row-right">Vendedor: <?php echo $values['data']['employee'] ?></div>
      </div>
    </div>
    <table>
      <thead>
        <tr>
          <th class="left">Artículo</th>
          <th class="center">Ud.</th>
          <th class="right">PVP (€)</th>
          <th class="right">Total (€)</th>
        </tr>
      </thead>
      <tbody>
<?php foreach ($values['data']['lineas'] as $i => $linea): ?>
      <tr>
        <td class="left"><?php echo $linea->get('nombre_articulo') ?></td>
        <td class="center"><?php echo $linea->get('unidades') ?></td>
        <td class="right"><?php echo number_format($linea->get('pvp'), 2, ',') ?></td>
        <td class="right"><?php echo number_format($linea->get('unidades') * $linea->get('pvp'), 2, ',') ?></td>
      </tr>
<?php if (!is_null($linea->get('descuento')) && $linea->get('descuento') > 0): ?>
      <tr>
        <td class="center descuento"><strong>Descuento: <?php echo $linea->get('descuento') ?>%</strong></td>
        <td></td>
        <td class="right  descuento">-<?php echo $linea->get('pvp') * ($linea->get('descuento') / 100) ?></td>
        <td class="right descuento">-<?php echo $linea->get('unidades') * $linea->get('pvp') * ($linea->get('descuento') / 100) ?></td>
      </tr>
<?php endif ?>
<?php endforeach ?>
      </tbody>
    </table>
    <div class="total">
      <div class="total-label">Total:</div>
      <div class="total-amount"><?php echo number_format($values['data']['total'], 2, ',') ?> €</div>
    </div>
<?php if (!$values['data']['mixto']): ?>
  <div class="forma-pago">
    <div class="forma-pago-label"><?php echo $values['data']['forma_pago'] ?>:</div>
    <div class="forma-pago-forma"><?php echo number_format($values['data']['entregado'], 2, ',') ?> €</div>
  </div>
  <?php if ($values['data']['total'] != $values['data']['entregado']): ?>
    <div class="forma-pago">
      <div class="forma-pago-label">Cambio:</div>
      <div class="forma-pago-forma"><?php echo number_format($values['data']['entregado'] - $values['data']['total'], 2, ',') ?> €</div>
    </div>
  <?php endif ?>
<?php else: ?>
  <div class="forma-pago">
    <div class="forma-pago-label"><?php echo $values['data']['forma_pago'] ?>:</div>
    <div class="forma-pago-forma"><?php echo number_format($values['data']['entregado_otro'], 2, ',') ?> €</div>
  </div>
  <div class="forma-pago">
    <div class="forma-pago-label">Efectivo:</div>
    <div class="forma-pago-forma"><?php echo number_format($values['data']['entregado'], 2, ',') ?> €</div>
  </div>
  <?php if ($values['data']['total'] != ($values['data']['entregado'] + $values['data']['entregado_otro'])): ?>
    <div class="forma-pago">
      <div class="forma-pago-label">Cambio:</div>
      <div class="forma-pago-forma"><?php echo number_format(($values['data']['entregado'] + $values['data']['entregado_otro']) - $values['data']['total'], 2, ',') ?> €</div>
    </div>
  <?php endif ?>
<?php endif ?>

<?php if (!is_null($values['data']['cliente'])): ?>
    <div class="cliente">Cliente: <?php echo $values['data']['cliente']->get('nombre_apellidos') ?></div>
<?php endif ?>

    <div class="iva-incluido">I.V.A. incluído</div>
    <div class="iva-row">
      <div class="iva-row-iva">IVA</div>
      <div class="iva-row-base">Base imponible ( € )</div>
      <div class="iva-row-cuota">Cuota ( € )</div>
    </div>
<?php foreach ($values['data']['ivas'] as $iva): ?>
    <div class="iva-row">
      <div class="iva-row-iva"><?php echo $iva['iva'] ?>%</div>
      <div class="iva-row-base"><?php echo number_format($iva['base'], 2, ',') ?></div>
      <div class="iva-row-cuota"><?php echo number_format($iva['cuota_iva'], 2, ',') ?></div>
    </div>
    <?php if ($iva['re'] != 0): ?>
    <div class="iva-row">
      <div class="iva-row-iva">R.E.: <?php echo $iva['re'] ?>%</div>
      <div class="iva-row-base"><?php echo number_format($iva['base'], 2, ',') ?></div>
      <div class="iva-row-cuota"><?php echo number_format($iva['cuota_re'], 2, ',') ?></div>
    </div>
  <?php endif ?>
<?php endforeach ?>

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
