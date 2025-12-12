<?php

//Me traigo el fichero que tiene todas las librerias básicas del proyecto
require_once __DIR__ . "/../utils.php";

//Incluyo mi clases necesarias
require_once __DIR__ . "/../models/Contacto.php";

// Compruebo la sesión y si no está conectado o la sesión caducó, me redirige al login
if (!comprobarSesion()) {
    return;
}

// **Rúbrica: Valida que solo un usuario conectado admin pueda añadir, borrar o modificar contactos (B-0.5)**
$usu_conectado = $_SESSION["usuario"];
if ($usu_conectado->getRolId() != 1) {
    header('Location: ../listados/listado_clientes.php?error=No tiene permisos de administrador para gestionar contactos');
    return;
}


// Me instancio mi clase de contacto
$cont = new Contacto();

// Obtenemos la acción del query string
$accion = $_GET['action'] ?? '';


// Verificamos si se ha enviado el formulario por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recogemos los datos (ya sanitizados por engine/sanetizar.php incluido en utils.php)
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $contacto_id = (int)($_POST['contacto_id'] ?? 0);
    $cliente_id = (int)($_POST['cliente_id'] ?? 0);

    // Creamos/Cargamos el objeto Contacto
    if ($contacto_id != 0) {
        $cont = $cont->obtenerPorId($pdo, (int)$contacto_id);
    }

    $cont->setClienteId($cliente_id);
    $cont->setNombre($nombre);
    $cont->setApellidos($apellidos);
    $cont->setEmail($email);
    $cont->setTelefono($telefono);
    $cont->setId($contacto_id); // Necesario para el UPDATE

    // 1. Validamos los datos (Rúbrica 2 - Valida el contenido)
    $error = validar($accion);

    if ($error != "") {
        // Hay errores, volvemos a la ficha con el mensaje de error y los datos
        volverFicha($error);
    } else {
        // No hay errores, procedemos con la acción CRUD
        switch ($accion) {
            case 'anadir': // Añade: B-1
                $cont->guardar($pdo);
                // Redireccionamos al listado de contactos del cliente
                header('Location: listados/listado_contactos.php?cliente_id=' . $cliente_id . '&ok=1');
                break;
            case 'guardar': // Actualiza: B-1
                $cont->guardar($pdo);
                // Redireccionamos al listado de contactos del cliente
                header('Location: listados/listado_contactos.php?cliente_id=' . $cliente_id . '&ok=1');
                break;
            default:
                // Redirigir por defecto
                header('Location: listados/listado_contactos.php?cliente_id=' . $cliente_id);
                break;
        }
    }
}

/**
 * Función para validar el contenido del formulario. (Rúbrica 2)
 */
function validar($accion): string
{
    global $nombre;
    global $email;
    global $telefono;
    global $cliente_id;
    $error = "";

    switch ($accion) {
        case 'guardar': 
        case 'anadir': 
            if (!isset($cliente_id) || $cliente_id == 0) {
                $error .= "El contacto debe estar asociado a un cliente--";
            }
            if (!comprobarPatronEmail($email)) {
                $error .= "El email no tiene el formato correcto--";
            }
            // Validación de teléfono español
            if (!comprobarTelefonoEspana($telefono)) {
                $error .= "El teléfono no tiene un formato de España válido (ej. +34600123456)--";
            }
            if (!isset($nombre) || $nombre == "") {
                $error .= "Es necesario rellenar el campo nombre--";
            }
            break;
    }

    return $error;
}

/**
 * Redirige de vuelta al formulario con el error y los datos.
 */
function volverFicha($error = "")
{
    global $nombre;
    global $apellidos;
    global $email;
    global $telefono;
    global $contacto_id;
    global $cliente_id;
    
    // Codificamos los datos para pasarlos por URL
    $query = "contacto_id={$contacto_id}&cliente_id={$cliente_id}&error={$error}&nombre={$nombre}&apellidos={$apellidos}&email={$email}&telefono={$telefono}";
    
    header('Location: ficha_contacto.php?' . $query);
    exit();
}

// Si la petición es GET y la acción es eliminar (viene del JS)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $accion == 'eliminar') {
    $contacto_id = (int)($_GET['contacto_id'] ?? 0);
    $cliente_id = (int)($_GET['cliente_id'] ?? 0);
    
    // Validamos que hay un ID de contacto
    if ($contacto_id != 0) {
        $cont = $cont->obtenerPorId($pdo, $contacto_id);
        $cont->eliminar($pdo); // Elimina: B-1
        // Redirigimos al listado filtrado de ese cliente
        header('Location: ../listados/listado_contactos.php?cliente_id=' . $cliente_id . '&ok=1');
    } else {
        header('Location: ../listados/listado_contactos.php?cliente_id=' . $cliente_id . '&error=ID de contacto no válido');
    }
}

?>