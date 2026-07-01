<?php
require_once __DIR__ . '/configuracao_api.php';
exigirPost();

$usuarioId = exigirLogin();
$dados = lerJson();

$idsServicos = $dados['servicos'] ?? [];
$profissionalId = $dados['profissional_id'] ?? null;
$data = $dados['data'] ?? '';
$hora = $dados['hora'] ?? '';
$pagamento = $dados['forma_pagamento'] ?? '';

if (!is_array($idsServicos) || count($idsServicos) === 0) {
    responder(['sucesso' => false, 'mensagem' => 'Escolha pelo menos um serviço.'], 422);
}

// Mantém somente IDs numéricos e sem repetição.
$idsLimpos = [];
foreach ($idsServicos as $id) {
    $id = (int) $id;
    if ($id > 0 && !in_array($id, $idsLimpos)) {
        $idsLimpos[] = $id;
    }
}
$idsServicos = $idsLimpos;

$horarios = ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00', '18:00', '19:00'];
$pagamentos = ['credito', 'debito', 'pix', 'dinheiro'];

$dataValida = DateTime::createFromFormat('Y-m-d', $data);
if (!$dataValida || $dataValida->format('Y-m-d') !== $data || $data < date('Y-m-d')) {
    responder(['sucesso' => false, 'mensagem' => 'Escolha uma data válida.'], 422);
}

if (!in_array($hora, $horarios)) {
    responder(['sucesso' => false, 'mensagem' => 'Escolha um horário válido.'], 422);
}

if (!in_array($pagamento, $pagamentos)) {
    responder(['sucesso' => false, 'mensagem' => 'Escolha a forma de pagamento.'], 422);
}

$usuario = buscarUsuarioDaSessao($pdo);
if (!$usuario) {
    responder(['sucesso' => false, 'mensagem' => 'Usuário não encontrado.'], 401);
}

// Confere se todos os serviços enviados existem no banco.
$interrogacoes = implode(',', array_fill(0, count($idsServicos), '?'));
$consulta = $pdo->prepare(
    "SELECT id, preco FROM servicos WHERE ativo = 1 AND id IN ($interrogacoes)"
);
$consulta->execute($idsServicos);
$servicos = $consulta->fetchAll();

if (count($servicos) !== count($idsServicos)) {
    responder(['sucesso' => false, 'mensagem' => 'Um serviço escolhido não está disponível.'], 422);
}

if ($profissionalId !== null && $profissionalId !== '') {
    $profissionalId = (int) $profissionalId;

    $consulta = $pdo->prepare('SELECT id FROM profissionais WHERE id = :id AND ativo = 1');
    $consulta->execute([':id' => $profissionalId]);

    if (!$consulta->fetch()) {
        responder(['sucesso' => false, 'mensagem' => 'Profissional inválido.'], 422);
    }

    $consulta = $pdo->prepare(
        "SELECT id FROM agendamentos
         WHERE profissional_id = :profissional
           AND data_agendamento = :data
           AND hora_agendamento = :hora
           AND status <> 'Cancelado'"
    );
    $consulta->execute([
        ':profissional' => $profissionalId,
        ':data' => $data,
        ':hora' => $hora
    ]);

    if ($consulta->fetch()) {
        responder(['sucesso' => false, 'mensagem' => 'A profissional já está ocupada neste horário.'], 409);
    }
} else {
    $profissionalId = null;
}

$total = 0;
foreach ($servicos as $servico) {
    $total += $servico['preco'];
}

try {
    // A transação garante que o agendamento e seus itens sejam salvos juntos.
    $pdo->beginTransaction();

    $consulta = $pdo->prepare(
        'INSERT INTO agendamentos
         (servico_id, nome, telefone, email, data_agendamento, hora_agendamento,
          usuario_id, profissional_id, forma_pagamento, valor_total)
         VALUES
         (:servico, :nome, :telefone, :email, :data, :hora,
          :usuario, :profissional, :pagamento, :total)'
    );

    $consulta->execute([
        ':servico' => $idsServicos[0],
        ':nome' => $usuario['nome'],
        ':telefone' => $usuario['telefone'],
        ':email' => $usuario['email'],
        ':data' => $data,
        ':hora' => $hora,
        ':usuario' => $usuarioId,
        ':profissional' => $profissionalId,
        ':pagamento' => $pagamento,
        ':total' => $total
    ]);

    $agendamentoId = $pdo->lastInsertId();
    $inserirItem = $pdo->prepare(
        'INSERT INTO agendamento_servicos (agendamento_id, servico_id, preco_unitario)
         VALUES (:agendamento, :servico, :preco)'
    );

    foreach ($servicos as $servico) {
        $inserirItem->execute([
            ':agendamento' => $agendamentoId,
            ':servico' => $servico['id'],
            ':preco' => $servico['preco']
        ]);
    }

    $pdo->commit();
    responder(['sucesso' => true, 'mensagem' => 'Agendamento confirmado com sucesso.'], 201);
} catch (Exception $erro) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    responder(['sucesso' => false, 'mensagem' => 'Erro ao salvar o agendamento.'], 500);
}
