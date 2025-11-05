<?php
// gestionar las peticiones POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // obtener la accion
    $accion = $_GET["accion"] ?? '';

    // campos de comic
    $id = $_GET['id'] ?? null;
    $titulo = $_POST['titulo'] ?? '';
    $autor = $_POST['autor'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $localizacion = $_POST['localizacion'] ?? '';
    $prestado = $_POST['prestado'] === 'true' ? true : false;

        // Llamamos a la función CRUD correspondiente
    if($accion == 'modificar'){
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

// ---- funciones principales de la aplicacion


// function anadirComic(titulo, autor, estado , localizacion, prestado)

function anadirComic($titulo, $autor, $estado, $localizacion, $prestado)
{
    // cargamos nuestro dato guardado en json de comics
    $comics = cargarJSON('comics.json');
    if ($comics == '') {
        return false;
    }

    // generar nuevo id
    $id = generarIdComic($comics);

    // crear nuevo comic
    $nuevoComic = (object) [
        'id' => $id,
        'titulo' => $titulo,
        'autor' => $autor,
        'estado' => $estado,
        'localizacion' => $localizacion,
        'prestado' => $prestado
    ];

    // lo asignamos a nuestro array de comics
    $comics[] = $nuevoComic;

    // guardamos el json actualizado
    guardarJSON('comics.json', $comics);
    return true;
}


// function listarComics(titulo, autor)
function listarComics($titulo, $autor , $estado, $prestado , $localizacion)
{
    // cargar lista de comics
    $comics = cargarJSON('comics.json');
    if ($comics == '') {
        return [];
    }

    // Corregimos la función anónima para que use TODOS los parámetros de filtrado
    $comicsFiltrados = array_filter($comics, function ($comic) use ($titulo, $autor, $estado, $prestado, $localizacion) {
        
        // 1. Coincidencia de Título (Parcial e insensible a mayúsculas/minúsculas)
        $coincideTitulo = empty($titulo) || stripos($comic->titulo, $titulo) !== false;
        
        // 2. Coincidencia de Autor (Parcial e insensible a mayúsculas/minúsculas)
        $coincideAutor = empty($autor) || stripos($comic->autor, $autor) !== false;
        
        // 3. Coincidencia de Estado (Exacta)
        $coincideEstado = empty($estado) || ($comic->estado === $estado); 

        // 4. Coincidencia de Localización (Parcial e insensible a mayúsculas/minúsculas)
        $coincideLocalizacion = empty($localizacion) || stripos($comic->localizacion, $localizacion) !== false;

        // 5. Coincidencia de Prestado
        // $prestado viene como "true" o "" (cadena vacía) desde el JS/POST.
        // Si $prestado es "true", el cómic debe tener $comic->prestado == true.
        // Si $prestado es "" (vacío), no se aplica el filtro.
        $coincidePrestado = empty($prestado) || ($comic->prestado === true);
        
        // Devolver true solo si el cómic coincide con TODOS los filtros
        return $coincideTitulo && $coincideAutor && $coincideEstado && $coincideLocalizacion && $coincidePrestado;
    });

    // devolver lista filtrada
    return array_values($comicsFiltrados);
}

// function eliminarComic (id)

// ?? hay que mejorar y comprobar
function eliminarComic($id)
{
    // cargar lista de comics
    $comics = cargarJSON('comics.json');
    if ($comics == '') {
        return false;
    }

    // filtrar para eliminar el comic con id dado
    $comicsFiltrados = array_filter($comics, function ($comic) use ($id) {
        return $comic->id != $id;
    });

    // guardar la lista actualizada
    guardarJSON('comics.json', array_values($comicsFiltrados));
    return true;
}


// function editarComic(id, titulo, autor, editorial, anio, numero, imagen)
function modificarComic($id, $titulo, $autor, $estado, $localizacion, $prestado)
{
    // cargar lista de comics
    $comics = cargarJSON('comics.json');
    if ($comics == '') {
        return false;
    }

    // buscar y modificar el comic con id dado
    // & ???????????
    foreach ($comics as &$comic) {
        if ($comic->id == $id) {
            $comic->titulo = $titulo;
            $comic->autor = $autor;
            $comic->estado = $estado;
            $comic->localizacion = $localizacion;
            $comic->prestado = $prestado;
            break;
        }
    }

    // guardar la lista actualizada
    guardarJSON('comics.json', $comics);
    return true;
}

// ---- funciones necesarias para el funcionamiento interno de la aplicacion

// function generarIdComic()

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

// function obtenerElementoMaximo( v1 , v2)

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


// function cargarJsonComics()

function cargarJSON($ruta)
{
    // carga el contenido de archivo json y si no existe devuelve array vacio
    if (!file_exists($ruta)) {
        return [];
    }

    // leer el archivo json
    $jsonData = file_get_contents($ruta);
    // decodificar el json a objeto php
    $data = json_decode($jsonData);

    return $data;
}

// function guardarJsonComics( listaComics )
function guardarJSON($ruta, $datos)
{
    // Guarda el array PHP como una cadena JSON formateada
    $json = json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    // guardar en el archivo
    if (file_put_contents($ruta, $json) === false) {
        error_log("Error: No se pudo escribir en el archivo '$ruta'.");
        return false;
    }
    return true;
}




// function volver()
function volver()
{
    header("Location: index.php?ok=1");
    exit();
}
// function obtenerComic(id)