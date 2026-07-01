<?php
require_once __DIR__ . '/configuracao_api.php';
exigirPost();

session_unset();
session_destroy();

responder(['sucesso' => true, 'mensagem' => 'Sessão encerrada.']);
