<?php

session_start();
$email = $_SESSION["email"];
if(is_null($email)){
  header('Location: index.php');
  exit();
}