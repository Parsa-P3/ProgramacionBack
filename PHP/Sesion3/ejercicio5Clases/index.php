<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coche</title>
</head>
<body>
    <?php
    require_once("coche.php");
    require_once("rectangulo2.php");
    require_once("cuentaBancaria.php");

    $coche1 = new Coche("Nissan " , "Qashqai" , "Blanco");
    $coche1 -> mostrarInfo();

    $rectangulo1 = new rectangulo2(2 , 4);
    $rectangulo1 -> mostrarInfo();
    
    $cuenta1 = new cuentaBancaria("Parsa" , 0.00);
    echo "<br> <br>";
    echo "*** INFORMACION DE CUENTA BANCARIA ***";
    $cuenta1 -> mostrarInfo();
    echo "<br> <br>";
    echo "- Paso 1 <br>";
    echo "- Depositar 10$ - " ;
    $cuenta1 -> depositar(10.00);
    $cuenta1 -> mostrarInfo();
    echo "<br> <br>";
    echo "- Paso 2 <br>";
    echo "- Retirar 11 -";
    $cuenta1 -> retirar(11);
    $cuenta1 -> mostrarInfo();
    echo "<br> <br>";
    echo "- Paso 3 <br>";
    echo "- Retirar 10 -";
    $cuenta1 -> retirar(10);
    $cuenta1 -> mostrarInfo();


    ?>


</body>
</html>