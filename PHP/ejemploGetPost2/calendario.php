<?php

    require_once 'datos.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['anio']) && !$_POST == "") {
        $anio = $_POST['anio'];
    }}

    global $Meses;
    global $diasSemana;

    $numMes = 1 ;
    echo "<h1> $anio </h1>";

    foreach ($Meses as $mes){
        $diaSemana = "";
         for ($i = 1; $i <= ($mes['dias']); $i++){
            $date = $anio.'-'.$numMes.'-'.$i;
            $diaSemana = diaDeLaSemana($date);
            echo "$diaSemana $i de {$mes['nombre']} de $anio <br>";
         }
         $numMes++;
    
        }
    
?>