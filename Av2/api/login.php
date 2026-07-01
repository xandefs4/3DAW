<?php
require_once __DIR__ . '/configuracao_api.php';
exigirPost();

$dados = lerJson();
$email = strtolower(trim($dados['email'] ?? ''));
$senha = $dados['senha'] ?? '';

if ($email === '' || $senha === '') {
    responder(['sucesso' => false, 'mensagem' => 'Preencha e-mail e senha.'], 422);
}

$consulta = $pdo->prepare(
    'SELECT id, nome, telefone, email, senha_hash
     FROM usuarios
     WHERE email = :email'
);
$consulta->execute([':email' => $email]);
$usuario = $consulta->fetch();

if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
    responder(['sucesso' => false, 'mensagem' => 'E-mail ou senha incorretos.'], 401);
}

$_SESSION['usuario_id'] = $usuario['id'];
unset($usuario['senha_hash']);

responder(['sucesso' => true, 'usuario' => $usuario]);
