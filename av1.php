<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $acao = $_POST["acao"];

    // variaveis dos usuario
    $id_usuario = isset($_POST["id_usuario"]) ? $_POST["id_usuario"] : "";
    $nome_usuario = isset($_POST["nome_usuario"]) ? $_POST["nome_usuario"] : "";
    $senha_usuario = isset($_POST["senha_usuario"]) ? $_POST["senha_usuario"] : "";

    // variaveis das pergunta
    $id_pergunta = isset($_POST["id_pergunta"]) ? $_POST["id_pergunta"] : "";
    $pergunta = isset($_POST["pergunta"]) ? $_POST["pergunta"] : "";
    $opcoes = isset($_POST["opcoes"]) ? $_POST["opcoes"] : "";
    $opcao_correta = isset($_POST["opcao_correta"]) ? $_POST["opcao_correta"] : "";
    $resposta_texto = isset($_POST["modelo_resposta_texto"]) ? $_POST["modelo_resposta_texto"] : "";

    // cadastro de usuarios
    if($acao = "incluir_usuario") {
        if(!file_exists("usuarios.txt")){

            $arqUsu = fopen("usuarios.txt", "w") or die("erro ao criar o arquivo");
            $linha = "id;nome;senha\n";
            fwrite($arqUsu, $linha);
            fclose($arqUsu);
        }

        $arqUsu = fopen("usuarios.txt", "a") or die("erro ao abrir o arquivo");
        $linha = "id;nome;senha\n";
        fwrite($arqUsu, $linha);
        fclose($arqUsu);
        $msg = "";

    }

    // Criar multipla escolha
    if ($acao == "incluir_multipla") {
        if (!file_exists("perguntas_multiplas.txt")) {
            $arqMult = fopen("perguntas_multiplas.txt","w") or die("erro ao criar arquivo");
            $linha = "id;pergunta;opces;opcao_correta\n";
            fwrite($arqMult,$linha);
        }
        $arqMult = fopen("perguntas_multiplas.txt","a") or die("erro ao abrir arquivo");
        $linha = $id_pergunta . ";" . $pergunta . ";" . $opcoes . ";" . $opcao_correta . "\n";
        fwrite($arqMult,$linha);
        fclose($arqMult);
        $msg = "";
    }
    
    // Criar discursivas
    
    
    // Alterar multiplas
    
    
    // Alteras discursivas
    
    
    // Exluir perguntas
    
    
    // Listar perguntas
    
    
}
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>

<h1>Cadastrar Usuario</h1>
<form action="av1.php" method="POST">
    <input type="hidden" name="acao" value="incluir_usuario">
    ID: <input type="text" name="id_usurio" required>
    <br><br>
    Nome: <input type="text" name="nome_usuario" required>
    <br><br>
    Senha: <input type="password" name="senha_usuario" required>
    <br><br>
    <input type="submit" value="Incluir novo usuario">
</form>

<br>
<h1>1. Criar perguntas multipla escolha</h1>
<form action="av1.php" method="POST">
    <input type="hidden" name="acao" value="incluir_multipla">
    ID da Pergunta: <input type="text" name="id_pergunta" required>
    <br><br>
    Pergunta: <input type="text" name="pergunta" required>
    <br><br>
    Opções (ex: a-1, b-2, c-3): <input type="text" name="opcoes" required>
    <br><br>
    Opção Correta: <input type="text" name="opcao_correta" required>
    <br><br>
    <input type="submit" value="Criar Pergunta Múltipla">
</form>

<br>
<h1>2. Criar perguntas de texto</h1>
<form action="av1.php" method="POST">
    <input type="hidden" name="acao" value="incluir_texto">
    if da Pergunta: <input type="text" name="id_pergunta" required>
    <br><br>
    Pergunta: <input type="text" name="pergunta" required>
    <br><br>
    Resposta: <input type="text" name="resposta_texto" required>
    <br><br>
    <input type="submit" value="Criar Pergunta de Texto">
</form>