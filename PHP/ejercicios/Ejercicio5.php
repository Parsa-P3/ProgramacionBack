<?php
$var1="var1";
$var2="var2";
echo $var1, " texto ", $var2;
echo "<h1>Título</h1><p>Párrafo.</p>";

$nombre = "Elena";
echo "Hola, $nombre.<br>"; // Salida: Hola, Elena.
echo "Línea 1\nLínea 2"; // Interpreta \n (en HTML, no se ve sin<pre> o <br>)

$nombre = "Elena";
echo 'Hola, $nombre.<br>'; // Salida: Hola, $nombre.<br>
echo 'Esto es \n una línea.'; // Salida: Esto es \n una línea.
?>
