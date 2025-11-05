<?php
require_once 'funciones.php';

// obtener valores que necesitaremos para el filtrado
$ftitulo = $_POST['titulo'] ?? '';
$fautor = $_POST['autor'] ??'';
$festado = $_POST['estado'] ?? '';
$flocalizacion = $_POST['localizacion'] ?? '';
$fprestado = $_POST['prestado'] ?? '';

// obtener la lista de comics filtrada
$comics = listarComics($ftitulo, $fautor, $festado, $fprestado, $flocalizacion);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comics</title>
    <script src="funciones.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Formulario  -->
    
    <form id="myform" name="myform" action="funciones.php" method="post" >
    <input type="hidden" name="id" id="id">
    <input type="hidden" name="titulo" id="titulo" value="<?= $ftitulo ?>">
    <input type="hidden" name="autor" id="autor" value="<?= $fautor ?>">
    <input type="hidden" name="estado" id="estado" value="<?= $festado ?>">
    <input type="hidden" name="localizacion" id="localizacion" value="<?= $flocalizacion ?>">
    <input type="hidden" name="prestado" id="prestado" value="<?= $fprestado ?>">

    <!-- seccion principal -->
    <div id="principal">
        <!-- cabecera -->
        <div id="cabecera" class="grid-6-cols">
            <div>Titulo</div>
            <div>Autor</div>
            <div>Estado</div>
            <div>Localizacion</div>
            <div>Prestado</div>
            <div>Acciones</div>
        </div>

        <!-- seccion filtro -->
        <div id="filtro" class="grid-6-cols" >
            <!-- filtro por titulo , cuando escribe algo usuario empieza a filtrar -->
            <div><input type="text" value="<?= $ftitulo ?>" id="ftitulo" onchange="filtrar()" placeholder="Buscar por Título"></div>
            <div><input type="text" value="<?= $fautor ?>" id="fautor" onchange="filtrar()" placeholder="Buscar por Autor"></div>
             <div>
                    <!-- filtro por estado , cuando interactura el usuario con opciones empieza a filtrar -->
                    <select name="estado" id="festado" onchange="filtrar()">
                        <option value="">--Estado--</option>
                        <!-- en cada opcion comprobamos si esta elegido -->
                        <option value="pendiente de leer" <?= $festado === 'pendiente de leer' ? 'selected' : '' ?>>pendiente</option>
                        <option value="leyendo" <?= $festado === 'leyendo' ? 'selected' : '' ?>>leyendo</option>
                        <option value="leido" <?= $festado === 'leido' ? 'selected' : '' ?>>leído</option>
                   </select>
             </div>
<div>
                <input type="text" value="<?= $flocalizacion ?>" id="flocalizacion" onchange="filtrar()" placeholder="Buscar Localización">
             </div>
             <div><input type="checkbox" id="fprestado" onchange="filtrar()" <?= $fprestado === 'true' ? 'checked' : '' ?> /></div>
            <div></div>

        </div>

        <div class="anadirlinea grid-6-cols filtro">
            <div><input type="text" id="NuevoTitulo" value="" placeholder="Nuevo Titulo"></div>
            <div><input type="text" id="NuevoAutor" value="" placeholder="Autor"></div>
            <div>
                <select id="NuevoEstado">
                    <option value="pendiente de leer">pendiente</option>
                    <option value="leyendo">leyendo</option>
                    <option value="leido">leído</option>
                </select>
            </div>
            <div>
                <select id="NuevoLocalizacion">
                    <option value="estanteria1">estanteria1</option>
                    <option value="estanteria2">estanteria2</option>
                    <option value="mueble">mueble</option>
                </select>
            </div>
            <div><input type="checkbox" id="NuevoPrestado" /></div>
            <div>
                <input type="button" onclick="anadir();" value="Añadir">
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
                        <input type="button" onclick="modificar('<?= $comic->id ?>');" value="Modificar" />
                        <input type="button" onclick="eliminar('<?= $comic->id ?>');" value="Eliminar" />
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