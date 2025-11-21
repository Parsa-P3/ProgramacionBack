<?php
require_once "manejadorErrores.php";
if (isset($_GET["message"])) {
    echo "El error es ".$_GET["message"];
}
 
if (isset($_GET["accion"])) {
    if ($_GET["accion"] == "inverso") {
        inverso();
    } else {
        $ruta = "/var/www/html/miarchivo.txt";
        $contenido = abrirFicheroServidor($ruta);
    }
} else {
    dividir();
}

function dividir()
{
    if (isset($_POST["num1"]) && isset($_POST["num2"])) {
        try {
            $num1 = $_POST["num1"];
            $num2 = $_POST["num2"];

            echo "Número 1: $num1 <br/>";
            echo "Número 2: $num2 <br/>";
            echo "La suma de $num1 y $num2 es " . ($num1 + $num2) . "<br/>";
            echo "La resta de $num1 y $num2 es " . ($num1 - $num2) . "<br/>";
            echo "La multiplicación de $num1 y $num2 es " . ($num1 * $num2) . "<br/>";

            // Controlar división por cero
            if ($num2 == 0) {
                throw new DivisionByZeroError("No puedes dividir por 0");
            }

            if ($num1 == 5) {
                throw new Exception("Vegeta viene a verte");
            }
            echo "La división de $num1 y $num2 es " . ($num1 / $num2) . "<br/>";
            $_POST["aaaa"] = "aa";
        } catch (DivisionByZeroError $excepcion) {
            // Redirige con mensaje de error
            header("Location: index.php?message=" . urlencode($excepcion->getMessage()));
            exit;
        } catch (Exception $excepcion) {
            // Redirige con mensaje de error
            header("Location: index.php?message=" . urlencode($excepcion->getMessage()));
            exit;
        }
    }
    //else {
    //     // Si no se envió el formulario
    //     header("Location: index.php?message=" . urlencode("Rellena el formulario"));
    //     exit;
    // }
}


function inverso()
{
    try {
        $num2 = $_POST["num2"];

        // Controlar división por cero
        if ($num2 == 0) {
            throw new DivisionByZeroError("No se puede calcular el inverso de 0");
        }

        echo "El inverso de  $num2 es " . (1 / $num2) . "<br/>";
    } catch (DivisionByZeroError $excepcion) {
        // Redirige con mensaje de error
        header("Location: index.php?message=" . urlencode($excepcion->getMessage()));
        exit;
    }
}

/**
 * Abre un fichero del servidor y devuelve su contenido.
 *
 * @param string $ruta Ruta completa del fichero en el servidor.
 * @return string|null Contenido del fichero o null si no se puede leer.
 */
function abrirFicheroServidor(string $ruta): ?string
{
    $contenido = "";

    //try {
    // Comprobamos si el fichero existe
    if (!file_exists($ruta)) {
        // error_log("El fichero no existe: " . $ruta);
        throw new Exception("El fichero no existe: " . $ruta);
        return null;
    }

    // Comprobamos si es legible
    if (!is_readable($ruta)) {
        //error_log("El fichero no tiene permisos de lectura: " . $ruta);
        throw new Exception("El fichero no tiene permisos de lectura: " . $ruta);
        return null;
    }

    // Intentamos abrir y leer el archivo
    $contenido = file_get_contents($ruta);

    if ($contenido === false) {
        throw new Exception("No se pudo leer el fichero: " . $ruta);
        //error_log("No se pudo leer el fichero: " . $ruta);
        return null;
    }
    // } catch (Exception $excepcion) {
    //     header("Location: index.php?message=" . urlencode($excepcion->getMessage()));
    //     exit;
    // }


    return $contenido;
}





// Ejemplo de uso
// $ruta = "/var/www/html/miarchivo.txt";
// $contenido = abrirFicheroServidor($ruta);

// if ($contenido !== null) {
//     echo "<pre>" . htmlspecialchars($contenido) . "</pre>";
// } else {
//     echo "No se pudo abrir el fichero.";
// }
//?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form id="frmCuentas" name="frmCuentas" action="index.php" method="post">
        <label for="num1">Número 1</label>
        <input type="number" id="num1" name="num1" /><br>
        <label for="num2">Número 2</label>
        <input type="number" id="num2" name="num2" /><br>
        <input type="submit" id="btn" name="btn" value="Enviar" />
    </form>


    <form id="frmInverso" name="frmInverso" action="index.php?accion=inverso" method="post">
        <label for="num2">Número 2</label>
        <input type="number" id="num2" name="num2" /><br>
        <input type="submit" id="btn" name="btn" value="Inverso" />
    </form>

    <form id="frmFichero" name="frmFichero" action="index.php?accion=fichero" method="post">
        <input type="submit" id="btn" name="btn" value="Enviar" />
    </form>
</body>

</html>