<?php

// Bloque principal que gestiona las peticiones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenemos la acción del query string
    $accion = $_GET['action'] ?? '';

    // CAMPOS DE PELÍCULA (Adaptados desde el modelo de cómic)
    $id = $_POST['id'] ?? null;
    $titulo = $_POST['titulo'] ?? '';
    $director = $_POST['director'] ?? ''; // Antes: autor
    $genero = $_POST['genero'] ?? ''; // Antes: estado
    $plataforma = $_POST['plataforma'] ?? ''; // Antes: localizacion
    // Recibimos 'true' o 'false' como string desde JS (Antes: prestado)
    $vista = $_POST['vista'] === 'true' ? true : false; 

    // Llamamos a la función CRUD correspondiente
    switch ($accion) {
        case 'guardar':
            modificarPelicula($id, $titulo, $director, $genero, $vista, $plataforma);
            volver();
            break;
        case 'eliminar':
            eliminarPelicula($id);
            volver();
            break;
        case 'anadir':
            anadirPelicula($titulo, $director, $genero, $vista, $plataforma);
            volver();
            break;

        default:
    }
}

function volver()
{
    // Volvemos a la página principal tras la acción (evita reenviar el formulario)
    header('Location: index.php?ok=1');
    exit();
}

// === FUNCIONES CRUD DE PELÍCULAS ===

function anadirPelicula($titulo, $director, $genero, $vista, $plataforma)
{
    $peliculas = cargarJSON('peliculas.json');
    if ($peliculas === null) { return false; }

    $id = generarIdPelicula($peliculas); // Generamos el ID

    // Creamos el nuevo objeto Pelicula
    $nuevaPelicula = (object)['id' => $id, 'titulo' => $titulo, 'director' => $director, 'genero' => $genero, 'vista' => $vista, 'plataforma' => $plataforma];

    $peliculas[] = $nuevaPelicula;

    guardarJSON('peliculas.json', $peliculas);
    return true;
}

function modificarPelicula($id, $titulo, $director, $genero, $vista, $plataforma)
{
    $peliculas = cargarJSON('peliculas.json');
    if ($peliculas === null) { return false; }

    foreach ($peliculas as &$pelicula) {
        if ($pelicula->id == $id) {
            $pelicula->titulo = $titulo;
            $pelicula->director = $director;
            $pelicula->genero = $genero;
            $pelicula->vista = $vista;
            $pelicula->plataforma = $plataforma;
            break;
        }
    }
    unset($pelicula); 

    guardarJSON('peliculas.json', $peliculas);
    return true;
}

function eliminarPelicula($id)
{
    $peliculas = cargarJSON('peliculas.json');
    if ($peliculas === null) { return false; }

    foreach ($peliculas as $index => $pelicula) {
        if ($pelicula->id == $id) {
            unset($peliculas[$index]); // Elimina el elemento del array
            break; 
        }
    }

    $peliculas = array_values($peliculas); // Reindexa el array

    guardarJSON('peliculas.json', $peliculas);
    return true;
}

function listarPeliculas($titulo, $genero)
{
    // Carga los datos desde el JSON
    $peliculas = cargarJSON('peliculas.json');

    if ($peliculas === null) {
        return []; 
    }

    // Aplica filtros de Título y Género
    $peliculas = array_filter($peliculas, function ($pelicula) use ($titulo, $genero) {
        $valido = true;

        // Filtro por Título
        if ($titulo != '' && str_contains(strtolower($pelicula->titulo), strtolower($titulo)) == false) {
            $valido = false;
        }
        // Filtro por Género
        if ($genero != '' && $pelicula->genero != $genero) {
            $valido = false;
        }
        
        return $valido;
    });

    return $peliculas;
}

// === FUNCIONES AUXILIARES ===

function generarIdPelicula($peliculas): int
{
    $id = 0;
    // Obtener el ID máximo
    $maximo = obtenerElementoMaximo($peliculas, 'id');
    
    // Buscar el primer ID libre
    for ($i = 1; $i <= $maximo; $i++) {
        $resultado = array_filter($peliculas, function ($pelicula) use ($i) {
            return $pelicula->id == $i;
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


function cargarJSON($ruta)
{
    // Carga el contenido del archivo JSON
    if (!file_exists($ruta)) {
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