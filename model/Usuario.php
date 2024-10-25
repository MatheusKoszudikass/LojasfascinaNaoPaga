<?php

namespace leitorClasses;

class Usuario extends BaseClass
{

  public function validaLogin($email, $senha){
    $email = $this->conn->real_escape_string($email);
    $senha = $this->conn->real_escape_string($senha);

    $sql = "SELECT * FROM usuario WHERE email = '$email' AND senha = '$senha'";
    $rows = $this->exec_sql($sql);

    return count($rows) == 1;

  }
}
