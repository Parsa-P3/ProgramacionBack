<?php

//Me traigo el fichero que tiene todas las librerias básicas del proyecto
require_once "utils.php";

//Incluyo mi clases necesarias
require_once "./models/Usuarios.php";
require_once "./models/Roles.php";

//Me instancio mi clase de usuario
$usu = new Usuario();

// Obtenemos la acción del query string
$accion = $_GET['action'] ?? '';
$list = $_GET['listado'] ?? false;

// Verificamos si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    $rol_id = (int)($_POST['rol_id'] ?? 2);
    $usuario_id = (int)($_POST['usuario_id']  ?? 0);

    //Creo mis dos clases de las entidades que necesito
    $usu = new Usuario();
    $usu = $usu->obtenerPorId($pdo, (int)$usuario_id);

    $usu->setEmail($email);
    $usu->setNombre($nombre);
    $usu->setApellidos($apellidos);
    $usu->setUsuario($usuario);
    $usu->setPassword($password);
    $usu->setRolId($rol_id);
    $usu->setId($usuario_id);

    // Llamamos a la función correspondiente
    switch ($accion) {
        case 'guardar':
            $error = validar();
            if ($error == "") {
                $usu->guardar($pdo);
            }
            volver($error, $list);
            break;
        case 'eliminar':
            print_r("elimina");
            $error = validar();
            print_r($error);
            if ($error == "") {
                $usu->eliminar($pdo);
            }
            volver($error, $list);
            break;
        case 'anadir':
            $error = validar();
            if ($error == "") {
                $usu->guardar($pdo);
            }
            volver($error, $list);
            break;

        default:
    }
}

function volver($error = "", $list = false)
{
    global $email;
    global $nombre;
    global $apellidos;
    global $usuario;
    global $rol_id;
    global $usuario_id;
    global $accion;
    //Volvemos a la página que ha hecho el submit en caso de error
    if ($error == "") {
        if ($list == true) {
            header('Location: listado.php?ok=1');
        } else {
            header('Location: index.php?ok=1');
        }
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

function validar(): string
{
    global $accion;
    global $usuario_id;
    global $email;
    global $usuario;
    global $password;
    global $nombre;
    global $apellidos;
    global $pdo;
    $error = "";

    switch ($accion) {
        case 'guardar':
            if (!(isset($usuario_id)) || $usuario_id == 0) {
                $error .= "Tiene que seleccionar un usuario para poder modificarlo--";
            }
            
            if (!(isset($email)) || $email == "") {
                $error .= "Es necesario rellenar el campo email--";
            } elseif (comprobarPatronEmail($email) == false) {
                $error .= "El email no tiene el formato correcto--";
            } elseif (emailExisteUsuario($pdo, $email, $usuario_id)) {
                $error .= "Error: el email ya está registrado en la base de datos--";
            }

            if (!(isset($usuario)) || $usuario == "") {
                $error .= "Es necesario rellenar el campo usuario--";
            } elseif (usuarioExiste($pdo, $usuario, $usuario_id)) {
                $error .= "Error: el nombre de usuario ya está en uso--";
            }
            
            if (!(isset($nombre)) || $nombre == "") {
                $error .= "Es necesario rellenar el campo nombre--";
            }
            
            if (!(isset($apellidos)) || $apellidos == "") {
                $error .= "Es necesario rellenar el campo apellidos--";
            }
            
            if (!(isset($password)) || $password == "") {
                $error .= "Es necesario rellenar el campo password--";
            } elseif (comprobarPassword($password) == false) {
                $error .= "La password tiene que tener al menos una mayúscula, un número, un carácter alfanumérico y al menos 8 caracteres--";
            }
            break;
            
        case 'eliminar':
            if (!(isset($usuario_id)) || $usuario_id == 0) {
                $error .= "Tiene que seleccionar un usuario para poder eliminarlo";
            }
            break;
            
        case 'anadir':
            if (!(isset($email)) || $email == "") {
                $error .= "Es necesario rellenar el campo email--";
            } elseif (comprobarPatronEmail($email) == false) {
                $error .= "El email no tiene el formato correcto--";
            } elseif (emailExisteUsuario($pdo, $email)) {
                $error .= "Error: el email ya está registrado en la base de datos--";
            }

            if (!(isset($usuario)) || $usuario == "") {
                $error .= "Es necesario rellenar el campo usuario--";
            } elseif (usuarioExiste($pdo, $usuario)) {
                $error .= "Error: el nombre de usuario ya está en uso--";
            }

            if (!(isset($nombre)) || $nombre == "") {
                $error .= "Es necesario rellenar el campo nombre--";
            }

            if (!(isset($apellidos)) || $apellidos == "") {
                $error .= "Es necesario rellenar el campo apellidos--";
            }

            if (!(isset($password)) || $password == "") {
                $error .= "Es necesario rellenar el campo password--";
            } elseif (comprobarPassword($password) == false) {
                $error .= "La password tiene que tener al menos una mayúscula, un número, un carácter alfanumérico y al menos 8 caracteres--";
            }

            break;
            
        case 'login':

            if (!(isset($usuario)) || $usuario == "") {
                $error .= "Es necesario rellenar el campo usuario--";
            }

            if (!(isset($password)) || $password == "") {
                $error .= "Es necesario rellenar el campo password--";
            }

            //Intento validar el login por aqui
            $usu = new Usuario();
            $usu = $usu->login($pdo, $usuario, $password);
            if (!(isset($usu))) {
                //El usuario es nulo y no ha hecho login en la bbdd
                $error .= "No hay ningun usuario con esa contraseña en el sistema--";
            } else {
                //Genero la sesion del usuario
                crearSesion($usu);
            }
            break;

        default:
            break;
    }

    return $error;
}
