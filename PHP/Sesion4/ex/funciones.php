<?php
// funciones.php

function leerFicheros() {
    $todos = [];

    // 1. JSON
    $rutaJson = 'data/DatosRubricaFinal.json';
    if (file_exists($rutaJson)) {
        $jsonData = json_decode(file_get_contents($rutaJson), true);
        foreach ($jsonData as $item) {
            $todos[] = formarDoc($item['fila'], $item['nombre'], $item['apellidos'], $item['sexo'], $item['es_profe_sexi']);
        }
    }

    // 2. CSV (Separado por ;)
    $rutaCsv = 'data/DatosRubrica4Final.csv';
    if (($h = fopen($rutaCsv, "r")) !== FALSE) {
        fgetcsv($h, 1000, ";"); // Saltar cabecera
        while (($d = fgetcsv($h, 1000, ";")) !== FALSE) {
            // NOMBRE(0);APELLIDOS(1);FILA(2);SEXO(3);ES_PROFE_SEXI(4)
            $todos[] = formarDoc($d[2], $d[0], $d[1], $d[3], $d[4]);
        }
        fclose($h);
    }

    // 3. XML
    $rutaXml = 'data/DatosRubricaFinal.xml';
    if (file_exists($rutaXml)) {
        $xml = simplexml_load_file($rutaXml);
        foreach ($xml->persona as $p) {
            $todos[] = formarDoc($p->fila, $p->nombre, $p->apellidos, $p->sexo, $p->es_profe_sexi);
        }
    }

    return $todos;
}

function formarDoc($n, $nom, $ape, $sex, $sexi) {
    return [
        "Numero" => (int)$n,
        "Alumno" => [
            "Nombre" => (string)$nom,
            "Apellidos" => (string)$ape,
            "Sexo" => (string)$sex,
            "es_profe_sexi" => (bool)$sexi
        ]
    ];
}