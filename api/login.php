<?php

include_once '../vendor/autoload.php';

// só pode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("HTTP/1.1 400 BAD REQUEST");
  echo "Esse endpoint só permite POST.";
  exit();
}

// body deve ser válido
$post = json_decode(file_get_contents('php://input'), true);
if (json_last_error() != JSON_ERROR_NONE) {
  header("HTTP/1.1 400 BAD REQUEST");
  echo "Ocorreu um erro json_decode: " . json_last_error_msg();
  exit();
}

// parâmetros obrigatórios
$email = $post["email"];
$senha = $post["senha"];

if ($email == null || $senha == null) {
  header("HTTP/1.1 400 BAD REQUEST");
  echo "Campos email e senha são obrigatórios.";
  exit();
}

$usuario = new leitorClasses\Usuario();
$result = $usuario->validaLogin($email, $senha);

if ($result) {
  session_start();
  $_SESSION["email"] = $email;
} else {
  @session_destroy();
}

echo json_encode($result);
