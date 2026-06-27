<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

$obrigatorios = ['servico_id', 'nome', 'telefone', 'email', 'data', 'hora'];
foreach ($obrigatorios as $campo) {
    if (empty($dados[$campo])) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios.']);
        exit;
    }
}

if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Email inválido.']);
    exit;
}

$sql = 'INSERT INTO agendamentos (servico_id, nome, telefone, email, data_agendamento, hora_agendamento)
        VALUES (:servico_id, :nome, :telefone, :email, :data_agendamento, :hora_agendamento)';

$consulta = $pdo->prepare($sql);
$consulta->execute([
    ':servico_id' => $dados['servico_id'],
    ':nome' => trim($dados['nome']),
    ':telefone' => trim($dados['telefone']),
    ':email' => trim($dados['email']),
    ':data_agendamento' => $dados['data'],
    ':hora_agendamento' => $dados['hora']
]);

echo json_encode(['sucesso' => true, 'mensagem' => 'Agendamento salvo com sucesso.']);
