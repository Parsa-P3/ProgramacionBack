<?php
require_once 'funciones.php';
$config = require 'config.php';
$mensaje = "";

// Solo intentamos conectar si el botón se pulsa Y existe la clase
if (isset($_GET['importar'])) {
    if (class_exists('MongoDB\Driver\Manager')) {
        try {
            $manager = new MongoDB\Driver\Manager($config['mongodb']['connection_string']);
            $datos = leerFicheros();
            $bulk = new MongoDB\Driver\BulkWrite;
            foreach ($datos as $doc) { $bulk->insert($doc); }
            $manager->executeBulkWrite($config['mongodb']['bbdd'].".".$config['mongodb']['coleccion'], $bulk);
            $mensaje = "<b style='color:green'>✅ ¡Datos enviados a Atlas con éxito!</b>";
        } catch (Exception $e) {
            $mensaje = "<b style='color:red'>❌ Error de conexión: " . $e->getMessage() . "</b>";
        }
    } else {
        $mensaje = "<b style='color:orange'>⚠️ Tu XAMPP no tiene MongoDB instalado. La tabla de abajo funciona, pero no puedes enviar a la nube desde aquí. En el PC del profe sí funcionará.</b>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sesión 4 - Alumnos</title>
    <style>table{border-collapse:collapse;width:100%;} th,td{border:1px solid #000;padding:5px;}</style>
</head>
<body>
    <h1>Panel de Control - Sesión 4</h1>
    
    <a href="index.php?importar=1" style="background:blue; color:white; padding:10px; text-decoration:none;">🚀 IMPORTAR A MONGODB</a>
    <p><?= $mensaje ?></p>

    <h2>Vista previa de archivos (Lectura)</h2>
    <table>
        <tr><th>Nº Fila</th><th>Nombre</th><th>Apellidos</th><th>Sexo</th><th>¿Sexy?</th></tr>
        <?php foreach (leerFicheros() as $f): ?>
        <tr>
            <td><?= $f['Numero'] ?></td>
            <td><?= $f['Alumno']['Nombre'] ?></td>
            <td><?= $f['Alumno']['Apellidos'] ?></td>
            <td><?= $f['Alumno']['Sexo'] ?></td>
            <td><?= $f['Alumno']['es_profe_sexi'] ? 'SI' : 'NO' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>