<?php
// Archivo donde se guardan los cómics
$archivo = "comics.txt";

// Función para leer todos los cómics
function leerComics($archivo) {
    if (!file_exists($archivo)) return [];
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES);
    $comics = [];
    foreach ($lineas as $linea) {
        list($titulo, $autor, $estado, $prestado, $localizacion) = explode("|", $linea);
        $comics[] = [
            'titulo' => $titulo,
            'autor' => $autor,
            'estado' => $estado,
            'prestado' => $prestado,
            'localizacion' => $localizacion
        ];
    }
    return $comics;
}

// Función para guardar cómics
function guardarComics($archivo, $comics) {
    $lineas = [];
    foreach ($comics as $c) {
        $lineas[] = implode("|", [$c['titulo'], $c['autor'], $c['estado'], $c['prestado'], $c['localizacion']]);
    }
    file_put_contents($archivo, implode("\n", $lineas));
}

// Agregar nuevo cómic
if (isset($_POST['agregar'])) {
    $nuevo = [
        'titulo' => $_POST['titulo'],
        'autor' => $_POST['autor'],
        'estado' => $_POST['estado'],
        'prestado' => isset($_POST['prestado']) ? 'si' : 'no',
        'localizacion' => $_POST['localizacion']
    ];
    $comics = leerComics($archivo);
    $comics[] = $nuevo;
    guardarComics($archivo, $comics);
    echo "<p>✅ Cómic agregado.</p>";
}

// Eliminar cómic
if (isset($_POST['eliminar'])) {
    $titulo = $_POST['titulo_eliminar'];
    $comics = leerComics($archivo);
    $comics = array_filter($comics, fn($c) => $c['titulo'] !== $titulo);
    guardarComics($archivo, $comics);
    echo "<p>🗑️ Cómic eliminado.</p>";
}

// Cambiar estado
if (isset($_POST['cambiar'])) {
    $titulo = $_POST['titulo_cambiar'];
    $nuevoEstado = $_POST['nuevo_estado'];
    $comics = leerComics($archivo);
    foreach ($comics as &$c) {
        if ($c['titulo'] === $titulo) {
            $c['estado'] = $nuevoEstado;
        }
    }
    guardarComics($archivo, $comics);
    echo "<p>🔄 Estado actualizado.</p>";
}

// Mostrar lista
$comics = leerComics($archivo);
?>

<h2>Agregar cómic</h2>
<form method="post">
    Título: <input type="text" name="titulo" required><br>
    Autor: <input type="text" name="autor" required><br>
    Estado: 
    <select name="estado">
        <option value="pendiente">Pendiente</option>
        <option value="leyendo">Leyendo</option>
        <option value="leído">Leído</option>
    </select><br>
    Prestado: <input type="checkbox" name="prestado"> Sí<br>
    Localización:
    <select name="localizacion">
        <option value="estanteria1">Estantería 1</option>
        <option value="estanteria2">Estantería 2</option>
        <option value="mueble">Mueble</option>
    </select><br>
    <button name="agregar">Agregar</button>
</form>



<h2>Lista de cómics</h2>
<table border="1" cellpadding="5">
<tr><th>Título</th><th>Autor</th><th>Estado</th><th>Prestado</th><th>Localización</th></tr>
<?php foreach ($comics as $c): ?>
<tr>
    <td><?= htmlspecialchars($c['titulo']) ?></td>
    <td><?= htmlspecialchars($c['autor']) ?></td>
    <td><?= htmlspecialchars($c['estado']) ?></td>
    <td><?= htmlspecialchars($c['prestado']) ?></td>
    <td><?= htmlspecialchars($c['localizacion']) ?></td>
</tr>
<?php endforeach; ?>
</table>



<h2>Eliminar cómic</h2>
<form method="post">
    Título: <input type="text" name="titulo_eliminar" required>
    <button name="eliminar">Eliminar</button>
</form>

<h2>Cambiar estado de un cómic</h2>
<form method="post">
    Título: <input type="text" name="titulo_cambiar" required><br>
    Nuevo estado:
    <select name="nuevo_estado">
        <option value="pendiente">Pendiente</option>
        <option value="leyendo">Leyendo</option>
        <option value="leído">Leído</option>
    </select>
    <button name="cambiar">Cambiar</button>
</form>
