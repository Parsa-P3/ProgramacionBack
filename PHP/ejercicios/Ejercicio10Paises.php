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

//  Ordenar los países alfabéticamente por nombre
usort($paises, function($a, $b){
    return strcmp($a->nombre, $b->nombre);
});

//  Crear la tabla
$tabla = "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width:80%; margin:auto; text-align:center;'>";
$tabla .= "<tr style='background-color:#333; color:white;'>
              <th>Nombre del país</th>
              <th>Habitantes</th>
              <th>Superficie (km²)</th>
          </tr>";

//  Recorrer los países y crear las filas
foreach($paises as $pais){
    $nombre = $pais->nombre ?? "";
    $habitantes = $pais->habitantes ?? 0;
    $superficie = $pais->superficie ?? 0;

    $colorHabitantes = ($habitantes > 100000000) ? "red" : "grey";
// number_format muestra los números con puntos de miles y sin decimales.
    $tabla .= "<tr>
                  <td>" . htmlspecialchars($nombre) . "</td>
                  <td style='border:2px solid $colorHabitantes;'>" . number_format($habitantes, 0, ',', '.') . "</td>
                  <td>" . number_format($superficie, 0, ',', '.') . "</td>
               </tr>";
}

$tabla .= "</table>";

echo $tabla;

?>