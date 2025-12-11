<?php
// Toma como origen la carpeta inicial
$carpeta = 'C:\\Users\\Alumno.DESKTOP-DI5KTUG\\Desktop\\ServidorPHP';
$tabulacion_inicial = "";

// Llama a la función principal para comenzar el listado
pintarContenido($carpeta, $tabulacion_inicial);

function pintarContenido($carpeta, $tabulacion_inicial)
{
    // Ficheros del directorio actual
    $lista = scandir($carpeta);

    // Verifica si la lectura fue exitosa
    if ($lista === FALSE) {
        echo $tabulacion_inicial . "ERROR: No se pudo leer el directorio " . htmlspecialchars($carpeta) . "<br>";
        return;
    }

    foreach ($lista as $fichero) {
        
        if ($fichero != "." && $fichero != ".." && $fichero != ".git") {
            
            // Construir la ruta completa para la comprobación
            // Nota: Se reemplaza el '/' por DIRECTORY_SEPARATOR para mayor compatibilidad
            $rutaCompleta = $carpeta . DIRECTORY_SEPARATOR . $fichero;
            
            // Compruebo que tiene algun directorio y si es así llamo al proceso
            $es_directorio = is_dir($rutaCompleta);
            $es_fichero = is_file($rutaCompleta);
            
            if ($es_directorio) {
                
                echo $tabulacion_inicial . "# Directorio ". htmlspecialchars($fichero) . " <br>";
                
                // Llama a sí misma (recursión) para entrar en el subdirectorio
                // Añadir tabulación extra para el siguiente nivel (usando &nbsp;)
                $nueva_tabulacion = $tabulacion_inicial . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                pintarContenido($rutaCompleta, $nueva_tabulacion);
            }
            
            if ($es_fichero) {
                echo $tabulacion_inicial . "* Fichero " . htmlspecialchars($fichero) . " <br>";
            }
        }
    }
}
?>