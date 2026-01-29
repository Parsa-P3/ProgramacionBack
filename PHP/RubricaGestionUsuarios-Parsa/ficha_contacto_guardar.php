<?php

// Cargar utilidades y modelos necesarios
require_once "utils.php";
require_once "./models/Contacto.php";

// Comprobar que la sesión está activa
if (!validarSesionActiva()) {
    exit();
}

// Control de acceso: solo administradores pueden gestionar contactos
$usu_conectado = $_SESSION["usuario"];
if ($usu_conectado->getRolId() != 1) {
    header('Location: listado_clientes.php?error=No tiene permisos de administrador para gestionar contactos');
    return;
}

// Instanciar objeto Contacto y obtener la acción solicitada
$cont = new Contacto();
$accion = $_GET['action'] ?? '';

// Manejar envío del formulario por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recoger datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $contacto_id = (int)($_POST['contacto_id'] ?? 0);
    $cliente_id = (int)($_POST['cliente_id'] ?? 0);

    // Si es edición, cargar el contacto existente
    if ($contacto_id != 0) {
        $cont = $cont->obtenerPorId($pdo, (int)$contacto_id);
    }

    // Preparar objeto con los datos recibidos
    $cont->setClienteId($cliente_id);
    $cont->setNombre($nombre);
    $cont->setApellidos($apellidos);
    $cont->setEmail($email);
    $cont->setTelefono($telefono);
    $cont->setId($contacto_id);

    // Validar los datos recibidos
    $error = validar($accion);

    // Si hay errores, volver al formulario con los datos
    if ($error != "") {
        volverFicha($error);
    } else {
        // Ejecutar la acción solicitada (crear/actualizar)
        // Se captura excepciones PDO para detectar violaciones de unicidad
        // (teléfono/email duplicados) y mostrar mensajes amigables al usuario
        switch ($accion) {
            case 'anadir':
                try {
                    $cont->guardar($pdo);
                    header('Location: listado_contactos.php?cliente_id=' . $cliente_id . '&ok=1');
                } catch (PDOException $e) {
                    $msg = $e->getMessage();
                    if (strpos($msg, 'contactos.telefono') !== false) {
                        volverFicha('Error: El teléfono ya está registrado para otro contacto--');
                    } elseif (strpos($msg, 'contactos.email') !== false) {
                        volverFicha('Error: El email ya está registrado para otro contacto--');
                    } else {
                        volverFicha('Error: Error al guardar el contacto en la base de datos--');
                    }
                }
                break;
            case 'guardar':
                try {
                    $cont->guardar($pdo);
                    header('Location: listado_contactos.php?cliente_id=' . $cliente_id . '&ok=1');
                } catch (PDOException $e) {
                    $msg = $e->getMessage();
                    if (strpos($msg, 'contactos.telefono') !== false) {
                        volverFicha('Error: El teléfono ya está registrado para otro contacto--');
                    } elseif (strpos($msg, 'contactos.email') !== false) {
                        volverFicha('Error: El email ya está registrado para otro contacto--');
                    } else {
                        volverFicha('Error: Error al guardar el contacto en la base de datos--');
                    }
                }
                break;
            default:
                header('Location: listado_contactos.php?cliente_id=' . $cliente_id);
                break;
        }
    }
}

// Valida y devuelve una cadena con errores si los hay
function validar($accion): string
{
    global $nombre;
    global $apellidos;
    global $email;
    global $telefono;
    global $cliente_id;
    global $contacto_id;
    global $pdo;
    $error = "";

    switch ($accion) {
        case 'guardar':
        case 'anadir':
            if (!isset($cliente_id) || $cliente_id == 0) {
                $error .= "El contacto debe estar asociado a un cliente--";
            }

            if (!isset($nombre) || $nombre == "") {
                $error .= "Es necesario rellenar el campo nombre--";
            }

            if (!isset($apellidos) || $apellidos == "") {
                $error .= "Es necesario rellenar el campo apellidos--";
            }

            if (!isset($email) || $email == "") {
                $error .= "Es necesario rellenar el campo email--";
            } elseif (!comprobarPatronEmail($email)) {
                $error .= "El email no tiene el formato correcto--";
            } elseif ($accion == 'anadir' && emailExisteContacto($pdo, $email)) {
                $error .= "Error: el email ya está registrado en la base de datos--";
            } elseif ($accion == 'guardar' && emailExisteContacto($pdo, $email, $contacto_id)) {
                $error .= "Error: el email ya está registrado en la base de datos--";
            }

            if (!isset($telefono) || $telefono == "") {
                $error .= "Es necesario rellenar el campo teléfono--";
            } elseif (!comprobarTelefonoEspana($telefono)) {
                $error .= "El teléfono debe empezar por 34 y el primer dígito tras el prefijo debe ser 6,7,8 o 9 (ej.: 34612345678)--";
            }
            break;
    }

    return $error;
}

// Redirige de vuelta al formulario con los datos y mensaje de error
function volverFicha($error = "")
{
    global $nombre;
    global $apellidos;
    global $email;
    global $telefono;
    global $contacto_id;
    global $cliente_id;
    $params = [
        'contacto_id' => $contacto_id,
        'cliente_id' => $cliente_id,
        'error' => $error,
        'nombre' => $nombre,
        'apellidos' => $apellidos,
        'email' => $email,
        'telefono' => $telefono
    ];
    header('Location: ficha_contacto.php?' . http_build_query($params));
    exit();
}

// Manejar petición GET para eliminar contacto
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $accion == 'eliminar') {
    $contacto_id = (int)($_GET['contacto_id'] ?? 0);
    $cliente_id = (int)($_GET['cliente_id'] ?? 0);

    if ($contacto_id != 0) {
        $cont = $cont->obtenerPorId($pdo, $contacto_id);
        $cont->eliminar($pdo);
        header('Location: listado_contactos.php?cliente_id=' . $cliente_id . '&ok=1');
    } else {
        header('Location: listado_contactos.php?cliente_id=' . $cliente_id . '&error=ID de contacto no válido');
    }
}

?>