<?php

// CRUD alunos

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