<?php
// Bloque principal que gestiona las peticiones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenemos la acción 
    $accion = $_GET['action'] ?? '';

    // CAMPOS DEL CÓMIC
    $id = $_POST['id'] ?? null;
    $titulo = $_POST['titulo'] ?? '';
    $autor = $_POST['autor'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $localizacion = $_POST['localizacion'] ?? '';
    $prestado = $_POST['prestado'] === 'true' ? true : false; 

    // Llamamos a la función CRUD correspondiente
    if ($accion == 'guardar') {
         modificarComic($id, $titulo, $autor, $estado, $prestado, $localizacion);
            volver();
            
    }elseif ($accion == 'eliminar') {
        eliminarComic($id);
        volver();
    }elseif ($accion == 'anadir'){
        anadirComic($titulo, $autor, $estado, $prestado, $localizacion);
        volver();
    }
}

function volver()
{
    // Volvemos a la página principal tras la acción (evita reenviar el formulario)
    header('Location: index.php?ok=1');
    exit();
}

// === FUNCIONES CRUD DE CÓMICS ===

function anadirComic($titulo, $autor, $estado, $prestado, $localizacion)
{
    $comics = cargarJSON('comics.json');
    if ($comics === null) { return false; }

    $id = generarIdComic($comics); // Generamos el ID

    // Creamos el nuevo objeto Comic
    $nuevoComic = (object)['id' => $id, 'titulo' => $titulo, 'autor' => $autor, 'estado' => $estado, 'prestado' => $prestado, 'localizacion' => $localizacion];

    $comics[] = $nuevoComic;

    guardarJSON('comics.json', $comics);
    return true;
}

function modificarComic($id, $titulo, $autor, $estado, $prestado, $localizacion)
{
    $comics = cargarJSON('comics.json');
    if ($comics === null) { return false; }

    foreach ($comics as &$comic) {
        if ($comic->id == $id) {
            $comic->titulo = $titulo;
            $comic->autor = $autor;
            $comic->estado = $estado;
            $comic->prestado = $prestado;
            $comic->localizacion = $localizacion;
            break;
        }
    }
    unset($comic); // Es crucial liberar la referencia

    guardarJSON('comics.json', $comics);
    return true;
}

function eliminarComic($id)
{
    $comics = cargarJSON('comics.json');
    if ($comics === null) { return false; }

    foreach ($comics as $index => $comic) {
        if ($comic->id == $id) {
            unset($comics[$index]); // Elimina el elemento del array
            break; 
        }
    }

    $comics = array_values($comics); // Reindexa el array después de la eliminación

    guardarJSON('comics.json', $comics);
    return true;
}

function listarComics($titulo, $estado)
{
    // Carga los datos desde el JSON
    $comics = cargarJSON('comics.json');

    if ($comics === null) {
        return []; 
    }

    // Aplica filtros de Título y Estado
    $comics = array_filter($comics, function ($comic) use ($titulo, $estado) {
        $valido = true;

        // Filtro por Título
        if ($titulo != '' && str_contains(strtolower($comic->titulo), strtolower($titulo)) == false) {
            $valido = false;
        }
        // Filtro por Estado
        if ($estado != '' && $comic->estado != $estado) {
            $valido = false;
        }
        
        return $valido;
    });

    return $comics;
}

function generarIdComic($comics): int
{
    $id = 0;
    // Obtener el ID máximo
    $maximo = obtenerElementoMaximo($comics, 'id');
    
    // Buscar el primer ID libre
    for ($i = 1; $i <= $maximo; $i++) {
        $resultado = array_filter($comics, function ($comic) use ($i) {
            return $comic->id == $i;
        });
        if (empty($resultado)) {
            $id = $i;
            break;
        }
    }

    // Si no se encontró ID libre, el siguiente es el máximo + 1
    if ($id == 0) {
        $id = $maximo + 1;
    }

    return $id;
}

function obtenerElementoMaximo($datos, $propiedad)
{
    if (empty($datos)) {
        return 0; // Devolver 0 para empezar desde ID 1
    }

    // Usar array_reduce para encontrar el objeto con el valor máximo de la propiedad
    $maximo = array_reduce($datos, function ($a, $b) use ($propiedad) {
        if ($a === null) {
            return $b;
        }
        return ($b->$propiedad > $a->$propiedad) ? $b : $a;
    });

    return $maximo->id;
}

/*
xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
*/

function cargarJSON($ruta)
{
    // Carga el contenido del archivo JSON
    if (!file_exists($ruta)) {
        // Si el archivo no existe, devuelve un array vacío
        return []; 
    }
    $contenido = file_get_contents($ruta);
    $datos = json_decode($contenido);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('Error al decodificar JSON: ' . json_last_error_msg());
        return null;
    }

    return $datos;
}

function guardarJSON($ruta, $datos)
{
    // Guarda el array PHP como una cadena JSON formateada
    $json = json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if (file_put_contents($ruta, $json) === false) {
        error_log("Error: No se pudo escribir en el archivo '$ruta'.");
        return false;
    }
    return true;
}
?>