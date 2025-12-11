<?php
require_once "./models/Usuarios.php";
session_start();

function crearSesion(Usuario $usu)
{
    $_SESSION["usuario"] = $usu;
    $_SESSION["login_time"] = time();
}

function borrarSesion()
{
    // Session verilerini temizler ve oturumu sonlandırır
    $_SESSION = array();
    session_destroy();
}

function comprobarSesion(): bool
{
    if (!isset($_SESSION['usuario'])) {
        return false;
    }

    global $config;
    $durSesion = (int)$config['sesion']['duracion_seg'];
    
    // Oturum süresi kontrolü
    if ((time() - $_SESSION["login_time"]) > $durSesion) {
        borrarSesion();
        header('Location: login.php?accion=sesioncaducada');
        exit();
    }
    
    // Oturum yenileme
    $_SESSION["login_time"] = time();
    return true;
}