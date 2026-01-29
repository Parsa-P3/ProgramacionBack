<?php

require_once "./models/Usuarios.php";

//$config = require_once "config.php";
//Obligatorio en todas las páginas para la página tenga acceso a la sesion del servidor
session_start();

// Crea una sesión para el usuario dado y guarda datos en $_SESSION y cookies
function crearSesion(Usuario $usu)
{
    //Creo la nueva sesion de usuario con el objeto de usuario y asi tengo todo el usuario a mano
    $_SESSION["usuario"] = $usu;
    $_SESSION["login_time"] = time();

    //GENERO UNA COKKIE PARA EL ROL, aun que no debería de ser asi porque la mandamos al front y es modificable por el usuario
    setcookie("rol_id", $usu->getRolId());
    setcookie("conectado", "true");
}

// Elimina la sesión y borra cookies relacionadas
function borrarSesion()
{
    // Vacía la sesión correctamente
    // Destruir la sesión del servidor
    session_destroy();

    // Elimina las cookies correctamente
    setcookie("rol_id");
    setcookie("conectado");
}


// Comprueba si la sesión ha expirado según la configuración
function comprobarSesion(): bool
{
    global $config;
    $salida = true;
    $durSesion = (int)$config['sesion']['duracion_seg'];
    
    //Esta funcion me comprueba si el usuario esta conectado en base a que haya pasado un minuto
    if ((time() - $_SESSION["login_time"]) > $durSesion) {
        //Destruyo la sesion
        borrarSesion();

        //Actualizo mi variable de salida
        $salida = false;
        //Le envio de nuevo al login
        header('Location: login.php?accion=sesioncaducada');
    }
    return $salida;
}

// Cierra la sesión y redirige al login
function cerrarSesion()
{

    //Cerrar sesion
    borrarSesion();

    //Le envio de nuevo al login
    header('Location: login.php');

}

// Valida que haya una sesión de usuario activa, redirige si no
function validarSesionActiva(): bool
{
    // Valida que el usuario tenga una sesión activa
    if (!isset($_SESSION["usuario"])) {
        header('Location: login.php?accion=requierelogin');
        exit();
    }
    
    // Si existe usuario en sesión, comprobamos si ha expirado
    return comprobarSesion();
}

//Cada vez que cambio de página compruebo la sesion siempre y cuando tenga la cookie
$conectado = (isset($_COOKIE["conectado"]) && $_COOKIE["conectado"] != "" ? true : false);
if ($conectado == true) {
    comprobarSesion();
}
