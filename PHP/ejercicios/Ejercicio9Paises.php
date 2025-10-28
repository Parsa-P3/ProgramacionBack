<?php
$paises = [
    (object)[ "nombre" => "China", "habitantes" => 1410470000, "superficie" => 9596961 ],
    (object)[ "nombre" => "India", "habitantes" => 1382600000, "superficie" => 3287263 ],
    (object)[ "nombre" => "Estados Unidos", "habitantes" => 339000000, "superficie" => 9833517 ],
    (object)[ "nombre" => "Indonesia", "habitantes" => 275500000, "superficie" => 1904569 ],
    (object)[ "nombre" => "Pakistán", "habitantes" => 240500000, "superficie" => 770880 ],
    (object)[ "nombre" => "Brazil", "habitantes" => 215000000, "superficie" => 8515770 ],
    (object)[ "nombre" => "Nigeria", "habitantes" => 224000000, "superficie" => 923768 ],
    (object)[ "nombre" => "Bangladesh", "habitantes" => 171000000, "superficie" => 147570 ],
    (object)[ "nombre" => "Rusia", "habitantes" => 146000000, "superficie" => 17098242 ],
    (object)[ "nombre" => "México", "habitantes" => 133000000, "superficie" => 1964375 ],
    (object)[ "nombre" => "Japón", "habitantes" => 124000000, "superficie" => 377975 ],
    (object)[ "nombre" => "Etiopía", "habitantes" => 120000000, "superficie" => 1104300 ],
    (object)[ "nombre" => "Filipinas", "habitantes" => 116000000, "superficie" => 300000 ],
    (object)[ "nombre" => "Egipto", "habitantes" => 110000000, "superficie" => 1002450 ],
    (object)[ "nombre" => "Vietnam", "habitantes" => 103000000, "superficie" => 331212 ],
    (object)[ "nombre" => "República Democrática del Congo", "habitantes" => 110000000, "superficie" => 2344858 ],
    (object)[ "nombre" => "Irán", "habitantes" => 86000000, "superficie" => 1648195 ],
    (object)[ "nombre" => "Turquía", "habitantes" => 86000000, "superficie" => 783562 ],
    (object)[ "nombre" => "Alemania", "habitantes" => 84000000, "superficie" => 357022 ],
    (object)[ "nombre" => "Tailandia", "habitantes" => 70000000, "superficie" => 510890 ]
];


$tabla = "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width:60%; margin:auto; text-align:center;'>";
$tabla .= "<tr style='background-color:#333; color:white;'>
              <th>Nombre del país</th>
              <th>Habitantes</th>
              <th>Superficie (km²)</th>
              <th>3er carácter desde la derecha</th>
          </tr>";


foreach ($paises as $pais) {

    // Pintamos el nombre del país
    // isset($pais->nombre) comprueba si la propiedad nombre existe y no es null.
    $nombre = isset($pais->nombre) ? $pais->nombre : "";
    // echo "<b>Nombre del país:</b> " .$nombre . "<br>";
    //error_log() manda el mensaje al log del servidor, como si fuera un console.log() de JavaScript. No se ve en el navegador, pero sirve para depurar.
    error_log("Nombre del país: " . $nombre); // Similar a console.log

    $tamanyo = ";";
    // Comprobamos si el país tiene más de 10M de habitantes 
    // $pais->habitantes varsa ve null değilse, o değeri alır.
    $num_habitantes = isset($pais->habitantes)? $pais->habitantes : 0;
    if ($num_habitantes > 10000000) {
        $tamanyo = "Es un país grande<br>";
    } else {
        $tamanyo = "Es un país pequeñito<br>";
    }

    // Extraemos el tercer carácter empezando por la derecha de la superficie
    $superficie = isset($pais->superficie) ? $pais->superficie : 0;
    $sSuperficie = (string)$superficie;
    // strlen da longitud de String
    $len_ssuperficie = strlen($sSuperficie);

    $sCaracter = "";
    if ($len_ssuperficie >= 3) {
        // Extraemos el carácter que está en la posición -3
        // subtr(String , dondeVaEmpezar , CuantosCaracteresVaCoger)
        $sCaracter = substr($sSuperficie, -3, 1);
        // echo "sCaracter: " . $sCaracter . "<br>";
    }

        // Añadir una fila a la tabla
    $tabla .= "<tr>
                  <td>" . htmlspecialchars($nombre) . "</td>
                  <td>" . number_format($num_habitantes, 0, ',', '.') . "</td>
                  <td>" . number_format($superficie, 0, ',', '.') . "</td>
                  <td>" . $sCaracter . "</td>
               </tr>";



}
$tabla .= "</table>";

echo $tabla;
?>