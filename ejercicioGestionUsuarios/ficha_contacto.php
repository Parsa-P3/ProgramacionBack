<?php
//Me traigo el fichero que tiene todas las librerias básicas del proyecto
require_once "utils.php";

//Incluyo mi clases necesarias
require_once "./models/Contacto.php";
require_once "./models/Clientes.php";

// Compruebo la sesión
if (!comprobarSesion()) {
    return;
}

// **Control de Acceso Admin**
$usu_conectado = $_SESSION["usuario"];
if ($usu_conectado->getRolId() != 1) {
    header('Location: listado_clientes.php'); // Redirige si no es admin
    return;
}

$cont = new Contacto();
$cli = new Cliente();

// Me traigo el query string del id del contacto
$contacto_id = $_GET['contacto_id'] ?? '0';

// Me traigo el query string del id del cliente (siempre debe existir)
$cliente_id_form = $_GET['cliente_id'] ?? '0';

// Me traigo el query string del error si lo hubiera
$error = $_GET['error'] ?? '';

$nuevo = false;

if (isset($contacto_id) == true && $contacto_id != 0) {
    $nuevo = false;
} else {
    $nuevo = true;
}

// Declaro las variables del formulario
$nombre = "";
$apellidos = "";
$email = "";
$telefono = "";
$nombre_cliente = "Cliente Desconocido";

// Si es un contacto existente, cargo sus datos (Pinta los datos del contacto en los campos)
if (!$nuevo) {
    $cont = $cont->obtenerPorId($pdo, (int)$contacto_id);
    $nombre = $cont->getNombre();
    $apellidos = $cont->getApellidos();
    $email = $cont->getEmail();
    $telefono = $cont->getTelefono();
    $cliente_id_form = $cont->getClienteId(); // Aseguro que el cliente_id sea el del contacto
}

// Si tengo error (ej. al validar), cargo los datos del POST que vienen en el GET
if ($error != "") {
    $nombre = $_GET['nombre'] ?? '';
    $apellidos = $_GET['apellidos'] ?? '';
    $email = $_GET['email'] ?? '';
    $telefono = $_GET['telefono'] ?? '';
    $contacto_id = $_GET['contacto_id'] ?? '0';
    $cliente_id_form = $_GET['cliente_id'] ?? '0';

    echo "<script>alert('" . str_replace("--", "\\n", $error) . "')</script>";
}

// Obtengo el nombre del cliente
if ($cliente_id_form != 0) {
    $cliente = $cli->obtenerPorId($pdo, $cliente_id_form);
    $nombre_cliente = $cliente->getNombre() . " " . $cliente->getApellidos();
} else {
     // Si no hay cliente_id, no se puede crear/modificar un contacto
    header('Location: listado_clientes.php?error=Debe seleccionar un cliente para gestionar sus contactos');
    return;
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?= $nuevo ? 'Añadir' : 'Modificar' ?> Contacto</title>
    <link rel="stylesheet" href="./estilos/estilos.css">
    <script src="./scripts/scripts.js"></script>
</head>

<body>
    <div class="container">
        <h1><?= $nuevo ? 'Añadir' : 'Modificar' ?> Contacto (Cliente: <?= htmlspecialchars($nombre_cliente) ?>)</h1>

        <form method="post">
            <input type="hidden" id="contacto_id" name="contacto_id" value="<?= $contacto_id ?>">
            <input type="hidden" id="cliente_id" name="cliente_id" value="<?= $cliente_id_form ?>">

            <div>
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre del contacto" required
                    value="<?= htmlspecialchars($nombre) ?>">
            </div>

            <div>
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos del contacto"
                    value="<?= htmlspecialchars($apellidos) ?>">
            </div>

            <div>
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required
                    value="<?= htmlspecialchars($email) ?>">
            </div>

            <div>
                <label for="telefono">Teléfono (España) *</label>
                <input type="text" id="telefono" name="telefono" placeholder="Ej: +34600123456 o 911234567" required
                    value="<?= htmlspecialchars($telefono) ?>">
            </div>

            <?php if ($nuevo): ?>
                <button onclick="anadirContacto(<?= $cliente_id_form ?>)">Crear Contacto</button>
            <?php else: ?>
                <button onclick="modificarContacto(<?= $cliente_id_form ?>)">Modificar Contacto</button>
            <?php endif; ?>

             <button class="btn secondary" type="button" onclick="IrListadoContactos(<?= $cliente_id_form ?>)">
                Volver al Listado
            </button>
        </form>
    </div>
</body>
</html>