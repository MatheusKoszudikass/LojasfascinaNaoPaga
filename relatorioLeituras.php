<?php
include_once 'vendor/autoload.php';

$codigoDeBarra = new leitorClasses\CodigoDeBarra();
$dia = $_GET['dia'];
$detalhes = $codigoDeBarra->leiturasDoDia($dia)
?>
<!DOCTYPE html>
<html>

<head>
  <title>Relatório - leituras</title>
  <? include("template/head.php") ?>

</head>

<body>
  <? include("template/menu.php") ?>

  <div class="container">
    <div class="table-responsive">

      <h3>Relatório - leituras</h3>

      <table id="table" class="table table-striped table-bordered table-hover">
        <thead>
          <tr style="text-align: center;">
            <th data-field="hora" data-sortable="true">Hora</th>
            <th data-field="codigo" data-sortable="true">Código de Barras</th>
          </tr>
        </thead>
        <tbody>
          <?

          if(sizeof($detalhes) == 0) {
            echo "<tr><td colspan=2 align=center>Nenhum registro encontrado.</td></tr>";
          }

          foreach ($detalhes as $detalhe) {
            $hora = $detalhe['dataDeLeitura'];
          ?>
            <tr style="text-align: center;">
              <td><?= $hora; ?></td>
              <td><?= $detalhe['codigo']; ?></td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>