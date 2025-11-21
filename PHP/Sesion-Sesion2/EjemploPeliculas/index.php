<?php
// === CONFIGURACIÓN Y PREPARACIÓN DE DATOS ===
require_once 'funciones.php'; // Incluye las funciones.

// Obtener valores de filtro
$filTitulo = $_POST['titulo'] ?? ''; 
$filGenero = $_POST['genero'] ?? ''; // Nuevo filtro

// Obtener la lista filtrada de películas
$peliculas = listarPeliculas($filTitulo, $filGenero); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Colección de Películas</title>
    <script src="funciones.js"></script> 
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <form id="frm" name="frm" action="funciones.php" method="post">
        <input type="hidden" id="titulo" name="titulo" value="<?= $filTitulo ?>">
        <input type="hidden" id="genero" name="genero" value="<?= $filGenero ?>">
        <input type="hidden" id="id" name="id">
        <input type="hidden" id="director" name="director">
        <input type="hidden" id="plataforma" name="plataforma">
        <input type="hidden" id="vista" name="vista">

        <div id="principal">
            <div id="cabecera" class="grid-6-cols">
                <div>Título</div>
                <div>Director</div>
                <div>Género</div>
                <div>Plataforma</div>
                <div>Vista</div>
                <div>Botonera</div>
            </div>

            <div id="filtro" class="grid-6-cols">
                <div><input type="text" value="<?= $filTitulo ?>" id="filTitulo" onchange="filtrar()" placeholder="Buscar por Título"></div>
                <div></div> 
                <div>
                    <select id="filGenero" onchange="filtrar()">
                        <option value="">-- Todos --</option>
                        <option value="accion" <?= $filGenero === 'accion' ? 'selected' : '' ?>>Acción</option>
                        <option value="drama" <?= $filGenero === 'drama' ? 'selected' : '' ?>>Drama</option>
                        <option value="comedia" <?= $filGenero === 'comedia' ? 'selected' : '' ?>>Comedia</option>
                    </select>
                </div>
                <div></div> 
                <div></div> 
                <div></div> 
            </div>
            
            <div class="row-add grid-6-cols">
                <div><input type="text" id="titulo0" value="" placeholder="Nuevo Título" /></div>
                <div><input type="text" id="director0" value="" placeholder="Director" /></div>
                <div>
                    <select id="genero0">
                        <option value="accion">Acción</option>
                        <option value="drama">Drama</option>
                        <option value="comedia">Comedia</option>
                    </select>
                </div>
                <div><input type="text" id="plataforma0" value="" placeholder="Netflix/HBO/DVD" /></div>
                <div><input type="checkbox" id="vista0" /></div>
                <div>
                    <input type="button" onclick="anadir();" value="ADD" />
                </div>
            </div>

            <div id="listado">
                <?php
                foreach ($peliculas as $pelicula) { 
                    ?>
                <div class="row grid-6-cols">
                    <div><input type="text" id="titulo<?= $pelicula->id ?>" value="<?= htmlspecialchars($pelicula->titulo) ?>" /></div>
                    <div><input type="text" id="director<?= $pelicula->id ?>" value="<?= htmlspecialchars($pelicula->director) ?>" /></div>
                    <div> 
                        <select id="genero<?= $pelicula->id ?>">
                            <option value="accion" <?= $pelicula->genero === 'accion' ? 'selected' : '' ?>>Acción</option>
                            <option value="drama" <?= $pelicula->genero === 'drama' ? 'selected' : '' ?>>Drama</option>
                            <option value="comedia" <?= $pelicula->genero === 'comedia' ? 'selected' : '' ?>>Comedia</option>
                        </select>
                    </div>
                    <div><input type="text" id="plataforma<?= $pelicula->id ?>" value="<?= htmlspecialchars($pelicula->plataforma) ?>" /></div>
                    <div><input type="checkbox" id="vista<?= $pelicula->id ?>" <?= $pelicula->vista ? 'checked' : '' ?> /></div>
                    <div>
                        <input type="button" onclick="modificar('<?= $pelicula->id ?>');" value="MOD" />
                        <input type="button" onclick="eliminar('<?= $pelicula->id ?>');" value="DEL" />
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </form>
</body>
</html>