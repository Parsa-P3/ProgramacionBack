<?php
// Activar manejador de errores
set_error_handler("manejaErrores");
set_exception_handler("manejaExcepciones");

function manejaErrores($nivel, $mensaje, $fichero, $linea)
{
    // Hata detaylarını log dosyasına yazar
    $log_mensaje = "Fecha: " . date("H:i d-m-Y") .
                  " | Mensaje: " . $mensaje .
                  " | Archivo: " . $fichero .
                  " | Línea: " . $linea .
                  " | Usuario: " . (isset($_SESSION['usuario']) ? $_SESSION['usuario']->getUsuario() : 'Anonimo') .
                  " | IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . PHP_EOL;

    // Log dosyasını xampp/apache/logs/ dizininde oluşturur
    error_log($log_mensaje, 3, __DIR__ . "/../gestor_contactos_errors.log");
}


function manejaExcepciones(Throwable $ex)
{
    // İstisna detaylarını log dosyasına yazar
    $log_mensaje = "Fecha: " . date("H:i d-m-Y") .
                  " | Mensaje: " . $ex->getMessage() .
                  " | Archivo: " . $ex->getFile() .
                  " | Línea: " . $ex->getLine() .
                  " | Usuario: " . (isset($_SESSION['usuario']) ? $_SESSION['usuario']->getUsuario() : 'Anonimo') .
                  " | IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . PHP_EOL;

    error_log($log_mensaje, 3, __DIR__ . "/../gestor_contactos_errors.log");
}