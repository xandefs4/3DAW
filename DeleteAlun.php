<?php

// Excluir aluno

$msg = "";
   
if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
    $matricula = $_POST["matricula"]; 
  
    if (!file_exists("alunos.txt")) {
        $msg = "Arquivo de alunos não existe!";
    } else {
        
        $linhas = file("alunos.txt");
        $arqAlun = fopen("alunos.txt","w") or die("erro ao abrir arquivo");
        $achou = false;

        foreach ($linhas as $linha) {
            $dados = explode(";", $linha);
            
            if ($dados[0] != "nome" && isset($dados[1]) && trim($dados[1]) == $matricula) {
                $achou = true;
            } else {
                fwrite($arqAlun, $linha);
            }
        }
        
        fclose($arqAlun);
        
        if ($achou) {
            $msg = "Aluno excluído com sucesso!";
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
<h1>Excluir aluno</h1>
<form action="excluir.php" method="POST">
    Mátricula (aluno que deseja excluir): <input type="text" name="matricula" required>
    <br><br>
    <input type="submit" value="Excluir aluno">
</form>
<p><?php echo $msg ?></p>
<br>
</body>
</html>