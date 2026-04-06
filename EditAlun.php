<?php

// Alterar aluno

$msg = "";
   
if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
    $nome = $_POST["nome"];
    $matricula = $_POST["matricula"]; 
    $email = $_POST["email"];
  
    if (!file_exists("alunos.txt")) {
        $msg = "Arquivo de alunos não existe!";
    } else {
        
        $linhas = file("alunos.txt");
        $arqAlun = fopen("alunos.txt","w") or die("erro ao abrir arquivo");
        $achou = false;

        foreach ($linhas as $linha) {
            $dados = explode(";", $linha);
            
            if ($dados[0] != "nome" && isset($dados[1]) && trim($dados[1]) == $matricula) {
                
                $novaLinha = $nome . ";" . $matricula . ";" . $email . "\n";
                fwrite($arqAlun, $novaLinha);
                $achou = true;
            } else {
               
                fwrite($arqAlun, $linha);
            }
        }
        
        fclose($arqAlun);
        
        if ($achou) {
            $msg = "Aluno alterado com sucesso!";
        } else {
            $msg = "Matrícula não encontrada!";
        }
    }
}
?> 
<!DOCTYPE html>
<html>
<head>
</head>
<body>   
<h1>Alterar aluno</h1>
<form action="alterar.php" method="POST">
    Mátricula (aluno que deseja alterar): <input type="text" name="matricula" required>
    <br><br>
    Novo Nome: <input type="text" name="nome" required>
    <br><br>
    Novo Email: <input type="email" name="email" required>
    <br><br>
    <input type="submit" value="Alterar aluno">
</form>
<p><?php echo $msg ?></p>
<br>
</body>
</html>