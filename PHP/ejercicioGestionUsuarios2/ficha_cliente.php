<?php

//Me traigo el fichero que tiene todas las librerias básicas del proyecto
require_once "utils.php";

// Validar que existe sesión activa
if (!validarSesionActiva()) {
    exit();
}

//Incluyo mi clases necesarias
require_once "./models/Clientes.php";

//Me instancio mi clase de cliente
$cli = new Cliente();

// Obtenemos parámetros del query string
$cliente_id = (int)($_GET['cliente_id'] ?? 0);
$error = $_GET['error'] ?? '';
$list = $_GET['listado'] ?? false;

// Variables para el formulario
$nombre = "";
$cif = "";
$email = "";
$telefono = "";
$apellidos = "";
$edad = 0;
$nuevo = false;

// Verificamos si es nuevo o edición
if ($cliente_id == 0) {
    $nuevo = true;
} else {
    $nuevo = false;
}

// Si hay error, obtenemos los datos del GET para repoblar el formulario
if ($error != "") {
    $nombre = $_GET['nombre'] ?? '';
    $cif = $_GET['cif'] ?? '';
    $email = $_GET['email'] ?? '';
    $telefono = $_GET['telefono'] ?? '';
    $apellidos = $_GET['apellidos'] ?? '';
    $edad = (int)($_GET['edad'] ?? 0);
    echo "<script>alert('" . str_replace("--", "\\n", $error) . "')</script>";
} else if ($cliente_id != 0) {
    // Si no hay error y tenemos ID, cargamos los datos del cliente
    $cliente_obj = $cli->obtenerPorId($pdo, $cliente_id);
    if ($cliente_obj) {
        $nombre = $cliente_obj->getNombre();
        $cif = $cliente_obj->getCIF();
        $email = $cliente_obj->getEmail();
        $telefono = $cliente_obj->getTelefono();
        $apellidos = $cliente_obj->getApellidos();
        $edad = $cliente_obj->getEdad();
    }
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <?php if ($nuevo): ?>
    <title>Nuevo Cliente</title>
    <?php else: ?>
    <title>Modificar Cliente</title>
    <?php endif; ?>
    <link rel="stylesheet" href="./estilos/estilos.css">
    <script src="./scripts/scripts.js"></script>
</head>

<body>
    <div class="container">
        <?php if ($nuevo): ?>
        <h1>Nuevo Cliente</h1>
        <?php else: ?>
        <h1>Modificar Cliente</h1>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" id="cliente_id" name="cliente_id"
                value="<?= $cliente_id ?>">
            <div>
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre" required
                    value="<?= $nombre ?>">
            </div>

            <div>
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos" required
                    value="<?= $apellidos ?>">
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required
                    value="<?= $email ?>">
            </div>

            <div>
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" placeholder="+34 123456789" required
                    value="<?= $telefono ?>">
            </div>

            <div>
                <label for="cif">CIF/NIF</label>
                <input type="text" id="cif" name="cif" placeholder="12345678A" required
                    value="<?= $cif ?>">
            </div>

            <div>
                <label for="edad">Edad</label>
                <input type="number" id="edad" name="edad" placeholder="Edad" required min="18"
                    value="<?= $edad ?>">
            </div>

            <?php if ($nuevo): ?>
            <button
                onclick="anadirCliente(<?= ($list == 'true' ? 'true' : '') ?>)">Crear
                Cliente</button>
            <?php else: ?>
            <button
                onclick="modificarCliente(<?= ($list == 'true' ? 'true' : '') ?>)">Modificar
                Cliente</button>
            <?php endif; ?>

        </form>
    </div>
</body>

</html>
