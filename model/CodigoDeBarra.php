<?php

namespace leitorClasses;

class CodigoDeBarra extends BaseClass
{
  public function totaisPorDias()
  {
    // TODO: esse método não vai escalar muito bem quando a tabela ficar grande.
    // vamos precisar criar índice e limitar a data no futuro.
    $sql = 'select dia, count(*) as total from
    ( SELECT DATE_FORMAT(dataDeLeitura, "%Y-%m-%d") as dia from codigoDeBarra ) subQuery
    group by dia
    order by dia desc';
    $leituras = $this->exec_sql($sql);

    $sql = 'select dia, count(*) as total from
    ( SELECT DATE_FORMAT(dataDeLeitura, "%Y-%m-%d") as dia from erro ) subQuery
    group by dia
    order by dia desc';
    $erros = $this->exec_sql($sql);

    $totais = [];
    foreach ($leituras as $leitura) {
      $dia = $leitura['dia'];
      $total = $leitura['total'];

      $totais[$dia]["dia"] = $dia;
      $totais[$dia]["totalLeituras"] = (int) $total;
      $totais[$dia]["totalErros"] = 0;
    }

    foreach ($erros as $erro) {
      $dia = $erro['dia'];
      $total = $erro['total'];

      $totais[$dia]["totalErros"] = (int) $total;
    }

    return $totais;
  }

  public function leiturasDoDia($dia)
  {
    $dataInicio = date("$dia 00:00");
    $dataFim = date("$dia 23:59");
    
    $sql = "select * from codigoDeBarra
            where 
              dataDeLeitura >= '$dataInicio' and 
              dataDeLeitura <= '$dataFim'
              order by dataDeLeitura desc";

    return $this->exec_sql($sql);
  }

  public function errosDoDia($dia)
  {
    $dataInicio = date("$dia 00:00");
    $dataFim = date("$dia 23:59");
    
    $sql = "select * from erro
            where 
              dataDeLeitura >= '$dataInicio' and 
              dataDeLeitura <= '$dataFim'
            order by dataDeLeitura desc";

    return $this->exec_sql($sql);
  }

  public function registrarLeitura($codigo)
  {
    $data["codigo"] = $codigo;
    $data["dataDeLeitura"] = date("Y-m-d H:i:s");
    return $this->inserir($data, "codigoDeBarra");
  }

  public function registrarFalhaNaLeitura($codigo, $detalhe)
  {
    $data["codigo"] = $codigo;
    $data["dataDeLeitura"] = date("Y-m-d H:i:s");
    $data["detalhe"] = $detalhe;
    return $this->inserir($data, "erro");
  }

  public function TotalDoDia()
  {
    $dataInicio = date("Y-m-d 00:00");
    $dataFim = date("Y-m-d 23:59");

    $sql = "select count(*) as total from codigoDeBarra 
    where 
      dataDeLeitura >= '$dataInicio' and 
        dataDeLeitura <= '$dataFim'";

    $rows = $this->exec_sql($sql);
    return $rows[0]["total"];
  }

  public function obterDataDeLeitura($codigo)
  {
    $sql = "select dataDeLeitura from codigoDeBarra where codigo = '$codigo'";
    $rows = $this->exec_sql($sql);
    $data = $rows[0]["dataDeLeitura"];

    $dataObj = new \DateTime($data);
    return $dataObj->format('d/m/Y H:i:s');
  }
}
