<?php
require_once 'datos.php';

function filtrarNaves($sw) {
    $moreThan100 = [];
    $lessThan100 = [];

    foreach ($sw['results'] as $nave) {
        $pasajeros = $nave['passengers'];

        if (is_numeric($pasajeros)) {
            $numPasajeros = (int)$pasajeros;

            if ($numPasajeros > 100) {
                $moreThan100[] = [
                    'name' => $nave['name'],
                    'model' => $nave['model']
                ];
            } else {
                $lessThan100[] = [
                    'name' => $nave['name'],
                    'model' => $nave['model']
                ];
            }
        } else {
            $lessThan100[] = [
                'name' => $nave['name'],
                'model' => $nave['model']
            ];
        }
    }

    return [
        'moreThan100' => $moreThan100,
        'lessThan100' => $lessThan100
    ];
}
