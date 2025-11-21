<?php

$archivo_datos = 'tareas.json';

function leer_tareas()
{
    // Comprobar si el archivo existe o si está vacío (evita json_decode en contenido nulo)
    global $archivo_datos;
    if (!file_exists($archivo_datos) || filesize($archivo_datos) == 0) {
        return [];
    }
    // Obtener el contenido del archivo como una cadena
    $contenido = file_get_contents($archivo_datos);

    // Decodificar la cadena JSON a un array asociativo de PHP
    return json_decode($contenido, true);
}

// para guardar tareas
function guardar_tareas($tareas)
{
    global $archivo_datos;
    // Codificar el array de PHP a formato JSON
    $contenido_json = json_encode($tareas, JSON_PRETTY_PRINT);
    // Escribir el contenido JSON en el archivo
    file_put_contents($archivo_datos, $contenido_json);
}

// para calcular el id disponible
function obtener_siguiente_id($tareas)
{
    // Caso base: si no hay tareas, el primer ID es 1
    if (empty($tareas)) {
        return 1;
    }

    // Obtener un array simple con solo los IDs existentes
    $ids_existentes = [];
    foreach ($tareas as $tarea) {
        if (isset($tarea['id'])) {
            $ids_existentes[] = $tarea['id'];
        }
    }

    // Ordenar los IDs para facilitar la búsqueda de huecos
    sort($ids_existentes);

    // Iterar sobre los IDs ordenados para encontrar el primer salto (hueco)
    $siguiente_id = 1;

    foreach ($ids_existentes as $id_actual) {
        // Iterar sobre los IDs ordenados para encontrar el primer salto (hueco)
        if ($id_actual > $siguiente_id) {
            return $siguiente_id;
        }
        // Si no hay hueco, incrementa el ID esperado para la siguiente comprobación
        $siguiente_id++;
    }

    // Si el bucle termina, significa que no hubo huecos, así que el siguiente ID es el último + 1
    return $siguiente_id;
}

//para agregar tareas
function agregar_tarea($descripcion, $estado)
{
    $tareas = leer_tareas();

    $nuevo_id = obtener_siguiente_id($tareas);

    $nueva_tarea = [
        'id' => $nuevo_id,
        'descripcion' => trim($descripcion),
        'estado' => $estado
    ];

    //guarda tareas añadiendo nuevo tarea
    $tareas[] = $nueva_tarea;
    guardar_tareas($tareas);
}

function modificar_estado_tarea($id, $nuevo_estado)
{
    $id = (int) $id;

    $estados_validos = ['pendiente', 'en progreso', 'completada'];

    // 2. Yeni durumun geçerli olup olmadığını kontrol et
    if (!in_array($nuevo_estado, $estados_validos)) {
        return false; // Geçersiz durum, işlemi durdur
    }

    $tareas = leer_tareas();
    $encontrado = false;

    foreach ($tareas as $indice => $tarea) {
        // ID'leri karşılaştır (== tür uyumsuzluğunu tolere eder)
        if ($tarea['id'] == $id) {

            // 3. Durumu güncelle ve döngüyü sonlandır
            $tareas[$indice]['estado'] = $nuevo_estado;
            $encontrado = true;
            break; // Görevi bulduk, döngüden çık
        }
    }

    // Değişikliği JSON dosyasına kaydet
    if ($encontrado) {
        guardar_tareas($tareas);
        return true;
    }
    return false;
}

function eliminar_tarea($id)
{
    $tareas = leer_tareas();
    $tareas_actualizadas = [];
    $encontrado = false;

    foreach ($tareas as $tarea) {
        // si existe añade a tarea actualizada si no salta
        if ($tarea['id'] != $id) {
            $tareas_actualizadas[] = $tarea;
        } else {
            $encontrado = true;
        }
    }

    if ($encontrado) {
        guardar_tareas($tareas_actualizadas);
        return true;
    } else {
        echo "<script>alert('No existe el id!');</script>";
    }
    return false;
}


function listar_todas_las_tareas()
{
    $tareas = leer_tareas();

    // GÖREVLERİ ID'ye göre sıralama (Çözüm burada)
    usort($tareas, function ($a, $b) {
        // ID'leri karşılaştır ve küçükten büyüğe sırala
        return $a['id'] <=> $b['id'];
    });

    return $tareas;
}
