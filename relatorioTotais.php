<?php
include_once 'vendor/autoload.php';

$codigoDeBarra = new leitorClasses\CodigoDeBarra();
$diasTotais = $codigoDeBarra->totaisPorDias();

?>
<!DOCTYPE html>
<html>

<head>
  <title>Relatório</title>
  <? include("template/head.php") ?>
</head>

<body>
  <? include("template/menu.php") ?>

  <div class="container">
  <h3>Relatório - totais</h3>

  <div class="container">
    <div class="table-responsive">
      <table id="table" class="table table-striped table-bordered table-hover">
        <thead>
          <tr style="text-align: center;">
            <th data-field="data" data-sortable="true">Data</th>
            <th data-field="total" data-sortable="true">Leituras</th>
            <th data-field="detalhes" data-sortable="false">Erros</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($diasTotais as $diaTotal) {
            $dia = $diaTotal['dia'];
            $leituras = $diaTotal['totalLeituras'];
            $erros = $diaTotal['totalErros'];;
          ?>
            <tr style="text-align: center;">
              <td><?= $dia ?></td>
              <td><a href="relatorioLeituras.php?dia=<?= $dia; ?>" role="button" title="Ver detalhes"><?= $leituras ?></a></td>
              <td>
              <a href="relatorioErros.php?dia=<?= $dia; ?>" role="button" title="Ver detalhes"><?= $erros ?></a>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
  </div>
</body>

</html>