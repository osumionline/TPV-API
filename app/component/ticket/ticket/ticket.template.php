<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Ticket</title>
    <style type="text/css">
      html, body {
        margin: 0;
        font-size: 10px;
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
      .ticket-id {
        display: inline-block;
        width: 45%;
        box-sizing: border-box;
        margin-left: 3%;
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
      .legal {
        text-align: center;
        line-height: 14px;
        font-size: 8px;
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
      <div class="ticket-id"><?php echo $values['data']['id'] ?></div>
      <div class="ticket-date"><?php echo $values['data']['date'] ?></div>
    </div>
    <table>
<?php foreach ($values['data']['lineas'] as $i => $linea): ?>
      <tr>
        <td>Loc: <?php echo $linea->getArticulo()->get('localizador') ?></td>
        <td>PVP: <?php echo number_format($linea->get('pvp'), 2, ',') ?> €</td>
        <td>Uds: <?php echo $linea->get('unidades') ?></td>
        <td>Tot: <?php echo number_format($linea->get('importe'), 2, ',') ?> €</td>
      </tr>
      <tr>
        <td colspan="4"><?php echo $linea->getArticulo()->get('nombre') ?></td>
      </tr>
<?php if ($i<count($values['data']['lineas'])-1): ?>
      <tr><td colspan="4">&nbsp;</td></tr>
<?php endif ?>
<?php endforeach ?>
    </table>
    <div class="total">
      <div class="total-label">TOTAL:</div>
      <div class="total-amount"><?php echo number_format($values['data']['total'], 2, ',') ?> €</div>
    </div>
    <div class="forma-pago">
      <div class="forma-pago-label">Forma de pago:</div>
      <div class="forma-pago-forma"><?php echo $values['data']['forma_pago'] ?></div>
    </div>
<?php if (!is_null($values['data']['cliente'])): ?>
    <div class="cliente">Cliente: <?php echo $values['data']['cliente']->get('nombre_apellidos') ?></div>
<?php endif ?>
    <div class="iva-incluido">I.V.A. incluído</div>
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
  </body>
</html>
