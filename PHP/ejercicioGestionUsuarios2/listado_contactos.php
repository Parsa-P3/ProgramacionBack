<?php
//Me traigo el fichero que tiene todas las librerias básicas del proyecto
require_once "utils.php";
require_once "./models/Contacto.php";
require_once "./models/Clientes.php"; // Necesario para obtener el nombre del cliente

// Validar que existe sesión activa
if (!validarSesionActiva()) {
    exit();
}

$cont = new Contacto();
$cliente_id_filtro = (int)($_GET['cliente_id'] ?? 0); // Filtra por cada cliente
$nombre_cliente = "";

// Obtengo el listado de contactos.
$contactos = $cont->obtenerTodos($pdo, $cliente_id_filtro); // Muestra todos / Filtra

// Si se está filtrando, obtenemos el nombre del cliente para el título
if ($cliente_id_filtro != 0) {
    $cli = Cliente::obtenerPorId($pdo, $cliente_id_filtro);
    $nombre_cliente = $cli->getNombre() . " " . $cli->getApellidos();
}

//Extraigo el usuario conectado para el control de permisos (B-0.5)
$usu_conectado = $_SESSION["usuario"];
$rol_id_usuario = $usu_conectado->getRolId();

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Listado de Contactos</title>
    <link rel="stylesheet" href="./estilos/estilos.css">
    <script src="./scripts/scripts.js"></script>
</head>

<body>
    <div class="tabla-contenedor">

        <h1>Listado de Contactos
            <?= $cliente_id_filtro != 0 ? ' del Cliente: ' . htmlspecialchars($nombre_cliente) : ' (Global)' ?>
        </h1>

        <button class="btn cerrarsesion" onclick="cerrarSesion()">
            Cerrar Sesion
        </button>
        <button class="btn cerrarsesion" onclick="IrListadoClientes()">
            Volver a Clientes
        </button>

        <?php if ($rol_id_usuario == 1): // Solo admin puede añadir ?>
            <?php if ($cliente_id_filtro == 0): // En listado global ?>
                <a href="#" onclick="javascript:IrListadoContactos(0)" class="btn secondary anadir">
                    Listado Global
                </a>
                <a href="#" onclick="javascript:IrListadoClientes()" class="btn primary anadir">
                    ➕ Añadir Contacto (Seleccionar Cliente)
                </a>
            <?php else: // En listado filtrado por cliente ?>
                <a href="#" onclick="javascript:IrFichaContacto(<?= $cliente_id_filtro ?>)" class="btn primary anadir">
                    ➕ Añadir Contacto
                </a>
                <a href="#" onclick="javascript:IrListadoContactos(0)" class="btn secondary anadir">
                    Ver Listado Global
                </a>
            <?php endif; ?>
        <?php endif; ?>


        <table>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Teléfono</th>
                <?php if ($rol_id_usuario == 1): ?>
                    <th>Acciones</th>
                <?php endif; ?>
            </tr>

            <?php foreach ($contactos as $c): ?>
                <tr>
                    <td><?= $c->getId() ?></td>
                    <td><?= $cliente_id_filtro == 0 ? (isset($c->cliente_nombre) ? $c->cliente_nombre : 'N/A') : htmlspecialchars($nombre_cliente) ?></td>
                    <td><?= $c->getNombre() ?></td>
                    <td><?= $c->getApellidos() ?></td>
                    <td><?= $c->getEmail() ?></td>
                    <td><?= $c->getTelefono() ?></td>
                    <?php if ($rol_id_usuario == 1): ?>
                        <td class="acciones">

                            <a class="btn editar"
                                href="ficha_contacto.php?contacto_id=<?= $c->getId() ?>&cliente_id=<?= $c->getClienteId() ?>">
                                Editar
                            </a>

                            <button class="btn borrar"
                                onclick="eliminarContacto(<?= $c->getId() ?>, <?= $c->getClienteId() ?>)">
                                Borrar
                            </button>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>

        </table>
    </div>
    <form action="" method="post" id="frmEli" name="frmEli" style="visibility: hidden;">
        <input type="hidden" name="contacto_id" id="contacto_id">
        <input type="hidden" name="cliente_id" id="cliente_id">
    </form>
</body>

</html>