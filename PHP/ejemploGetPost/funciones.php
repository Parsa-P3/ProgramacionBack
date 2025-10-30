<?php
// funciones.php

// 1. Determinar qué método de solicitud se ha utilizado (GET o POST)
$metodo = $_SERVER["REQUEST_METHOD"];

// 2. RECUPERAR LA 'ACCION' de la URL (siempre es un parámetro GET, independientemente del método del formulario)
// Usamos ?? para asignar un valor por defecto si 'accion' no existe en la URL
$accion = $_GET["accion"] ?? "Acción no definida en la URL";

// 3. RECUPERAR LOS DATOS DEL FORMULARIO según el método
$datos = []; // Inicializamos la variable que contendrá todos los datos

if ($metodo === "POST") {
    $datos = $_POST;
} elseif ($metodo === "GET") {
    $datos = $_GET;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultados del Formulario</title>
    <style>
        body {
            font-family: sans-serif;
        }

        strong {
            display: inline-block;
            width: 100px;
        }

        .metodo {
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h2>Resultados de la Solicitud (<span class="metodo"><?php echo $metodo; ?></span>)</h2>

    <h3>1. Valor de la Acción (Querystring/GET)</h3>
    <p><strong>Acción:</strong> <?php echo htmlspecialchars($accion); ?></p>

    <hr>

    <h3>2. Datos de los Campos del Formulario (<?php echo $metodo; ?>)</h3>


    <ul>
        <li><strong>Nombre:</strong> <?php echo htmlspecialchars($datos["nombre"] ?? "No enviado"); ?></li>
        <li><strong>Apellidos:</strong> <?php echo htmlspecialchars($datos["apellidos"] ?? "No enviado"); ?></li>
        <li><strong>Dirección:</strong> <?php echo htmlspecialchars($datos["direccion"] ?? "No enviado"); ?></li>
        <li><strong>Sexo:</strong> <?php echo htmlspecialchars($datos["sexo"] ?? "No enviado"); ?></li>

        <li><strong>Estudios:</strong>
            <?php
            echo isset($datos["estudios"]) ? "Sí, tiene estudios de Grado Medio" : "No tiene estudios de Grado Medio";
            // echo isset($_POST["estudios"]) ? "Sí, tiene estudios de Grado Medio" : "No tiene estudios de Grado Medio"; 
            ?>
        </li>
    </ul>
    <br>
    <a href="index.html">Volver al formulario</a>
</body>

</html>