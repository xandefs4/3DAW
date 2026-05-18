<?php

 // Incluir novo aLuno
 
 $msg = "";
   
if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
    $nome = $_POST["nome"];
    $matricula = $_POST["matricula"]; 
    $email = $_POST["email"];
    echo "Nome: " . $nome . " Matricula: " . $matricula . " Email: " . $email;
  
    if (!file_exists("alunos.txt")) {
       $arqAlun = fopen("alunos.txt","w") or die("erro ao criar arquivo");
       $linha = "nome;matricula;email\n";
       fwrite($arqAlun,$linha);
       fclose($arqAlun);
   }
   $arqAlun = fopen("alunos.txt","a") or die("erro ao criar arquivo");
    $linha = $nome . ";" . $matricula . ";" . $email . "\n";
    fwrite($arqAlun,$linha);
    fclose($arqAlun);
    $msg = "Deu certo!";
}
?> 
<!DOCTYPE html>
<html>
<head>
</head>
<body>   
<h1>Incluir novo aluno</h1>

<form action="include.php" method="POST" onsubmit="return validarFormulario()">

    Nome: <input type="text" id="nome" name="nome">
    <br>
    <span id="erroNome"></span>
    <br>

    Matrícula: <input type="text" id="matricula" name="matricula">
    <br>
    <span id="erroMatricula"></span>
    <br>

    Email: <input type="text" id="email" name="email">
    <br>
    <span id="erroEmail"></span>
    <br>

    <input type="submit" value="Incluir novo aluno">
</form>

<p><?php echo $msg ?></p>

<script>
    function mostrarErro(idCampo, idErro, mensagem) {
        var spanErro = document.getElementById(idErro);
        spanErro.textContent = "⚠ " + mensagem;
        spanErro.style.display = "block";
    }

    function limparErro(idCampo, idErro) {
        var spanErro = document.getElementById(idErro);
        spanErro.textContent = "";
        spanErro.style.display = "none";
    }

    document.getElementById("matricula").addEventListener("blur", function () {
        validarMatricula();
    });

    document.getElementById("nome").addEventListener("blur", function () {
        validarNome();
    });

    document.getElementById("email").addEventListener("blur", function () {
        validarEmail();
    });

    function validarMatricula() {
        var valor = document.getElementById("matricula").value.trim();

        if (valor === "") {
            mostrarErro("matricula", "erroMatricula", "A matrícula é obrigatória.");
            return false;
        }
        if (!/^\d+$/.test(valor)) {
            mostrarErro("matricula", "erroMatricula", "A matrícula deve conter apenas números.");
            return false;
        }
        if (valor.length < 4 || valor.length > 10) {
            mostrarErro("matricula", "erroMatricula", "A matrícula deve ter entre 4 e 10 dígitos.");
            return false;
        }

        limparErro("matricula", "erroMatricula");
        return true;
    }

    function validarNome() {
        var valor = document.getElementById("nome").value.trim();

        if (valor === "") {
            mostrarErro("nome", "erroNome", "O nome é obrigatório.");
            return false;
        }
        if (valor.length < 3) {
            mostrarErro("nome", "erroNome", "O nome deve ter pelo menos 3 caracteres.");
            return false;
        }
        if (valor.length > 100) {
            mostrarErro("nome", "erroNome", "O nome deve ter no máximo 100 caracteres.");
            return false;
        }
        if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(valor)) {
            mostrarErro("nome", "erroNome", "O nome deve conter apenas letras e espaços.");
            return false;
        }

        limparErro("nome", "erroNome");
        return true;
    }

    function validarEmail() {
        var valor = document.getElementById("email").value.trim();

        if (valor === "") {
            mostrarErro("email", "erroEmail", "O e-mail é obrigatório.");
            return false;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor)) {
            mostrarErro("email", "erroEmail", "Informe um e-mail válido (ex: aluno@email.com).");
            return false;
        }

        limparErro("email", "erroEmail");
        return true;
    }

    function validarFormulario() {
        var matriculaOk = validarMatricula();
        var nomeOk      = validarNome();
        var emailOk     = validarEmail();

        return matriculaOk && nomeOk && emailOk;
    }
</script>

</body>
</html>