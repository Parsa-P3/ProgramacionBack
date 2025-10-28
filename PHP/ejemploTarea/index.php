<?php
// Capa de Negocio / Acceso a Datos
// Incluye el archivo que contiene las funciones CRUD (Crear, Leer, Actualizar, Borrar) para las tareas.
include 'funciones.php';

// Inicializa una variable para almacenar cualquier mensaje de éxito o error que se deba mostrar.
$mensaje = '';
// Inicializa una variable para la clase CSS que dará estilo al mensaje (por ejemplo, 'green' o 'red').
$clase_mensaje = '';

// ----------------------------------------------------
// LÓGICA DE PROCESAMIENTO (POST & GET)
// ----------------------------------------------------

// Comprueba si la solicitud HTTP fue enviada por el método POST (normalmente, al enviar el formulario superior).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene el valor del campo 'accion' del formulario unificado para determinar la operación.
    $accion = $_POST['accion'] ?? '';
    // Obtiene el estado seleccionado, usado tanto para agregar como para modificar.
    $estado = $_POST['estado'] ?? 'pendiente';

    // 1. OPERACIÓN DE AÑADIR TAREA (AGREGAR)
    // Si la acción seleccionada es 'agregar'.
    if ($accion === 'agregar') {
        // Limpia y obtiene la descripción de la tarea a partir del input 'descripcion'.
        $desc = trim($_POST['descripcion'] ?? '');

        // Verifica que la descripción no esté vacía antes de proceder.
        if (!empty($desc)) {
            // Llama a la función de negocio para agregar la nueva tarea al sistema.
            agregar_tarea($desc, $estado);
            // Redirección después de una operación exitosa (PRG: Post/Redirect/Get).
            header('Location: index.php?msg=agregado');
            // Detiene la ejecución del script.
            exit;
        } else {
            // Manejo de error si la descripción está vacía (solo si falla el JS).
            $mensaje = 'ERROR: La descripción no puede estar vacía.';
            $clase_mensaje = 'red';
        }
    }

    // 2. OPERACIÓN DE ACTUALIZAR ESTADO (MODIFICAR POST)
    // Si la acción seleccionada es 'modificar'.
    elseif ($accion === 'modificar') {
        // Obtiene el ID de la tarea a modificar y lo convierte a entero (input 'id_modificar').
        $id_mod = (int) ($_POST['id_modificar'] ?? 0);
        // El nuevo estado es el que se seleccionó en el campo 'estado' compartido.
        $nuevo_estado = $estado;

        // Verifica que el ID sea un valor positivo.
        if ($id_mod > 0) {
            // Llama a la función para modificar el estado de la tarea.
            if (modificar_estado_tarea($id_mod, $nuevo_estado)) {
                // Redirección después de una operación exitosa.
                header('Location: index.php?msg=modificado&id=' . $id_mod);
                exit;
            } else {
                // Manejo de error si el ID existe o el estado no es válido.
                $mensaje = "ERROR: No se encontró la tarea con ID $id_mod o el estado no es válido.";
                $clase_mensaje = 'red';
            }
        } else {
            // Manejo de error si el ID es inválido.
            $mensaje = "ERROR: ID de tarea no válido para modificar.";
            $clase_mensaje = 'red';
        }
    }
}
// Comprueba si la solicitud HTTP fue enviada por el método GET (acceso directo o enlace de la tabla).
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtiene la acción y el ID de la URL (query string).
    $accion = $_GET['accion'] ?? '';
    $id = (int) ($_GET['id'] ?? 0);

    // 3. OPERACIÓN DE ELIMINAR (ELIMINAR)
    // Si se recibe un ID válido y la acción es 'eliminar' (desde el enlace de la tabla).
    if ($id > 0 && $accion === 'eliminar') {
        // Llama a la función para eliminar la tarea.
        if (eliminar_tarea($id)) {
            // OPERACIÓN EXITOSA: Redirige para evitar el doble envío al refrescar (PRG).
            header('Location: index.php?msg=eliminado&id=' . $id);
            exit;
        } else {
            // ERROR: Si la tarea no se encontró.
            $mensaje = "ERROR: No se encontró la tarea con ID $id.";
            $clase_mensaje = 'red';
        }
    }

    // 4. MOSTRAR MENSAJES DESPUÉS DE LA REDIRECCIÓN
    // Revisa si hay un parámetro 'msg' en la URL.
    if (isset($_GET['msg'])) {
        $msg = $_GET['msg'];
        // Obtiene el ID de la tarea afectada (si existe).
        $id_param = $_GET['id'] ?? '??';

        // Muestra el mensaje de éxito correspondiente.
        switch ($msg) {
            case 'eliminado':
                $mensaje = "Tarea ID $id_param eliminada correctamente.";
                $clase_mensaje = 'green';
                break;
            case 'agregado':
                $mensaje = 'Tarea agregada correctamente!';
                $clase_mensaje = 'green';
                break;
            case 'modificado':
                $mensaje = "Tarea ID $id_param modificada correctamente.";
                $clase_mensaje = 'green';
                break;
        }
    }
}

// Obtener la lista de tareas (Esta línea se ejecuta siempre para mostrar la lista actualizada).
$tareas = listar_todas_las_tareas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Tareas Final</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <div class="container">
        <h1>Gestión de Tareas (Simple)</h1>

        <?php if ($mensaje): ?>
            <div class="mensaje <?php echo $clase_mensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <h2>1. Agregar y Modificar Tareas</h2>
        <form method="POST">
            <label for="accion_form">Acción a realizar:</label>
            <select id="accion_form" name="accion" onchange="toggleFields()">
                <option value="agregar">Agregar Nueva Tarea</option>
                <option value="modificar">Modificar Estado por ID</option>
            </select>
            
            <hr style="width: 100%;">
            
            <div id="campo_id" style="display: none;">
                <label for="id_modificar">ID de Tarea:</label>
                <input type="number" id="id_modificar" name="id_modificar" min="1" size="5">
            </div>

            <div id="campo_descripcion">
                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" placeholder="Ej: Escribir código" size="30">
            </div>
            
            <label for="estado">Estado:</label>
            <select id="estado" name="estado">
                <option value="pendiente">Pendiente</option>
                <option value="en progreso">En Progreso</option>
                <option value="completada">Completada</option>
            </select>
            
            <button type="submit">Ejecutar Acción</button>
        </form>

        <hr>
        
        <script>
        // Función JavaScript para mostrar/ocultar campos según la acción seleccionada.
        function toggleFields() {
            // Obtiene el valor seleccionado del selector de acción.
            const accion = document.getElementById('accion_form').value;
            // Obtiene los contenedores de campos.
            const campoId = document.getElementById('campo_id');
            const campoDesc = document.getElementById('campo_descripcion');
            // Obtiene los inputs para gestionar la propiedad 'required'.
            const inputDesc = document.getElementById('descripcion');
            const inputId = document.getElementById('id_modificar');

            // Lógica para la acción 'modificar'.
            if (accion === 'modificar') {
                campoId.style.display = 'block'; // Muestra el campo ID.
                campoDesc.style.display = 'none'; // Oculta el campo Descripción.
                
                // Hace el campo de ID obligatorio y la descripción no obligatoria.
                inputId.required = true;
                inputDesc.required = false;
            } else { // 'agregar' (o valor por defecto).
                campoId.style.display = 'none'; // Oculta el campo ID.
                campoDesc.style.display = 'block'; // Muestra el campo Descripción.
                
                // Hace el campo de descripción obligatorio y el ID no obligatorio.
                inputId.required = false;
                inputDesc.required = true;
            }
        }
        // Ejecuta la función al cargar la página para configurar el estado inicial.
        document.addEventListener('DOMContentLoaded', toggleFields);
        </script>
        
        <h2>2. Lista de Tareas</h2>
        <?php if (empty($tareas)): ?>
            <p>No hay tareas registradas.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr><th>ID</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($tareas as $tarea):
                        // Crea una clase CSS basada en el estado (ej: 'tarea-en-progreso').
                        $clase_estado = 'tarea-' . str_replace(' ', '-', $tarea['estado']);
                        ?>
                        <tr class="<?php echo $clase_estado; ?>">
                            <td><?php echo htmlspecialchars($tarea['id']); ?></td>
                            <td><?php echo htmlspecialchars($tarea['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($tarea['estado']); ?></td>
                            
                            <td class="acciones-celda">
                                <a class="btn-eliminar" 
                                   href="?accion=eliminar&id=<?php echo $tarea['id']; ?>" 
                                   onclick="return confirm('¿Seguro que quieres eliminar la tarea ID <?php echo $tarea['id']; ?>?');">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>
</body>
</html>