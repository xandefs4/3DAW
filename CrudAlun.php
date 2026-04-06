<?php

// CRUD alunos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $acao = $_POST["acao"];
    $nome = isset($_POST["nome"]) ? $_POST["nome"] : "";
    $matricula = $_POST["matricula"];
    $email = isset($_POST["email"]) ? $_POST["email"] : "";

    // Incluir
    if ($acao == "incluir") {
        if (!file_exists("alunos.txt")) {
            $arqAlun = fopen("alunos.txt","w") or die("erro ao criar arquivo");
            $linha = "nome;matricula;email\n";
            fwrite($arqAlun,$linha);
            fclose($arqAlun);
        }
        $arqAlun = fopen("alunos.txt","a") or die("erro ao abrir arquivo");
        $linha = $nome . ";" . $matricula . ";" . $email . "\n";
        fwrite($arqAlun,$linha);
        fclose($arqAlun);
        $msg = "Aluno incluído com sucesso!";
    }

    
}

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<h1>Incluir novo aluno</h1>
<form action="crud.php" method="POST">
    <input type="hidden" name="acao" value="incluir">
    Nome: <input type="text" name="nome" required>
    <br><br>
    Mátricula: <input type="text" name="matricula" required>
    <br><br>
    Email: <input type="email" name="email" required>
    <br><br>
    <input type="submit" value="Incluir novo aluno">
</form>

<br>
<h1>Alterar aluno</h1>
<form action="crud.php" method="POST">
    <input type="hidden" name="acao" value="alterar">
    Mátricula (aluno que deseja alterar): <input type="text" name="matricula" required>
    <br><br>
    Novo Nome: <input type="text" name="nome" required>
    <br><br>
    Novo Email: <input type="email" name="email" required>
    <br><br>
    <input type="submit" value="Alterar aluno">
</form>

<br>
<h1>Excluir aluno</h1>
<form action="crud.php" method="POST">
    <input type="hidden" name="acao" value="excluir">
    Mátricula (aluno que deseja excluir): <input type="text" name="matricula" required>
    <br><br>
    <input type="submit" value="Excluir aluno">
</form>

<p><?php echo $msg ?></p>
<br>
</body>
</html>