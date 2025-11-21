<?php 
if (isset($_POST["num1"]) && isset($_POST["num2"])) {
    try {
    $num1 = $_POST["num1"];
    $num2 = $_POST["num2"];

    echo "Número 1: $num1 <br/>";
    echo "Número 2: $num2 <br/>";

    echo "La suma de $num1 y $num2 es " . ($num1 + $num2) . "<br/>";
    echo "La resta de $num1 y $num2 es " . ($num1 - $num2) . "<br/>";
    echo "La multiplicación de $num1 y $num2 es " . ($num1 * $num2) . "<br/>";

    if ($num2 == 0) {
        throw new DividirByZeroError("Que va tonto !");
    } else {
        echo "No se puede dividir entre 0.<br/>";
    }
} catch (Exception $e) {
    header("Location : funciones.php?message=" . urldecode($e->getMessage()));
}

} else {
    header("location:ejemplo12.php");
}
?>