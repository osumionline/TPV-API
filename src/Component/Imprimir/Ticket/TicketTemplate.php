<?php
  $descuento_total = 0;
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Ticket</title>
    <style>
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
      <img src="<?php echo $data['logo'] ?>" width="100" alt="Logo">
    </div>
    <div class="header"><?php echo $data['direccion'] ?> <?php echo $data['poblacion'] ?></div>
    <div class="header">Tel: <?php echo $data['telefono'] ?></div>
    <div class="header-small">NIF: <?php echo $data['nif'] ?></div>
    <div class="social">
<?php foreach ($data['social'] as $value): ?>
      <div class="social-item">
        <img src="<?php echo $value[0] ?>" width="10" alt="<?php echo $value[1] ?>">
        <span><?php echo $value[1] ?></span>
      </div>
<?php endforeach ?>
    </div>
    <table class="ticket-info">
      <tr>
        <td>Fecha: <?php echo $data['date'] ?></td>
        <td class="right">Hora: <?php echo $data['hour'] ?></td>
      </tr>
<?php if ($data['tipo'] !== 'reserva'): ?>
      <tr>
        <td>F. Simp: <?php echo $data['timestamp'].'-'.$data['num_venta'] ?></td>
        <td class="right">Le atendió: <?php echo $data['employee'] ?></td>
      </tr>
<?php endif ?>
    </table>
    <table class="venta">
      <thead>
        <tr>
          <th class="<?php echo ($data['tipo'] !== 'regalo') ? 'table-articulo' : 'table-articulo-wide' ?> left">Artículo</th>
          <th class="<?php echo ($data['tipo'] !== 'regalo') ? 'table-unidades center' : 'table-unidades-wide' ?>">Ud.</th>
          <?php if ($data['tipo'] !== 'regalo'): ?>
          <th class="table-pvp right">PVP (€)</th>
          <th class="table-total right">Total (€)</th>
          <?php endif ?>
        </tr>
      </thead>
      <tbody>
<?php foreach ($data['lineas'] as $i => $linea): ?>
      <tr>
        <td class="table-articulo left"><?php echo $linea->nombre_articulo ?></td>
        <td class="<?php echo ($data['tipo'] !== 'regalo') ? 'table-unidades center' : 'table-unidades-wide' ?>"><?php echo $linea->unidades ?></td>
        <?php if ($data['tipo'] !== 'regalo'): ?>
        <td class="table-pvp right"><?php echo number_format($linea->pvp, 2, ',') ?></td>
        <td class="table-total right"><?php echo number_format($linea->unidades * $linea->pvp, 2, ',') ?></td>
      <?php endif ?>
      </tr>
<?php if (
          (
            (!is_null($linea->descuento) && $linea->descuento > 0) ||
            (!is_null($linea->importe_descuento) && $linea->importe_descuento > 0)
          ) && $data['tipo'] !== 'regalo'): ?>
      <tr>
        <?php if (!$linea->regalo): ?>
        <td class="table-articulo center descuento">
          <strong>
            Descuento:
            <?php if (!is_null($linea->descuento) && $linea->descuento > 0): ?>
              <?php echo $linea->descuento ?>%
            <?php endif ?>
            <?php if (!is_null($linea->importe_descuento) && $linea->importe_descuento > 0): ?>
              <?php echo number_format($linea->importe_descuento, 2, ',') ?>€
            <?php endif ?>
          </strong>
        </td>
        <?php else: ?>
        <td class="table-articulo center descuento"><strong>Regalo</strong></td>
        <?php endif ?>
        <td class="table-unidades">&nbsp;</td>
        <td class="table-pvp right descuento">
          <?php if (!is_null($linea->descuento) && $linea->descuento > 0): ?>
            -<?php echo number_format(($linea->pvp * ($linea->descuento / 100)), 2, ',') ?>
          <?php endif ?>
          <?php if (!is_null($linea->importe_descuento) && $linea->importe_descuento > 0): ?>
            -<?php echo number_format($linea->importe_descuento, 2, ',') ?>
          <?php endif ?>
        </td>
        <td class="table-total right descuento">-<?php
          if (!is_null($linea->descuento) && $linea->descuento > 0) {
            echo number_format(($linea->unidades * $linea->pvp * ($linea->descuento / 100)), 2, ',');
            $descuento_total += ($linea->unidades * $linea->pvp * ($linea->descuento / 100));
          }
          if (!is_null($linea->importe_descuento) && $linea->importe_descuento > 0) {
            echo number_format($linea->importe_descuento, 2, ',');
            $descuento_total += $linea->importe_descuento;
          }
        ?></td>
      </tr>
<?php endif ?>
<?php endforeach ?>
      </tbody>
    </table>
<?php if ($data['tipo'] == 'venta' || $data['tipo'] == 'reserva'): ?>
    <table class="total">
      <tr class="total-header">
        <td class="total-label">Total:</td>
        <td class="total-amount"><?php echo number_format($data['total'], 2, ',') ?> €</td>
      </tr>
  <?php if ($data['tipo'] !== 'reserva'): ?>
    <?php if (!$data['mixto']): ?>
      <tr>
        <td class="total-label"><?php echo $data['forma_pago'] ?>:</td>
        <td class="total-amount">
          <?php echo number_format(is_null($data['id_tipo_pago']) ? $data['entregado'] : $data['total'], 2, ',') ?> €
        </td>
      </tr>
      <?php if (is_null($data['id_tipo_pago'])): ?>
      <tr>
        <td class="total-label">Cambio:</td>
        <td class="total-amount"><?php echo number_format($data['entregado'] - $data['total'], 2, ',') ?> €</td>
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
        <td class="total-label"><?php echo $data['forma_pago'] ?>:</td>
        <td class="total-amount"><?php echo number_format($data['entregado_otro'], 2, ',') ?> €</td>
      </tr>
      <tr>
        <td class="total-label">Efectivo:</td>
        <td class="total-amount"><?php echo number_format($data['entregado'], 2, ',') ?> €</td>
      </tr>
      <?php if ($data['total'] != ($data['entregado'] + $data['entregado_otro'])): ?>
      <tr>
        <td class="total-label">Cambio:</td>
        <td class="total-amount"><?php echo number_format(($data['entregado'] + $data['entregado_otro']) - $data['total'], 2, ',') ?> €</td>
      </tr>
      <?php endif ?>
    <?php endif ?>
  <?php endif ?>
    </table>
<?php if (!is_null($data['cliente'])): ?>
    <div class="cliente">Cliente: <?php echo $data['cliente']->nombre_apellidos ?></div>
<?php endif ?>

    <div class="iva-incluido">I.V.A. incluído</div>
    <table class="iva">
      <tr>
        <td class="iva-header iva-iva">IVA</td>
        <td class="iva-header iva-base">Base imponible ( € )</td>
        <td class="iva-header iva-cuota">Cuota ( € )</td>
      </tr>
<?php foreach ($data['ivas'] as $iva): ?>
      <tr>
        <td class="iva-iva"><?php echo $iva['iva'] ?>%</td>
        <td class="iva-base"><?php echo number_format($iva['base'], 2, ',') ?></td>
        <td class="iva-cuota"><?php echo number_format($iva['cuota_iva'], 2, ',') ?></td>
      </tr>
<?php endforeach ?>
    </table>
<?php endif ?>
<?php if ($data['tipo'] === 'regalo'): ?>
    <div class="regalo">TICKET REGALO</div>
<?php endif ?>

<?php if ($data['tipo'] === 'reserva'): ?>
<div class="reserva">RESERVA</div>
<?php endif ?>
<?php if ($data['tipo'] !== 'reserva'): ?>
    <div class="qr">
      <img src="<?php echo $data['qr'] ?>" width="80" alt="QR">
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
<?php if (!is_null($data['tbai_qr'])) : ?>
    <div class="qr">
      <label>TICKET BAI</label>
      <img src="data:image/png;base64,<?php echo $data['tbai_qr'] ?>" width="80" alt="QR">
      <span><?php echo $data['tbai_huella'] ?></span>
    </div>
<?php endif ?>
  </body>
</html>
