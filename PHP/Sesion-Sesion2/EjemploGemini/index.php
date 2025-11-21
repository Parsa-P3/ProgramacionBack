<?php
// === INCLUIR FUNCIONES ===
require_once 'funciones.php';

// === LÓGICA DE INTERACCIÓN ===

$mensaje_accion = '';

// 1. Manejar Eliminación
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id'])) {
    if (eliminarComic($_GET['id'])) {
        $mensaje_accion = '<p style="color:green;">✅ Cómic eliminado correctamente.</p>';
    } else {
        $mensaje_accion = '<p style="color:red;">❌ Error al intentar eliminar el cómic.</p>';
    }
}

// 2. Manejar Modificación (Estado/Préstamo)
if (isset($_GET['accion']) && $_GET['accion'] === 'modificar' && isset($_GET['id']) && isset($_GET['campo']) && isset($_GET['valor'])) {
    $campo = $_GET['campo'];
    $valor = $_GET['valor'];
    
    // Aquí solo necesitamos asegurarnos de que el valor sea correcto, la función lo ajusta si es estado
    if (modificarComic($_GET['id'], $campo, $valor)) {
        $mensaje_accion = '<p style="color:green;">✅ Cómic actualizado correctamente.</p>';
    } else {
        $mensaje_accion = '<p style="color:red;">❌ Error al actualizar el cómic.</p>';
    }
}

// 3. Manejar Adición (si estamos en la vista de agregar y se envió el formulario)
if (isset($_GET['vista']) && $_GET['vista'] === 'agregar' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
    $titulo = $_POST['titulo'] ?? '';
    $autor = $_POST['autor'] ?? '';
    $estado = $_POST['estado'] ?? 'pendiente'; // Viene del select
    $prestado = isset($_POST['prestado']); // Checkbox
    $localizacion = $_POST['localizacion'] ?? 'estanteria1';

    if (agregarComic($titulo, $autor, $estado, $prestado, $localizacion)) {
        $mensaje_accion = '<p style="color:green;">✅ Cómic "'.htmlspecialchars($titulo).'" agregado correctamente.</p>';
    } else {
        $mensaje_accion = '<p style="color:red;">❌ Error al agregar el cómic.</p>';
    }
}


// 4. Obtener Cómics para Mostrar
$filtros = [
    'q' => $_GET['q'] ?? '',
    'estado' => $_GET['estado'] ?? '',
    'prestado' => $_GET['prestado'] ?? '',
    'localizacion' => $_GET['localizacion'] ?? '',
];

$comics_mostrados = listarComicsFiltrados($filtros);

// === VISTA HTML ===
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Colección de Cómics - Lista</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="style.css"> 
    <style>
        /* Estilos básicos */
        body { font-family: sans-serif; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .comic-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .comic-table th, .comic-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <header class="site-header">
        <h1>Colección de Cómics</h1>
        <nav class="menu">
            <a href="index.php">Lista</a>
            <a href="index.php?vista=agregar">Agregar cómic</a>
        </nav>
    </header>

    <main class="container">
        
        <?php echo $mensaje_accion; // Muestra el mensaje de éxito/error ?>
        
        <?php if (isset($_GET['vista']) && $_GET['vista'] === 'agregar'): ?>
            
            <h2>➕ Agregar Nuevo Cómic</h2>
            <form method="post" action="index.php?vista=agregar">
                <label>Título: <input type="text" name="titulo" required></label><br><br>
                <label>Autor: <input type="text" name="autor" required></label><br><br>
                <label>Estado:
                    <select name="estado">
                        <option value="pendiente">Pendiente de leer</option>
                        <option value="leyendo">Leyendo</option>
                        <option value="leido">Leído</option>
                    </select>
                </label><br><br>
                <label>Prestado: <input type="checkbox" name="prestado" value="1"></label><br><br>
                <label>Localización: <input type="text" name="localizacion" placeholder="estanteria1" required></label><br><br>
                <button type="submit">Guardar Cómic</button>
                <a href="index.php">Volver a la Lista</a>
            </form>

        <?php else: // Vista de Listado ?>
        
            <section class="filters">
                <h2>🔍 Filtros</h2>
                <form method="get" action="index.php" class="filter-form">
                    
                    <label>Buscar título:
                        <input type="text" name="q" value="<?php echo htmlspecialchars($filtros['q']); ?>">
                    </label>

                    <label>Estado:
                        <select name="estado">
                            <option value="">--</option>
                            <?php 
                            $estados_ui = ['pendiente', 'leyendo', 'leido'];
                            foreach ($estados_ui as $est): ?>
                                <option value="<?php echo $est; ?>" <?php echo ($filtros['estado'] === $est) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($est); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label>Prestado:
                        <select name="prestado">
                            <option value="">--</option>
                            <option value="1" <?php echo (isset($filtros['prestado']) && $filtros['prestado'] === '1') ? 'selected' : ''; ?>>Sí</option>
                            <option value="0" <?php echo (isset($filtros['prestado']) && $filtros['prestado'] === '0') ? 'selected' : ''; ?>>No</option>
                        </select>
                    </label>

                    <label>Localización:
                        <input type="text" name="localizacion" placeholder="estanteria1" value="<?php echo htmlspecialchars($filtros['localizacion']); ?>">
                    </label>

                    <div class="filter-actions">
                        <button type="submit">Filtrar</button>
                        <a class="clear" href="index.php">Limpiar</a>
                    </div>
                </form>
            </section>

            <section class="listado">
                <h2>📚 Listado de Cómics (<?php echo count($comics_mostrados); ?>)</h2>
                <table class="comic-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Estado</th>
                            <th>Prestado</th>
                            <th>Localización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($comics_mostrados)): ?>
                            <tr>
                                <td colspan="7">No se encontraron cómics.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($comics_mostrados as $comic): 
                                $estado_ui = ($comic['estado'] === 'pendiente de leer') ? 'pendiente' : $comic['estado'];
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($comic['id']); ?></td>
                                    <td><?php echo htmlspecialchars($comic['titulo']); ?></td>
                                    <td><?php echo htmlspecialchars($comic['autor']); ?></td>
                                    <td><?php echo htmlspecialchars($comic['estado']); ?></td>
                                    <td>
                                        <?php echo $comic['prestado'] ? 'Sí' : 'No'; ?> 
                                        <a href="index.php?accion=modificar&id=<?php echo $comic['id']; ?>&campo=prestado&valor=<?php echo $comic['prestado'] ? '0' : '1'; ?>" style="font-size: small;">
                                            (Toggle)
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($comic['localizacion']); ?></td>
                                    <td>
                                        <?php
                                        $siguiente_estado = match($estado_ui) {
                                            'pendiente' => 'leyendo',
                                            'leyendo' => 'leido',
                                            'leido' => 'pendiente',
                                            default => 'pendiente',
                                        };
                                        ?>
                                        <a href="index.php?accion=modificar&id=<?php echo $comic['id']; ?>&campo=estado&valor=<?php echo $siguiente_estado; ?>"
                                            title="Cambiar a <?php echo ucfirst($siguiente_estado); ?>">
                                            Cambiar Estado
                                        </a> 
                                        |
                                        <a href="index.php?accion=eliminar&id=<?php echo $comic['id']; ?>" 
                                           onclick="return confirm('¿Seguro de eliminar «<?php echo htmlspecialchars($comic['titulo']); ?>»?');" style="color:red;">
                                            Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>