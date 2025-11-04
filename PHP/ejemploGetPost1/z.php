<?php
// === CONFIGURACIÓN Y PREPARACIÓN DE DATOS ===
require_once 'funciones.php'; // Incluye las funciones.

// Obtener valores de filtro enviados por POST
$filTitulo = $_POST['titulo'] ?? ''; 
$filEstado = $_POST['estado'] ?? ''; 

// Obtener la lista filtrada de cómics
$comics = listarComics($filTitulo, $filEstado); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Colección de Cómics</title>
    <script src="funciones.js"></script> 
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <form id="frm" name="frm" action="funciones.php" method="post">
        <input type="hidden" id="titulo" name="titulo" value="<?= $filTitulo ?>">
        <input type="hidden" id="estado" name="estado" value="<?= $filEstado ?>">
        <input type="hidden" id="id" name="id">
        <input type="hidden" id="autor" name="autor">
        <input type="hidden" id="localizacion" name="localizacion">
        <input type="hidden" id="prestado" name="prestado">

        <div id="principal">
            <div id="cabecera" class="grid-6-cols">
                <div>Título</div>
                <div>Autor</div>
                <div>Estado</div>
                <div>Localización</div>
                <div>Prestado</div>
                <div>Botonera</div>
            </div>

            <div id="filtro" class="grid-6-cols">
                <div><input type="text" value="<?= $filTitulo ?>" id="filTitulo" onchange="filtrar()" placeholder="Filtrar Título"></div>
                <div></div> 
                <div>
                    <select id="filEstado" onchange="filtrar()">
                        <option value="">-- Todos --</option>
                        <option value="pendiente de leer" <?= $filEstado === 'pendiente de leer' ? 'selected' : '' ?>>pendiente</option>
                        <option value="leyendo" <?= $filEstado === 'leyendo' ? 'selected' : '' ?>>leyendo</option>
                        <option value="leido" <?= $filEstado === 'leido' ? 'selected' : '' ?>>leído</option>
                    </select>
                </div>
                <div></div> 
                <div></div> 
                <div></div> 
            </div>
            
            <div class="row-add grid-6-cols">
                <div><input type="text" id="titulo0" value="" placeholder="Nuevo Título" /></div>
                <div><input type="text" id="autor0" value="" placeholder="Autor" /></div>
                <div>
                    <select id="estado0">
                        <option value="pendiente de leer">pendiente</option>
                        <option value="leyendo">leyendo</option>
                        <option value="leido">leído</option>
                    </select>
                </div>
                <div><input type="text" id="localizacion0" value="" placeholder="Estante/Ubicación" /></div>
                <div><input type="checkbox" id="prestado0" /></div>
                <div>
                    <input type="button" onclick="anadir();" value="ADD" />
                </div>
            </div>

            <div id="listado">
                <?php
                foreach ($comics as $comic) { 
                    ?>
                <div class="row grid-6-cols">
                    <div><input type="text" id="titulo<?= $comic->id ?>" value="<?= htmlspecialchars($comic->titulo) ?>" /></div>
                    <div><input type="text" id="autor<?= $comic->id ?>" value="<?= htmlspecialchars($comic->autor) ?>" /></div>
                    <div> 
                        <select id="estado<?= $comic->id ?>">
                            <option value="pendiente de leer" <?= $comic->estado === 'pendiente de leer' ? 'selected' : '' ?>>pendiente</option>
                            <option value="leyendo" <?= $comic->estado === 'leyendo' ? 'selected' : '' ?>>leyendo</option>
                            <option value="leido" <?= $comic->estado === 'leido' ? 'selected' : '' ?>>leído</option>
                        </select>
                    </div>
                    <div><input type="text" id="localizacion<?= $comic->id ?>" value="<?= htmlspecialchars($comic->localizacion) ?>" /></div>
                    <div><input type="checkbox" id="prestado<?= $comic->id ?>" <?= $comic->prestado ? 'checked' : '' ?> /></div>
                    <div>
                        <input type="button" onclick="modificar('<?= $comic->id ?>');" value="MOD" />
                        <input type="button" onclick="eliminar('<?= $comic->id ?>');" value="DEL" />
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