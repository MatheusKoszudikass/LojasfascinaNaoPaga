<?php

  include_once '../vendor/autoload.php';

  // tem que estar logado
  session_start();
  $email = $_SESSION["email"];
  if(is_null($email)){
    header("HTTP/1.1 401 UNAUTHORIZED");
    echo "É obrigatório estar logado para usar esse endpoint.";
    exit();
  }

  // só pode POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 400 BAD REQUEST");
    echo "Esse endpoint só permite POST.";
    exit();
  }

  // body deve ser válido
  $post = json_decode(file_get_contents('php://input'), true);
  if(json_last_error() != JSON_ERROR_NONE)
  {
    header("HTTP/1.1 400 BAD REQUEST");
    echo "Ocorreu um erro json_decode: " . json_last_error_msg();
    exit();
  }

  // parâmetros obrigatórios
  $codigo = $post["codigo"];
  if($codigo == null)
  {
    header("HTTP/1.1 400 BAD REQUEST");
    echo "Campos codigo é obrigatório.";
    exit();
  }

  $codigoDeBarra = new leitorClasses\CodigoDeBarra();

  try {
    $codigoDeBarra->registrarLeitura($codigo);
  }
  catch(Exception $e){
    
    if(strpos($e->getMessage(), "codigo_UNIQUE") === false){
      $errorMessage =  "Ocorreu um erro: " + $e->getMessage();
    }
    else {
      $data = $codigoDeBarra->obterDataDeLeitura($codigo);
      $errorMessage = "O código $codigo já foi cadastrado em $data";
    }

    header("HTTP/1.1 400 BAD REQUEST");
    echo $errorMessage;
    $codigoDeBarra->registrarFalhaNaLeitura($codigo, $errorMessage);
    exit();
  }

  $result["totalDoDia"] = $codigoDeBarra->TotalDoDia();
  echo json_encode($result);
