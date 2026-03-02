<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $a = $_POST["a"];
        $b = $_POST["b"];
        $operacao = $_POST["operacao"];

        // Verifica qual operação foi selecionada. A soma é o padrão (else).
        if ($operacao == 'subtracao') {
            $resultado = $a - $b;
        } elseif ($operacao == 'multiplicacao') {
            $resultado = $a * $b;
        } elseif ($operacao == 'divisao') {
            $resultado = $a / $b;
        } elseif ($operacao == 'potencia') {
            $resultado = $a ** $b; // 'a' elevado a 'b'
        } elseif ($operacao == 'raiz') {
            $resultado = sqrt($a); // Calcula a raiz quadrada apenas de 'a'
        } else {
            $resultado = $a + $b;
        }
    }
?>
<!DOCTYPE html>
<html>
<body>
<h1><?php echo 'Calculadora';?></h1>

<form method='POST' action=''>
    a:<input type=text name='a'><br>
    b:<input type=text name='b'><br><br>
    
    <select name='operacao'>
        <option value='soma'>Soma (+)</option>
        <option value='subtracao'>Subtração (-)</option>
        <option value='multiplicacao'>Multiplicação (*)</option>
        <option value='divisao'>Divisão (/)</option>
        <option value='potencia'>Potência (a^b)</option>
        <option value='raiz'>Raiz Quadrada (√a)</option>
    </select>
    <br><br>
    
    <input type=submit value='Calcular'>
    <br><br>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo '<hr> Resultado: ' . $resultado; 
    }
    ?>
    
</body>
</html>