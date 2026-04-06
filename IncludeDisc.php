<?php  

 // Incluir nova disciplina

 $msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $sigla = $_POST["sigla"];
    $nome = $_POST["nome"];
    $carga = $_POST["carga"];
    echo "Sigla: " . $sigla . " Nome: " . $nome ; " Carga horária: " . $carga;

    if (!file_exists(disciplinas.txt)){
        $arqDisc = fopen("disciplinas.txt", "w") or die("Erro ao criar arquivo");
        $linha = "sigla;nome;carga\n";
        fwrite($arqDisc, $linha);
        fclose($arqDisc);
    }

    $arqDisc = fopen("disciplinas.txt", "a") or die("Erro ao criar arquivo");

    $linha = $sigla . ";" . $nome . ";" . $carga . "\n";
    fwrite($arqDisc, $linha);
    fclose($arqDisc);
    $msg = "Deu certo";
}
?>

<!DOCTYPE htmml>
<htmml> 
    <head> 
        <body> 
        <h1> Incluir nova disciplina</h1>
        <form action="IncludeDisciplina.php" method="POST">
            Sigla: <input type="text" name="sigla" required>
            <br><br>
            Nome: <input type="text" name="nome" required>
            <br><br>    
            Carga horária (horas): <input type="text" name="carga" required>
            <input type="submit" value="Incluir nova disciplina">
        </form>
        <p> <?php echo $msg ?></p>
        </body>
    </head>
</html>
