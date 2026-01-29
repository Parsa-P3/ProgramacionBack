<?php

// Cargar utilidades y modelos de usuario
require_once "utils.php";
require_once "./models/Usuarios.php";
require_once "./models/Roles.php";

// Instancia y parámetros de la petición
$accion = $_GET['action'] ?? '';
$list = $_GET['listado'] ?? false;
// Nota: la instancia de Usuario se crea cuando hace falta (se carga más abajo)

// Manejar envío del formulario por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $email = $_POST['email'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    $rol_id = (int)($_POST['rol_id'] ?? 2);
    $usuario_id = (int)($_POST['usuario_id']  ?? 0);

    // Cargar o preparar el objeto Usuario
    $usu = (new Usuario())->obtenerPorId($pdo, $usuario_id);
    $usu->setEmail($email);
    $usu->setNombre($nombre);
    $usu->setApellidos($apellidos);
    $usu->setUsuario($usuario);
    $usu->setPassword($password);
    $usu->setRolId($rol_id);
    $usu->setId($usuario_id);

    // Ejecutar la acción solicitada
    // Las siguientes acciones manejan guardar, eliminar o añadir usuarios
    switch ($accion) {
        case 'guardar':
            $error = validar();
            if ($error == "") $usu->guardar($pdo);
            volver($error, $list);
            break;
        case 'eliminar':
            $error = validar();
            if ($error == "") $usu->eliminar($pdo);
            volver($error, $list);
            break;
        case 'anadir':
            $error = validar();
            if ($error == "") $usu->guardar($pdo);
            volver($error, $list);
            break;
    }
}

// Redirige de vuelta al formulario o listado con datos en caso de error/ok
function volver($error = "", $list = false)
{
    global $email, $nombre, $apellidos, $usuario, $rol_id, $usuario_id;
    if ($error == "") {
        header('Location: ' . ($list ? 'listado.php?ok=1' : 'index.php?ok=1'));
    } else {
        $params = [
            'error' => $error,
            'email' => $email,
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'usuario' => $usuario,
            'rol_id' => $rol_id,
            'usuario_id' => $usuario_id
        ];
        header('Location: ficha.php?' . http_build_query($params));
    }
    exit();
}

// Valida los datos recibidos según la acción solicitada y devuelve errores
function validar(): string
{
    global $accion, $usuario_id, $email, $usuario, $password, $nombre, $apellidos, $pdo;
    $error = "";

    switch ($accion) {
        case 'guardar':
            if (!isset($usuario_id) || $usuario_id == 0) $error .= "Tiene que seleccionar un usuario para poder modificarlo--";
            if (empty($email)) $error .= "Es necesario rellenar el campo email--";
            elseif (!comprobarPatronEmail($email)) $error .= "El email no tiene el formato correcto--";
            elseif (emailExisteUsuario($pdo, $email, $usuario_id)) $error .= "Error: el email ya está registrado en la base de datos--";

            if (empty($usuario)) $error .= "Es necesario rellenar el campo usuario--";
            elseif (usuarioExiste($pdo, $usuario, $usuario_id)) $error .= "Error: el usuario no valido , intenta con otro usuario ";

            if (empty($nombre)) $error .= "Es necesario rellenar el campo nombre--";
            if (empty($apellidos)) $error .= "Es necesario rellenar el campo apellidos--";
            if (empty($password)) $error .= "Es necesario rellenar el campo password--";
            elseif (!comprobarPassword($password)) $error .= "La password tiene que tener al menos una mayúscula, un número, un carácter alfanumérico y al menos 8 caracteres--";
            break;

        case 'eliminar':
            if (!isset($usuario_id) || $usuario_id == 0) $error .= "Tiene que seleccionar un usuario para poder eliminarlo";
            break;

        case 'anadir':
            if (empty($email)) $error .= "Es necesario rellenar el campo email--";
            elseif (!comprobarPatronEmail($email)) $error .= "El email no tiene el formato correcto--";
            elseif (emailExisteUsuario($pdo, $email)) $error .= "Error: el email ya está registrado en la base de datos--";

            if (empty($usuario)) $error .= "Es necesario rellenar el campo usuario--";
            elseif (usuarioExiste($pdo, $usuario)) $error .= "Error: el usuario no valido , intenta con otro usuario";

            if (empty($nombre)) $error .= "Es necesario rellenar el campo nombre--";
            if (empty($apellidos)) $error .= "Es necesario rellenar el campo apellidos--";
            if (empty($password)) $error .= "Es necesario rellenar el campo password--";
            elseif (!comprobarPassword($password)) $error .= "La password tiene que tener al menos una mayúscula, un número, un carácter alfanumérico y al menos 8 caracteres--";
            break;

        case 'login':
            if (empty($usuario)) $error .= "Es necesario rellenar el campo usuario--";
            if (empty($password)) $error .= "Es necesario rellenar el campo password--";

            $usu = (new Usuario())->login($pdo, $usuario, $password);
            if (!isset($usu)) $error .= "No hay ningun usuario con esa contraseña en el sistema--";
            else crearSesion($usu);
            break;
    }

    return $error;
}
