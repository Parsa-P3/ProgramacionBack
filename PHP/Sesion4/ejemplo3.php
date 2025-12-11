<?php
$nombre = "ejemplo3.php";
$tamaño = filesize($nombre);
$momento = filemtime($nombre);
$fecha = date("d/m/Y H:i:s", $momento);
echo "El archivo $nombre ocupa $tamaño bytes";
echo "<br/>";
echo "La última modificación fue el $fecha";
?>