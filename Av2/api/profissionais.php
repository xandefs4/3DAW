<?php
require_once __DIR__ . '/configuracao_api.php';

$consulta = $pdo->query(
    'SELECT id, nome, especialidade
     FROM profissionais
     WHERE ativo = 1
     ORDER BY nome'
);

responder($consulta->fetchAll());
