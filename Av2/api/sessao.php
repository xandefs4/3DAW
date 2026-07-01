<?php
require_once __DIR__ . '/configuracao_api.php';

responder([
    'sucesso' => true,
    'usuario' => buscarUsuarioDaSessao($pdo)
]);
