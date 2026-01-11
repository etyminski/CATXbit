<?php

// dados de conexão
global $servername;
global $username;
global $password;
global $dbname;

// configurações do banco de dados
$servername = "sql305.infinityfree.com";
$username = "if0_40868457";
$password = "kappa112153";
$dbname = "if0_40868457_catxbit";

// cria a conexao PDO
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

?>