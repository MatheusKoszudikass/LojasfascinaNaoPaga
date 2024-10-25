<?php

namespace leitorClasses;

class BaseClassComId extends BaseClass
{
  var $data = [];
  var $id;

  public function __construct($id)
  {
    $this->validate_id($id);
    $this->id = $id;
    parent::__construct();
  }

  private function validate_id($id)
  {
    if (!is_numeric($id)) {
      throw new \Exception("Id não encontrado ou inválido.");
    }
  }

  public function atualizar($data, $table_name, $key)
  {
    $updates = '';
    foreach ($data as $field => $value) {
      $value = $this->conn->real_escape_string($value);
      $updates .= "$field='$value', \n";
    }
    $updates = substr($updates, 0, -3);
    $sql = "UPDATE $table_name SET $updates WHERE $key = $this->id;";
    return $this->exec_sql($sql, false);
  }
}
