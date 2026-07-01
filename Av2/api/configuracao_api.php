<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once __DIR__ . '/../conexao.php';

// Envia uma resposta em JSON e encerra o arquivo.
function responder($dados, $status = 200)
{
    http_response_code($status);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

// Lê o JSON enviado pelo JavaScript.
function lerJson()
{
    $conteudo = file_get_contents('php://input');
    $dados = json_decode($conteudo, true);

    if (!is_array($dados)) {
        responder(['sucesso' => false, 'mensagem' => 'Dados inválidos.'], 400);
    }

    return $dados;
}

function exigirPost()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        responder(['sucesso' => false, 'mensagem' => 'Método não permitido.'], 405);
    }
}

function exigirLogin()
{
    if (empty($_SESSION['usuario_id'])) {
        responder(['sucesso' => false, 'mensagem' => 'Faça login para continuar.'], 401);
    }

    return (int) $_SESSION['usuario_id'];
}

function buscarUsuarioDaSessao($pdo)
{
    if (empty($_SESSION['usuario_id'])) {
        return null;
    }

    $consulta = $pdo->prepare(
        'SELECT id, nome, telefone, email FROM usuarios WHERE id = :id'
    );
    $consulta->execute([':id' => $_SESSION['usuario_id']]);

    return $consulta->fetch() ?: null;
}
