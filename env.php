<?php

// PROD
const SERVIDOR = "localhost";
const USUARIO = "lojas164_leitor";
const SENHA = "Vd6jprmaEnDWcSu";
const DBNAME = "lojas164_leitorcb";
const PORT = 3306;

// DEV
// const SERVIDOR = "localhost";
// const USUARIO = "leitorcbraffaser";
// const SENHA = "n&q\$ESBz7gU\$";
// const DBNAME = "leitorcb_dev";
// const PORT = 3306;

// LOCAL
// const SERVIDOR = "mysql";
// const USUARIO = "root";
// const SENHA = "123";
// const DBNAME = "leitor_db";
// const PORT = 3306;

// E_ERROR - mostra apenas erros graves.
// E_ALL   - mostra tudo, inclusive warnings.
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('America/Sao_Paulo');

// var_dump com mais detalhes.
ini_set("xdebug.var_display_max_children", '-1');
ini_set("xdebug.var_display_max_data", '-1');
ini_set("xdebug.var_display_max_depth", '-1');
