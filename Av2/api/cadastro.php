<?php
require_once __DIR__ . '/configuracao_api.php';
exigirPost();

$dados = lerJson();
$nome = trim($dados['nome'] ?? '');
$telefone = trim($dados['telefone'] ?? '');
$email = strtolower(trim($dados['email'] ?? ''));
$senha = $dados['senha'] ?? '';

if (strlen($nome) < 3 || $telefone === '') {
    responder(['sucesso' => false, 'mensagem' => 'Preencha nome e telefone.'], 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    responder(['sucesso' => false, 'mensagem' => 'Informe um e-mail válido.'], 422);
}

if (strlen($senha) < 6) {
    responder(['sucesso' => false, 'mensagem' => 'A senha deve ter pelo menos 6 caracteres.'], 422);
}

$consulta = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email');
$consulta->execute([':email' => $email]);

if ($consulta->fetch()) {
    responder(['sucesso' => false, 'mensagem' => 'Este e-mail já está cadastrado.'], 409);
}

$consulta = $pdo->prepare(
    'INSERT INTO usuarios (nome, telefone, email, senha_hash)
     VALUES (:nome, :telefone, :email, :senha)'
);
$consulta->execute([
    ':nome' => $nome,
    ':telefone' => $telefone,
    ':email' => $email,
    ':senha' => password_hash($senha, PASSWORD_DEFAULT)
]);

$_SESSION['usuario_id'] = $pdo->lastInsertId();

responder([
    'sucesso' => true,
    'usuario' => [
        'id' => $_SESSION['usuario_id'],
        'nome' => $nome,
        'telefone' => $telefone,
        'email' => $email
    ]
], 201);
