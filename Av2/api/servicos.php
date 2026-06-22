<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexao.php';

$sql = 'SELECT id, nome, categoria, duracao, preco FROM servicos ORDER BY categoria, nome';
$consulta = $pdo->query($sql);

echo json_encode($consulta->fetchAll(PDO::FETCH_ASSOC));
