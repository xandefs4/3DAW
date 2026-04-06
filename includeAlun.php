<?php

// Incluir novo aluno

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
<form action="include.php" method="POST">
    Nome: <input type="text" name="nome" required>
    <br><br>
    Mátricula: <input type="text" name="matricula" required>
    <br><br>
    Email: <input type="emai" name="email" required>
    <br><br>
    <input type="submit" value="Incluir novo aluno">
</form>
<p><?php echo $msg ?></p>
<br>
</body>
</html>