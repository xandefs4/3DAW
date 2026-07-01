<?php
require_once __DIR__ . '/configuracao_api.php';

$usuarioId = exigirLogin();

$sql = "SELECT
            a.data_agendamento,
            a.hora_agendamento,
            a.forma_pagamento,
            a.valor_total,
            a.status,
            p.nome AS profissional,
            GROUP_CONCAT(s.nome SEPARATOR ', ') AS servicos
        FROM agendamentos a
        LEFT JOIN profissionais p ON p.id = a.profissional_id
        INNER JOIN agendamento_servicos itens ON itens.agendamento_id = a.id
        INNER JOIN servicos s ON s.id = itens.servico_id
        WHERE a.usuario_id = :usuario_id
        GROUP BY a.id, a.data_agendamento, a.hora_agendamento,
                 a.forma_pagamento, a.valor_total, a.status, p.nome
        ORDER BY a.data_agendamento DESC, a.hora_agendamento DESC";

$consulta = $pdo->prepare($sql);
$consulta->execute([':usuario_id' => $usuarioId]);

responder([
    'sucesso' => true,
    'agendamentos' => $consulta->fetchAll()
]);
