<?php

namespace leitorClasses;

include_once $_SERVER['DOCUMENT_ROOT'] . '/env.php';

class BaseClass
{
  var $conn;

  public function __construct()
  {
    $this->conn = mysqli_connect(SERVIDOR, USUARIO, SENHA, DBNAME, PORT);
    mysqli_set_charset($this->conn, "utf8");
  }

  public function exec_sql($sql, $hasResults = true, $throwError = false)
  {
    $rs = mysqli_query($this->conn, $sql);
    $rows = [];

    if (!$rs) {
      if ($throwError) {
        throw new \Exception("Ocorreu um erro: " . mysqli_errno($this->conn) . " - " . mysqli_error($this->conn));
      }
      $this->sql_error($sql);
    }

    if (!$hasResults) {
      return true;
    }

    while ($row = mysqli_fetch_assoc($rs)) {
      array_push($rows, $row);
    }

    return $rows;
  }

  public function inserir($data, $table_name)
  {
    $fields = '';
    $values = '';
    foreach ($data as $field => $value) {
      $fields .= $field . ",\n";
      $value = $this->conn->real_escape_string($value);
      $values .= '"' . $value . '"' . ",\n";
    }
    $fields = substr($fields, 0, -2);
    $values = substr($values, 0, -2);

    $sql = "INSERT INTO $table_name
    (
      $fields
    )
    VALUES
    (
      $values
    )";
    return $this->exec_sql($sql, false, true);
  }

  public function sql_error($sql)
  {
    echo "<h1>SQL Error</h1>";
    echo "<PRE>";
    echo $sql;
    echo "</PRE><HR>";
    echo ("Ocorreu um erro: " . mysqli_errno($this->conn) . " - " . mysqli_error($this->conn));
    exit();
  }
}
