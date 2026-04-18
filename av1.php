<?php

    /* O Sr. Water Falls precisa de um sistema de jogo corporativo, para treinar seus gestores em situações difíceis. O jogo deverá gerenciar situações de perguntas e respostas (decisões) encadeadas.
    O game é composto por vários desafios e cada desafio tem um objetivo específico, como por exemplo, gerenciar o andamento de um projeto, resolver um problema administrativo, contratar um novo funcionário, conceder um empréstimo e outros.
    Neste primeiro momento será desenvolvido somente o cadastro Usuários, Perguntas e Respostas.
    Criar as funcionalidades de Criar Perguntas e respostas de multipla escolha, Criar Perguntas e respostas de texto,  alterar Perguntas e suas respostas de multipla escolha, listar todas Perguntas, listar uma Pergunta e excluir Pergunta e respostas.
    Inicialmente usaremos arquivos texto(txt) para salvar os usuários.
    As funcionalidades de Perguntas e respostas devem estar disponíveis por tela.
    O código deverá ser em PHP.
    Então deverá ser criado:
    1. Criar Perguntas e respostas de multipla escolha.
    2.Criar Perguntas e respostas de texto.
    3. Alterar Perguntas e suas respostas de multipla escolha
    4. Alterar Perguntas com respostas de texto
    5. Listar Perguntas e repostas.
    6. Listar uma Pergunta.
    7. Excluir Pergunta e respostas*/

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
    $resposta_texto = isset($_POST["resposta_texto"]) ? $_POST["resposta_texto"] : "";

    // cadastro de usuarios
    if($acao == "incluir_usuario") {
        if(!file_exists("usuarios.txt")){

            $arqUsu = fopen("usuarios.txt", "w") or die("erro ao criar o arquivo");
            $linha = "id;nome;senha\n";
            fwrite($arqUsu, $linha);
            fclose($arqUsu);
        }

    $arqUsu = fopen("usuarios.txt", "a") or die("erro ao abrir o arquivo");
    $linha = $id_usuario . ";" . $nome_usuario . ";" . $senha_usuario . "\n";
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
            fclose($arqMult);
        }
 
    $arqMult = fopen("perguntas_multiplas.txt","a") or die("erro ao abrir arquivo");
    $linha = $id_pergunta . ";" . $pergunta . ";" . $opcoes . ";" . $opcao_correta . "\n";
    fwrite($arqMult,$linha);
    fclose($arqMult);
    $msg = "";
    }
    
    // Criar discursias
    if ($acao == "incluir_texto") {
        if (!file_exists("perguntas_discursivas.txt")) {
            $arqTexto = fopen("perguntas_discursivas.txt","w") or die("erro ao criar arquivo");
            $linha = "id;pergunta;resposta\n";
            fwrite($arqTexto,$linha);
            fclose($arqTexto);
        }
    $arqTexto = fopen("perguntas_discursivas.txt","a") or die("erro ao abrir arquivo");
    $linha = $id_pergunta . ";" . $pergunta . ";" . $resposta_texto . "\n";
    fwrite($arqTexto,$linha);
    fclose($arqTexto);
    $msg = "";
    }
    
    // Alterar multiplas
    if ($acao == "alterar_multipla") {
        if (!file_exists("perguntas_multiplas.txt")) {
            $msg = "Arquivo de perguntas não existe!";
        } else {
            $linhas = file("perguntas_multiplas.txt");
            $arqMult = fopen("perguntas_multiplas.txt","w") or die("erro ao abrir arquivo");
            $achou = false;

            foreach ($linhas as $linha) {
                $dados = explode(";", $linha);
                if ($dados[0] != "id" && isset($dados[0]) && trim($dados[0]) == $id_pergunta) {
                    $novaLinha = $id_pergunta . ";" . $pergunta . ";" . $opcoes . ";" . $opcao_correta . "\n";
                    fwrite($arqMult, $novaLinha);
                    $achou = true;
                } else {
                    fwrite($arqMult, $linha);
                }
            }

            fclose($arqMult);

            if ($achou) {
                $msg = "Pergunta múltipla alterada com sucesso!";
            } else {
                $msg = "ID da pergunta não encontrado!";
            }
        }
    }

    // Alteras discursivas
    if ($acao == "alterar_texto") {
        if (!file_exists("perguntas_discursivas.txt")) {
            $msg = "Arquivo de perguntas não existe!";
        } else {
            $linhas = file("perguntas_discursivas.txt");
            $arqTexto = fopen("perguntas_discursivas.txt","w") or die("erro ao abrir arquivo");
            $achou = false;

            foreach ($linhas as $linha) {
                $dados = explode(";", $linha);
                if ($dados[0] != "id" && isset($dados[0]) && trim($dados[0]) == $id_pergunta) {
                    $novaLinha = $id_pergunta . ";" . $pergunta . ";" . $resposta_texto . "\n";
                    fwrite($arqTexto, $novaLinha);
                    $achou = true;
                } else {
                    fwrite($arqTexto, $linha);
                }
            }

            fclose($arqTexto);

            if ($achou) {
                $msg = "Pergunta de texto alterada com sucesso!";
            } else {
                $msg = "ID da pergunta não encontrado!";
            }
        }
    }

    // Exluir perguntas
    if ($acao == "excluir_pergunta") {
        $achou = false;

        if (file_exists("perguntas_multiplas.txt")) {
            $linhas = file("perguntas_multiplas.txt");
            $arqMult = fopen("perguntas_multiplas.txt","w") or die("erro ao abrir arquivo");
            foreach ($linhas as $linha) {
                $dados = explode(";", $linha);
                if ($dados[0] != "id" && isset($dados[0]) && trim($dados[0]) == $id_pergunta) {
                    $achou = true;
                } else {
                    fwrite($arqMult, $linha);
                }
            }
            fclose($arqMult);
        }

        if (file_exists("perguntas_discursivas.txt")) {
            $linhas = file("perguntas_discursivas.txt");
            $arqTexto = fopen("perguntas_discursivas.txt","w") or die("erro ao abrir arquivo");
            foreach ($linhas as $linha) {
                $dados = explode(";", $linha);
                if ($dados[0] != "id" && isset($dados[0]) && trim($dados[0]) == $id_pergunta) {
                    $achou = true;
                } else {
                    fwrite($arqTexto, $linha);
                }
            }
            fclose($arqTexto);
        }

        if ($achou) {
            $msg = "Pergunta excluída com sucesso!";
        } else {
            $msg = "ID não encontrado!";
        }
    }

    
    // Listar perguntas
    if ($acao == "listar_uma") {
        $pergunta_encontrada = "";
        
        if (file_exists("perguntas_multiplas.txt")) {
            $linhas = file("perguntas_multiplas.txt");
            foreach ($linhas as $linha) {
                $dados = explode(";", $linha);
                if ($dados[0] != "id" && isset($dados[0]) && trim($dados[0]) == $id_pergunta) {
                    $pergunta_encontrada = "Múltipla Escolha -> Pergunta: " . $dados[1] . " | Opções: " . $dados[2] . " | Correta: " . $dados[3];
                }
            }
        }
        
        if (file_exists("perguntas_discursivas.txt")) {
            $linhas = file("perguntas_discursivas.txt");
            foreach ($linhas as $linha) {
                $dados = explode(";", $linha);
                if ($dados[0] != "id" && isset($dados[0]) && trim($dados[0]) == $id_pergunta) {
                    $pergunta_encontrada = "Texto -> Pergunta: " . $dados[1] . " | Resposta: " . $dados[2];
                }
            }
        }

        if ($pergunta_encontrada != "") {
            $msg = "Encontrada: " . $pergunta_encontrada;
        } else {
            $msg = "Pergunta não encontrada!";
        }
    }
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
    ID: <input type="text" name="id_usuario" required>
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
    ID da Pergunta: <input type="text" name="id_pergunta" required>
    <br><br>
    Pergunta: <input type="text" name="pergunta" required>
    <br><br>
    Resposta: <input type="text" name="resposta_texto" required>
    <br><br>
    <input type="submit" value="Criar Pergunta de Texto">
</form>
<br>
<h1>3. Alterar Pergunta Múltipla Escolha</h1>
<form action="av1.php" method="POST">
    <input type="hidden" name="acao" value="alterar_multipla">
    ID (pergunta que deseja alterar): <input type="text" name="id_pergunta" required>
    <br><br>
    Nova Pergunta: <input type="text" name="pergunta" required>
    <br><br>
    Novas Opções: <input type="text" name="opcoes" required>
    <br><br>
    Nova Opção Correta: <input type="text" name="opcao_correta" required>
    <br><br>
    <input type="submit" value="Alterar Pergunta">
</form>

<br>
<h1>4. Alterar Pergunta com respostas de texto</h1>
<form action="av1.php" method="POST">
    <input type="hidden" name="acao" value="alterar_texto">
    ID (pergunta que deseja alterar): <input type="text" name="id_pergunta" required>
    <br><br>
    Nova Pergunta: <input type="text" name="pergunta" required>
    <br><br>
    Nova Resposta: <input type="text" name="resposta_texto" required>
    <br><br>
    <input type="submit" value="Alterar Pergunta">
</form>

<br>
<h1>6. Listar uma Pergunta</h1>
<form action="av1.php" method="POST">
    <input type="hidden" name="acao" value="listar_uma">
    ID da Pergunta: <input type="text" name="id_pergunta" required>
    <br><br>
    <input type="submit" value="Buscar Pergunta">
</form>

<br>
<h1>7. Excluir Pergunta e respostas</h1>
<form action="av1.php" method="POST">
    <input type="hidden" name="acao" value="excluir_pergunta">
    ID (pergunta que deseja excluir): <input type="text" name="id_pergunta" required>
    <br><br>
    <input type="submit" value="Excluir Pergunta">
</form>

<p><?php echo $msg ?></p>
<br>

<h1>5. Listar TODAS as Perguntas e respostas</h1>
<h3>Múltipla Escolha cadastradas:</h3>
<?php
if (file_exists("perguntas_multiplas.txt")) {
    $linhas = file("perguntas_multiplas.txt");
    foreach ($linhas as $linha) {
        $dados = explode(";", $linha);
        if ($dados[0] != "id") {
            echo "ID: " . $dados[0] . " | Pergunta: " . (isset($dados[1]) ? $dados[1] : "") . " | Opções: " . (isset($dados[2]) ? $dados[2] : "") . " | Correta: " . (isset($dados[3]) ? $dados[3] : "") . "<br>";
        }
    }
}
?>

<h3>Discursivas/Texto cadastradas:</h3>
<?php
if (file_exists("perguntas_discursivas.txt")) {
    $linhas = file("perguntas_discursivas.txt");
    foreach ($linhas as $linha) {
        $dados = explode(";", $linha);
        if ($dados[0] != "id") {
            echo "ID: " . $dados[0] . " | Pergunta: " . (isset($dados[1]) ? $dados[1] : "") . " | Resposta: " . (isset($dados[2]) ? $dados[2] : "") . "<br>";
        }
    }
}
?>

</body>
</html>
