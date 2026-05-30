<?php

    /* O Sr. Water Falls precisa de um sistema de jogo corporativo, para treinar seus gestores em situações difíceis. O jogo deverá gerenciar situações de perguntas e respostas (decisões) encadeadas.
    O game é composto por vários desafios e cada desafio tem um objetivo específico, como por exemplo, gerenciar o andamento de um projeto, resolver um problema administrativo, contratar um novo funcionário, conceder um empréstimo e outros.
    Neste primeiro momento será desenvolvido somente o cadastro Usuários, Perguntas e Respostas.
    Criar as funcionalidades de Criar Perguntas e respostas de multipla escolha, Criar Perguntas e respostas de texto,  alterar Perguntas e suas respostas de multipla escolha, listar todas Perguntas, listar uma Pergunta e excluir Pergunta e respostas.
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

$msg = ""; 
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $conteudo = file_get_contents("php://input");
    $dadosRecebidos = json_decode($conteudo, true);

    // Se não vier JSON, usa POST normalmente.
    if (!is_array($dadosRecebidos)) {
        $dadosRecebidos = $_POST;
    }

    $acao = isset($dadosRecebidos["acao"]) ? $dadosRecebidos["acao"] : "";

    // variaveis dos usuario
    $id_usuario = isset($dadosRecebidos["id_usuario"]) ? $dadosRecebidos["id_usuario"] : "";
    $nome_usuario = isset($dadosRecebidos["nome_usuario"]) ? $dadosRecebidos["nome_usuario"] : "";
    $senha_usuario = isset($dadosRecebidos["senha_usuario"]) ? $dadosRecebidos["senha_usuario"] : "";

    // variaveis das pergunta
    $id_pergunta = isset($dadosRecebidos["id_pergunta"]) ? $dadosRecebidos["id_pergunta"] : "";
    $pergunta = isset($dadosRecebidos["pergunta"]) ? $dadosRecebidos["pergunta"] : "";
    $opcoes = isset($dadosRecebidos["opcoes"]) ? $dadosRecebidos["opcoes"] : "";
    $opcao_correta = isset($dadosRecebidos["opcao_correta"]) ? $dadosRecebidos["opcao_correta"] : "";
    $resposta_texto = isset($dadosRecebidos["resposta_texto"]) ? $dadosRecebidos["resposta_texto"] : "";

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
    $msg = "Usuário incluído com sucesso!";
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
    $msg = "Pergunta múltipla criada com sucesso!";
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
    $msg = "Pergunta de texto criada com sucesso!";
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

    // Se a requisição veio, responde em JSON.
    if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["msg" => $msg]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>

<h1>Cadastrar Usuario</h1>
<form id="formIncluirUsuario">
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
<form id="formIncluirMultipla">
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
<form id="formIncluirTexto">
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
<form id="formAlterarMultipla">
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
<form id="formAlterarTexto">
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
<form id="formListarUma">
    <input type="hidden" name="acao" value="listar_uma">
    ID da Pergunta: <input type="text" name="id_pergunta" required>
    <br><br>
    <input type="submit" value="Buscar Pergunta">
</form>

<br>
<h1>7. Excluir Pergunta e respostas</h1>
<form id="formExcluirPergunta">
    <input type="hidden" name="acao" value="excluir_pergunta">
    ID (pergunta que deseja excluir): <input type="text" name="id_pergunta" required>
    <br><br>
    <input type="submit" value="Excluir Pergunta">
</form>

<p id="msg"><?php echo $msg ?></p>
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

<script>
    // Função para enviar uma requisição
    function enviarRequisicao(url, metodo, dados) {
        return fetch(url, {
            method: metodo,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dados)
        })
        .then(response => response.json())
        .catch(error => console.error('Erro:', error));
    }

    // Função para exibir mensagem de retorno na tela
    function exibirMsg(texto) {
        document.getElementById('msg').innerText = texto;
    }

    // Função para incluir usuario
    function incluirUsuario(event) {
        event.preventDefault();

        const form = document.getElementById('formIncluirUsuario');
        const id_usuario = form.elements['id_usuario'].value;
        const nome_usuario = form.elements['nome_usuario'].value;
        const senha_usuario = form.elements['senha_usuario'].value;

        const dados = {
            acao: 'incluir_usuario',
            id_usuario: id_usuario,
            nome_usuario: nome_usuario,
            senha_usuario: senha_usuario
        };

        enviarRequisicao('av1.php', 'POST', dados)
            .then(response => console.log(response));
    }

    // Função para criar pergunta multipla escolha
    function incluirMultipla(event) {
        event.preventDefault();

        const form = document.getElementById('formIncluirMultipla');
        const id_pergunta = form.elements['id_pergunta'].value;
        const pergunta = form.elements['pergunta'].value;
        const opcoes = form.elements['opcoes'].value;
        const opcao_correta = form.elements['opcao_correta'].value;

        const dados = {
            acao: 'incluir_multipla',
            id_pergunta: id_pergunta,
            pergunta: pergunta,
            opcoes: opcoes,
            opcao_correta: opcao_correta
        };

        enviarRequisicao('av1.php', 'POST', dados)
            .then(response => console.log(response));
    }

    // Função para criar pergunta de texto
    function incluirTexto(event) {
        event.preventDefault();

        const form = document.getElementById('formIncluirTexto');
        const id_pergunta = form.elements['id_pergunta'].value;
        const pergunta = form.elements['pergunta'].value;
        const resposta_texto = form.elements['resposta_texto'].value;

        const dados = {
            acao: 'incluir_texto',
            id_pergunta: id_pergunta,
            pergunta: pergunta,
            resposta_texto: resposta_texto
        };

        enviarRequisicao('av1.php', 'POST', dados)
            .then(response => console.log(response));
    }

    // Função para alterar pergunta multipla escolha
    function alterarMultipla(event) {
        event.preventDefault();

        const form = document.getElementById('formAlterarMultipla');
        const id_pergunta = form.elements['id_pergunta'].value;
        const pergunta = form.elements['pergunta'].value;
        const opcoes = form.elements['opcoes'].value;
        const opcao_correta = form.elements['opcao_correta'].value;

        const dados = {
            acao: 'alterar_multipla',
            id_pergunta: id_pergunta,
            pergunta: pergunta,
            opcoes: opcoes,
            opcao_correta: opcao_correta
        };

        enviarRequisicao('av1.php', 'POST', dados)
            .then(response => exibirMsg(response.msg));
    }

    // Função para alterar pergunta de texto
    function alterarTexto(event) {
        event.preventDefault();

        const form = document.getElementById('formAlterarTexto');
        const id_pergunta = form.elements['id_pergunta'].value;
        const pergunta = form.elements['pergunta'].value;
        const resposta_texto = form.elements['resposta_texto'].value;

        const dados = {
            acao: 'alterar_texto',
            id_pergunta: id_pergunta,
            pergunta: pergunta,
            resposta_texto: resposta_texto
        };

        enviarRequisicao('av1.php', 'POST', dados)
            .then(response => exibirMsg(response.msg));
    }

    // Função para listar uma pergunta especifica
    function listarUma(event) {
        event.preventDefault();

        const form = document.getElementById('formListarUma');
        const id_pergunta = form.elements['id_pergunta'].value;

        const dados = {
            acao: 'listar_uma',
            id_pergunta: id_pergunta
        };

        enviarRequisicao('av1.php', 'POST', dados)
            .then(response => exibirMsg(response.msg));
    }

    // Função para excluir pergunta e respostas
    function excluirPergunta(event) {
        event.preventDefault();

        const form = document.getElementById('formExcluirPergunta');
        const id_pergunta = form.elements['id_pergunta'].value;

        const dados = {
            acao: 'excluir_pergunta',
            id_pergunta: id_pergunta
        };

        enviarRequisicao('av1.php', 'POST', dados)
            .then(response => exibirMsg(response.msg));
    }

    // requisições
    document.getElementById('formIncluirUsuario').addEventListener('submit', incluirUsuario);
    document.getElementById('formIncluirMultipla').addEventListener('submit', incluirMultipla);
    document.getElementById('formIncluirTexto').addEventListener('submit', incluirTexto);
    document.getElementById('formAlterarMultipla').addEventListener('submit', alterarMultipla);
    document.getElementById('formAlterarTexto').addEventListener('submit', alterarTexto);
    document.getElementById('formListarUma').addEventListener('submit', listarUma);
    document.getElementById('formExcluirPergunta').addEventListener('submit', excluirPergunta);
</script>

</body>
</html>
