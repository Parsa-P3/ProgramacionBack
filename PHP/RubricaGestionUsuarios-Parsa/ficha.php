<?php
// prepara datos para crear o editar un usuario
require_once "utils.php";

if (!validarSesionActiva()) {
    exit();
}

require_once "./models/Usuarios.php";
require_once "./models/Roles.php";

$usu = new Usuario();
$rol = new Rol();

$usuario_id = (int)($_GET['usuario_id'] ?? 0);
$error = $_GET['error'] ?? '';
$listado = $_GET['listado'] ?? '';

// Definición de valores por defecto
$nuevo = $usuario_id === 0;
$usuario = "";
$email = "";
$nombre = "";
$password = "";
$apellidos = "";
$rol_id = 0;

if ($error != "") {
    // Si hay error, repoblar campos desde la URL
    $email = $_GET['email'] ?? '';
    $nombre = $_GET['nombre'] ?? '';
    $apellidos = $_GET['apellidos'] ?? '';
    $usuario = $_GET['usuario'] ?? '';
    $password = $_GET['password'] ?? '';
    $rol_id = (int)($_GET['rol_id'] ?? 2);
    $usuario_id = (int)($_GET['usuario_id']  ?? 0);
    $error_html = '<div class="error-box">' . nl2br(htmlspecialchars(str_replace("--", "\n", $error))) . '</div>';
} else if (!$nuevo) {
    // Cargar datos del usuario para edición
    $usu = $usu->obtenerPorId($pdo, $usuario_id);
    $usuario = $usu->getUsuario();
    $email = $usu->getEmail();
    $nombre = $usu->getNombre();
    $password = $usu->getPassword();
    $apellidos = $usu->getApellidos();
    $rol_id = $usu->getRolId();
}

if (isset($_SESSION["usuario"])) {
    $usu_conectado = $_SESSION["usuario"];
    $rol_id_usuario = $usu_conectado->getRolId();
}

?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <?php if ($nuevo): ?>
    <title>Nuevo Usuario</title>
    <?php else: ?>
    <title>Modificar usuario</title>
    <?php endif; ?>
    <link rel="stylesheet" href="./estilos/estilos.css">
    <script src="./scripts/scripts.js"></script>
</head>

<body>
    <div class="container">
        <?php if ($nuevo): ?>
        <h1>Nuevo Usuario</h1>
        <?php else: ?>
        <h1>Modificar usuario</h1>
        <?php endif; ?>

        <?php if (!empty($error_html)): ?>
        <?= $error_html ?>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" id="usuario_id" name="usuario_id"
                value="<?= $usuario_id ?>">
            <div>
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" placeholder="Nombre de usuario" required
                    value="<?= $usuario ?>">
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required
                    value="<?= $email ?>">
            </div>

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

            <?php if ($rol_id_usuario == 1): ?>
            <div>
                <label for="rol_id">Rol</label>
                <select id="rol_id" name="rol_id" required>
                    <option value="1">Admin</option>
                    <option value="2">Usuario</option>
                </select>
            </div>
            <?php endif; ?>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required value="">
            </div>

            <?php if ($nuevo): ?>
            <button
                onclick="anadirUsuario(<?= ($listado == 'true' ? 'true' : '') ?>)">Crear
                Usuario</button>
            <?php else: ?>
            <button
                onclick="modificarUsuario(<?= ($listado == 'true' ? 'true' : '') ?>)">Modificar
                Usuario</button>
            <?php endif; ?>

        </form>
    </div>
</body>

</html>