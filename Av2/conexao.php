<?php
$host = 'localhost';
$banco = 'vivant_beauty';
$usuario = 'root';
$senha = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $erro) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao conectar com o banco de dados.']);
    exit;
}
