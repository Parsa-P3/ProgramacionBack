<?php
// funciones.php

// 1. Determinar qué método de solicitud se ha utilizado (GET o POST)
$metodo = $_SERVER["REQUEST_METHOD"]; // Guarda "GET" o "POST"

// 2. RECUPERAR LA 'ACCION' de la URL (siempre es un parámetro GET, independientemente del método del formulario)
// Usamos ?? para asignar un valor por defecto si 'accion' no existe en la URL
$accion = $_GET["accion"] ?? "Acción no definida en la URL";

// 3. RECUPERAR LOS DATOS DEL FORMULARIO según el método
$datos = []; // Inicializamos la variable que contendrá todos los datos

if ($metodo === "POST") {
    // Si se usó POST, los datos del formulario están en $_POST
    $datos = $_POST;
    // La acción (que es GET) no está en $datos, pero la mostraremos por separado.
} elseif ($metodo === "GET") {
    // Si se usó GET, los datos del formulario están en $_GET
    $datos = $_GET;
    // Si el formulario usa GET, la 'accion' ya está incluida en $datos.
    // Para simplificar la visualización, usaremos $datos y la variable $accion separada.
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados del Formulario</title>
    <style>
        body { font-family: sans-serif; }
        ul { list-style-type: none; padding-left: 0; }
        strong { display: inline-block; width: 100px; }
        .metodo { color: #007bff; font-weight: bold; }
    </style>
</head>
<body>
    
    <h2>Resultados de la Solicitud (<span class="metodo"><?php echo $metodo; ?></span>)</h2>

    <h3>1. Valor de la Acción (Querystring/GET)</h3>
    <p><strong>Acción:</strong> <?php echo htmlspecialchars($accion); ?></p>
    
    <hr>

    <h3>2. Datos de los Campos del Formulario (<?php echo $metodo; ?>)</h3>

    <?php if (!empty($datos)): ?>
        <ul>
            <li><strong>Nombre:</strong> <?php echo htmlspecialchars($datos["nombre"] ?? "No enviado"); ?></li>
            <li><strong>Apellidos:</strong> <?php echo htmlspecialchars($datos["apellidos"] ?? "No enviado"); ?></li>
            <li><strong>Dirección:</strong> <?php echo htmlspecialchars($datos["direccion"] ?? "No enviado"); ?></li>
            <li><strong>Sexo:</strong> <?php echo htmlspecialchars($datos["sexo"] ?? "No enviado"); ?></li>
            
            <li><strong>Estudios:</strong> 
                <?php 
                    // Nota: Si el método es POST y 'accion' no está en $_POST, no es un problema.
                    // Si el método es GET y 'accion' está en $datos, lo ignoramos al mostrar los campos.
                    echo isset($datos["estudios"]) ? "Sí, tiene estudios de Grado Medio" : "No tiene estudios de Grado Medio"; 
                ?>
            </li>
        </ul>
    <?php else: ?>
        <p>No se recibieron datos de los campos del formulario.</p>
    <?php endif; ?>

    <br>
    <a href="index.html">Volver al formulario</a>
</body>
</html>