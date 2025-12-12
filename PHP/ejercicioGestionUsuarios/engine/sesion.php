<?php

// incluimos nuestro fichero de usuarios
require_once __DIR__ . "/../models/Usuarios.php";

//Obligatorio en todas las páginas para la página tenga acceso a la sesion del servidor
session_start();

// metodo para crear sesion (con cookies)
function crearSesion(Usuario $usu)
{
    //Creo la nueva sesion de usuario con el objeto de usuario y asi tengo todo el usuario a mano
    $_SESSION["usuario"] = $usu;
    $_SESSION["login_time"] = time();

    //GENERO UNA COKKIE PARA EL ROL, aun que no debería de ser asi porque la mandamos al front y es modificable por el usuario
    setcookie("rol_id", $usu->getRolId());
    setcookie("conectado", "true");
}

// metodo para borrar sesion
function borrarSesion()
{
    // Vacía la sesión correctamente
    // Destruir la sesión del servidor
    session_destroy();

    // Elimina las cookies correctamente
    setcookie("rol_id");
    setcookie("conectado");
}


// metodo para comprobar sesion
function comprobarSesion(): bool
{
    global $config;
    $salida = true;
    $durSesion = (int)$config['sesion']['duracion_seg'];
    //Esta funcion me comprueba si el usuario esta conectado en base a que haya pasado un minuto
    if ((time() - $_SESSION["login_time"]) > $durSesion) {
        //Si el tiempo de sesion supera el tiempo predefinido estruyo la sesion
        borrarSesion();

        //Actualizo mi variable de salida
        $salida = false;

        //Le envio de nuevo al login
        header('Location: login.php?accion=sesioncaducada');
    }
    return $salida;
}

// metodo para cerrar sesion
function cerrarSesion()
{

    //Cerrar sesion
    borrarSesion();

    //Le envio de nuevo al login
    header('Location: login.php');

}

//Cada vez que cambio de página compruebo la sesion siempre y cuando tenga la cookie
$conectado = (isset($_COOKIE["conectado"]) && $_COOKIE["conectado"] != "" ? true : false);
if ($conectado == true) {
    comprobarSesion();
}
