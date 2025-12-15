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

// Obtenemos la acción del query string
$accion = $_GET['action'] ?? '';
$list = $_GET['listado'] ?? false;

// Verificamos si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = $_POST['nombre'] ?? '';
    $cif = $_POST['cif'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $edad = (int)($_POST['edad'] ?? 0);
    $cliente_id = (int)($_POST['cliente_id']  ?? 0);

    //Creo mis dos clases de las entidades que necesito
    $cli = new Cliente();
    $cli = $cli->obtenerPorId($pdo, (int)$cliente_id);

    $cli->setNombre($nombre);
    $cli->setCIF($cif);
    $cli->setEmail($email);
    $cli->setTelefono($telefono);
    $cli->setApellidos($apellidos);
    $cli->setEdad($edad);
    $cli->setId($cliente_id);

    // Llamamos a la función correspondiente
    switch ($accion) {
        case 'guardar':
            $error = validar();
            if ($error == "") {
                $cli->guardar($pdo);
            }
            volver($error, $list);
            break;
        case 'eliminar':
            $error = validar();
            if ($error == "") {
                $cli->eliminar($pdo);
            }
            volver($error, $list);
            break;
        case 'anadir':
            $error = validar();
            if ($error == "") {
                $cli->guardar($pdo);
            }
            volver($error, $list);
            break;

        default:
    }
}

function volver($error = "", $list = false)
{

    global $nombre;
    global $cif;
    global $email;
    global $telefono;
    global $apellidos;
    global $edad;
    global $cliente_id;

    //Volvemos a la página que ha hecho el submit en caso de error
    if ($error == "") {
        if ($list == true) {
            header('Location: listado_clientes.php?ok=1');
        } else {
            header('Location: index.php?ok=1');
        }
    } else {
        $params = [
            'error' => $error,
            'nombre' => $nombre,
            'cif' => $cif,
            'email' => $email,
            'telefono' => $telefono,
            'apellidos' => $apellidos,
            'edad' => $edad,
            'cliente_id' => $cliente_id
        ];
        header('Location: ficha_cliente.php?' . http_build_query($params));
    }
    exit();
}

function validar(): string
{
    global $accion;
    global $nombre;
    global $cif;
    global $email;
    global $cliente_id;
    global $telefono;
    global $apellidos;
    global $edad;
    global $pdo;
    $error = "";

    switch ($accion) {
        case 'guardar':
            if (!(isset($cliente_id)) || $cliente_id == 0) {
                $error .= "Tiene que seleccionar un cliente para poder modificarlo--";
            }
            
            if (!(isset($nombre)) || $nombre == "") {
                $error .= "Es necesario rellenar el campo nombre--";
            }
            
            if (!(isset($apellidos)) || $apellidos == "") {
                $error .= "Es necesario rellenar el campo apellidos--";
            }

            if (!(isset($email)) || $email == "") {
                $error .= "Es necesario rellenar el campo email--";
            } elseif (comprobarPatronEmail($email) == false) {
                $error .= "El email no tiene el formato correcto--";
            } elseif (emailExisteCliente($pdo, $email, $cliente_id)) {
                $error .= "Error: el email ya está registrado en la base de datos--";
            }

            if (!(isset($telefono)) || $telefono == "") {
                $error .= "Es necesario rellenar el campo teléfono--";
            } elseif (comprobarTelefonoEspana($telefono) == false) {
                $error .= "El teléfono debe empezar por 34 y el primer dígito tras el prefijo debe ser 6,7,8 o 9 (ej.: 34612345678)--";
            }

            if (!(isset($cif)) || $cif == "") {
                $error .= "Es necesario rellenar el campo CIF/NIF--";
            } elseif (comprobarDocumento($cif) == false) {
                $error .= "El CIF/NIF no tiene un formato válido. Debe ser 8 dígitos + letra (NIF), Y/X/Z + 7 dígitos + letra (NIE) o un formato corporativo (Ej: B12345678)--";
            } elseif (cifExisteCliente($pdo, $cif, $cliente_id)) {
                $error .= "Error: el CIF/NIF ya está registrado en la base de datos--";
            }
            
            if (!(isset($edad)) || $edad == "") {
                $error .= "Es necesario rellenar el campo edad--";
            } elseif (!comprobarEdad($edad)) {
                $error .= "La edad debe ser mayor o igual a 18 años--";
            }
            break;
            
        case 'eliminar':
            if (!(isset($cliente_id)) || $cliente_id == 0) {
                $error .= "Tiene que seleccionar un cliente para poder eliminarlo";
            }
            break;
            
        case 'anadir':
            if (!(isset($nombre)) || $nombre == "") {
                $error .= "Es necesario rellenar el campo nombre--";
            }

            if (!(isset($apellidos)) || $apellidos == "") {
                $error .= "Es necesario rellenar el campo apellidos--";
            }

            if (!(isset($email)) || $email == "") {
                $error .= "Es necesario rellenar el campo email--";
            } elseif (comprobarPatronEmail($email) == false) {
                $error .= "El email no tiene el formato correcto--";
            } elseif (emailExisteCliente($pdo, $email)) {
                $error .= "Error: el email ya está registrado en la base de datos--";
            }

            if (!(isset($telefono)) || $telefono == "") {
                $error .= "Es necesario rellenar el campo teléfono--";
            } elseif (comprobarTelefonoEspana($telefono) == false) {
                $error .= "El teléfono debe empezar por 34 y el primer dígito tras el prefijo debe ser 6,7,8 o 9 (ej.: 34612345678)--";
            }

            if (!(isset($cif)) || $cif == "") {
                $error .= "Es necesario rellenar el campo CIF/NIF--";
            } elseif (comprobarDocumento($cif) == false) {
                $error .= "El cif no tiene el formato correcto--";
            } elseif (cifExisteCliente($pdo, $cif)) {
                $error .= "Error: el CIF/NIF ya está registrado en la base de datos--";
            }

            if (!(isset($edad)) || $edad == "") {
                $error .= "Es necesario rellenar el campo edad--";
            } elseif (!comprobarEdad($edad)) {
                $error .= "La edad debe ser mayor o igual a 18 años--";
            }

            break;

        default:
            break;
    }

    return $error;
}
?>
