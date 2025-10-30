<?php
require_once 'datos.php';


function MostrarPeliculas($bestMovies, $title) {
    echo "<h2>$title</h2>";
    echo "<ul>";
    foreach ($bestMovies as $pelicula) {
        // Muestra el título y el director
        echo "<li><strong>{$pelicula['title']}</strong> (Director: {$pelicula['director']})</li>";
    }
    echo "</ul>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hollllllllllyywoood</title>
</head>
<body>

    <?php
    MostrarPeliculas($bestMovies, "Lista Inicial de Películas (Sin Ordenar)");
    ?>

    <form action="pelis.php" name="formHollywood" , id="formHollywood" , method="post">
        <input type="hidden" name="action" value="ordenar">
        <button type="submit" id="btanio">Ordenar Alfabéticamente</button>
    </form>
<br>
    <form action="pelis.php" method="post" >
        <input type="hidden" name="action" value="extraer_rango">
        <button type="submit">Mostrar solo Películas 11 a 15</button>
    </form>
<br>
    <form action="pelis.php" method="post" >
        <input type="hidden" name="action" value="Lista_Modificada">
        <button type="submit">Copia de array eliminar El padrino y Añadir El padrino 2</button>
    </form>
    <br>
    <form action="pelis.php" method="post" >
        <input type="hidden" name="action" value="Añadir_al_principio_mi_fav">
        <button type="submit">Añadir al principio mi fav</button>
    </form>
   
    <br>
    <form action="pelis.php" method="post" >
        <input type="hidden" name="action" value="Añadir_al_final_mi_fav">
        <button type="submit">Añadir al final mi fav</button>
    </form>
   
   


</body>
</html>
