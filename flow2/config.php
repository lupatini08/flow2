<?php
// arquivo para verificar se a sessão está ativa
if (session_status() == PHP_SESSION_NONE) { // se a sessão não está ativa, ativa
    session_start();
}

// configurações do bd
$host = 'localhost'; // endereço do bd
$dbname = 'flow'; // nome do bd
$username = 'root'; // usuario do bd
$password = ''; // senha do bd

try {
    // cria uma conexao pdo com o bd
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>