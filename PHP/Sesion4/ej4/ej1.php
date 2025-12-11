<?php

$nombreArchivo = "mi_archivo.txt";
$contenidoArchivo = " HELLO WORLD! ";
$ruta_directorio = __DIR__; // Obtiene la ruta del directorio donde está el archivo PHP
$ruta_archivo = __DIR__ . '/' . 'mi_archivo.txt' ;

function abrir_crear($nombreArchivo , $contenidoArchivo ){
    // 'w' sobreescribe 'a' anyade al final
    // fopen abre el archivo
    $archivo = fopen($nombreArchivo , 'w');

        // escribimos en el archivo
    fwrite($archivo , $contenidoArchivo);
    // y lo cerramos
    fclose($archivo);
}

function tamanyo_ultimoMod($nombreArchivo){

    // filsesize() nos da el tamanyo
    $tamanyo = filesize($nombreArchivo);
    // filemtime() nos da la fecha de ultimo modificacion
    $momento = filemtime($nombreArchivo);
    // nuestro formato de visualizar
    $fecha = date("d/m/Y H:i:s", $momento);

    
    echo "El archivo $nombreArchivo ocupa $tamanyo bytes";
    echo "<br/>";
        echo "<br/>";
    echo "La última modificación fue el $fecha";
    echo "<br/>";
        echo "<br/>";
    
}


function Listar($ruta_directorio){
    // scandir() scanea ruta especificada
$lista = scandir($ruta_directorio);
    echo "Lista de archivos en la ruta :" , $ruta_directorio;
    echo "<br/>";

    // un bucle para leer el nombre de cada fichero
    foreach ($lista as $fichero) {
        if (is_file($fichero)) {
            if(strpos($fichero, ".txt")){
                echo $fichero . "<br/>";
            }
            
        }
    }
}

function crear($ruta_directorio , $nombreArchivoNuevo){
    $ruta_Completa = $ruta_directorio . DIRECTORY_SEPARATOR . $nombreArchivoNuevo ;
    if (!is_dir($ruta_Completa)){
      mkdir($ruta_Completa );  
    }
    else {
        echo "El fichero ya existe! ";
    }

}


// function mover ($origen , $destino){
//     if(rename($origen , $destino)){
//         echo  "El archivo movido con exito!";
//     }else{
//         echo " No se ha podido mover el archivo";
//     }
// }



abrir_crear($nombreArchivo , $contenidoArchivo);

tamanyo_ultimoMod($nombreArchivo);

listar($ruta_directorio);

crear($ruta_directorio , "Nuevo");



