<?php


// Funcion para obtener y parsear todos los comics del archivo
function obtenerTodosLosComics() {

    if (!file_exists('data.txt')) {
        return [];
    }
    
    $lineas = file('data.txt');
    $comics = [];

    foreach ($lineas as $linea) {
        //separar cada atributo utilizando | 
        $campos = explode('|', trim($linea));
        
        // Formato: ID|Titulo|Autor|Estado|Prestado|Localizacion
        $comics[] = [
            'id' => $campos[0],
            'titulo' => $campos[1],
            'autor' => $campos[2],
            'estado' => $campos[3],
            'prestado' => $campos[4] === 'true', // Convierte 'true'/'false' a booleano
            'localizacion' => $campos[5],
        ];
        
    }
    return $comics;
}

// Función para reescribir todo el archivo después de un cambio
function reescribirArchivo($comics_array) {
    $contenido = '';
    foreach ($comics_array as $comic) {
        $prestado_str = $comic['prestado'] ? 'true' : 'false';
        
        $contenido .= implode('|', [
            $comic['id'],
            $comic['titulo'],
            $comic['autor'],
            $comic['estado'],
            $prestado_str,
            $comic['localizacion']
        ]) . "\n";
    }
    return file_put_contents('data.txt', $contenido, LOCK_EX) !== false;
}

// Función para AGREGAR un cómic (Necesaria para la vista "Agregar")
function agregarComic($titulo, $autor, $estado, $prestado, $localizacion) {
    $id_nuevo = time();
    $prestado_str = $prestado ? 'true' : 'false';
    
    // Aseguramos que 'pendiente' se guarde como 'pendiente de leer'
    $estado_guardar = ($estado === 'pendiente') ? 'pendiente de leer' : $estado;

    $nueva_linea = $id_nuevo . '|' . 
                   $titulo . '|'  . 
                   $autor . '|' . 
                   $estado_guardar . '|'  . 
                   $prestado_str . '|'  . 
                   $localizacion . "\n";
                   
    return file_put_contents('data.txt', $nueva_linea, FILE_APPEND | LOCK_EX) !== false;
}

// Función para ELIMINAR un cómic
function eliminarComic($id) {
    $comics = obtenerTodosLosComics();
    $comics_filtrados = array_filter($comics, function($comic) use ($id) {
        return $comic['id'] != $id;
    });
    
    if (count($comics) !== count($comics_filtrados)) {
        return reescribirArchivo($comics_filtrados);
    }
    return false;
}

// Función para MODIFICAR un cómic (estado, prestado o localización)
function modificarComic($id, $campo, $nuevo_valor) {
    $comics = obtenerTodosLosComics();
    $modificado = false;

    foreach ($comics as $i => $comic) {
        if ($comic['id'] == $id) {
            if ($campo === 'prestado') {
                 $comics[$i]['prestado'] = ($nuevo_valor == '1');
            } else {
                 $comics[$i][$campo] = $nuevo_valor;
            }
            $modificado = true;
            break; 
        }
    }

    if ($modificado) {
        return reescribirArchivo($comics);
    }
    return false;
}

// Función para LISTAR y FILTRAR cómics
function listarComicsFiltrados($filtros = []) {
    $comics = obtenerTodosLosComics();
    
    if (!empty($filtros)) {
        $comics = array_filter($comics, function($comic) use ($filtros) {
            $pasa_filtro = true;

            if (!empty($filtros['q']) && strpos(strtolower($comic['titulo']), strtolower($filtros['q'])) === false) {
                $pasa_filtro = false;
            }
            
            $estado_real = ($filtros['estado'] === 'pendiente') ? 'pendiente de leer' : $filtros['estado'];
            if (!empty($filtros['estado']) && $comic['estado'] !== $estado_real) {
                $pasa_filtro = false;
            }
            
            if (isset($filtros['prestado']) && $filtros['prestado'] !== '') {
                $prestado_buscado = ($filtros['prestado'] === '1');
                if ($comic['prestado'] !== $prestado_buscado) {
                    $pasa_filtro = false;
                }
            }
            
            if (!empty($filtros['localizacion']) && strtolower($comic['localizacion']) !== strtolower($filtros['localizacion'])) {
                $pasa_filtro = false;
            }

            return $pasa_filtro;
        });
    }
    return $comics;
}
?>