<?php
require_once 'datos.php';  // Asegúrate de que 'datos.php' contenga el array de los peliculas correctamente definido.


function OrdenarAlfab($bestMovies){

    // usort para ordenar el array
    usort($bestMovies, function($a, $b){

        return strcasecmp($a['title'], $b['title']);
    });
    return $bestMovies;
}

function MostrarPeliculas($bestmovies, $title) {
    echo "<h2>$title</h2>";
    echo "<ul>";
    foreach ($bestmovies as $pelicula) {
        // Muestra el título y el director
        echo "<li><strong>{$pelicula['title']}</strong> (Director: {$pelicula['director']})</li>";
    }
    echo "</ul>";
}

function Extraer11a15($bestMovies){
    return array_slice($bestMovies,10,5);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
   
    $action = $_POST['action'];

    $bestMovies_ordenadas = OrdenarAlfab($bestMovies);

    if ($action === "ordenar"){    //ordenar el array
    $bestMovies_ordenadas = OrdenarAlfab($bestMovies);
    
    // Luego mostramos la lista ordenada
    MostrarPeliculas($bestMovies_ordenadas, "Lista de Películas Ordenada Alfabéticamente");
}elseif ($action === "extraer_rango"){

    $pelis11a15 = Extraer11a15($bestMovies);
echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><title>Películas Ordenadas</title></head><body>";
    
    
    echo "<hr>";
    
    // Mostramos la nueva variable con el rango extraído
    MostrarPeliculas($pelis11a15, "Películas de la Posición 11 a la 15 (del array ordenado)");
    
    echo "</body></html>";

}elseif ($action === "Lista_Modificada"){
    $bestMovies_ordenadas = OrdenarAlfab($bestMovies);
    $myOpinion = $bestMovies_ordenadas ;
    array_splice($myOpinion,1,1 , [["title" => "Nune" , "director" =>"Javier piñero" , "actor" => "Pedro naranjo"]]);
    MostrarPeliculas($myOpinion, "Lista modificada por mi gusto!");
    
}elseif($action === "Añadir_al_principio_mi_fav"){
    $myOpinion = $bestMovies_ordenadas ;
    
    $mi_favorita = [
        "title" => "L'haine",
        "director" => "Mathieu Kassovitz",
        "actor" => "Vincent Cassel"
    ];

    array_unshift($myOpinion, $mi_favorita);
    MostrarPeliculas($myOpinion, "Listado de peliculas con mi favorita al principio");

}elseif($action === "Añadir_al_final_mi_fav"){
    $myOpinion = $bestMovies_ordenadas ;
    
    $mi_favorita = [
        "title" => "L'haine",
        "director" => "Mathieu Kassovitz",
        "actor" => "Vincent Cassel"
    ];

    array_push($myOpinion, $mi_favorita);
    MostrarPeliculas($myOpinion, "Listado de peliculas con mi favorita al principio");

}
}

