function IrLogin() {
    let frm = document.forms[0];
    if (frm) {
        frm.action = `login.php`;
        frm.submit();
    }
}

//++funciones de usuario
function IrListadoUsuarios() {
    // La ruta es correcta si el script se llama desde el index (root) o un nivel abajo (listados/ o fichas/)
    document.location = '../listados/listado.php'; 
}

function IrFicha(list = false) {
    //Controlo que la llamada venga o no de la lista para gestionar la vuelta
    let qList = "";
    if (list) {
        qList = "?listado=true";
    }
    let frm = document.forms[0];
    if (frm) {
        // La ruta es correcta si se llama desde index (root) o listados/
        frm.action = `../fichas/ficha.php${qList}`;
        frm.submit();
    }
}

function anadirUsuario(list = false) {
    //Controlo que la llamada venga o no de la lista para gestionar la vuelta
    let qList = "";
    if (list) {
        qList = "&listado=true";
    }
    let frm = document.forms[0];
    if (frm) {
        // La ruta es correcta si se llama desde fichas/ficha.php
        frm.action = `../fichas/ficha_guardar.php?action=anadir${qList}`;
        frm.submit();
    }
}

function modificarUsuario(list = false) {
    //Controlo que la llamada venga o no de la lista para gestionar la vuelta
    let qList = "";
    if (list) {
        qList = "&listado=true";
    }

    let frm = document.forms[0];
    if (frm) {
        // La ruta es correcta si se llama desde fichas/ficha.php
        frm.action = `../fichas/ficha_guardar.php?action=guardar${qList}`;
        frm.submit();
    }
}

function eliminarUsuario(id) {
    // Me hago una funcion de javascript para lanzar el submit del form oculto 
    let url = '../fichas/ficha_guardar.php?action=eliminar&listado=true';
    let conf = confirm(`¿Seguro que deseas eliminar este usuario, con id ${id}?`);

    // El formulario oculto en listado.php es <form id="frmEli">
    let frm = document.getElementById('frmEli'); // <--- CORRECCIÓN CLAVE
    let hid = document.getElementById('usuario_id');

    if (!frm || !hid) {
        console.error("Error: No se encontró el formulario 'frmEli' o el campo 'usuario_id'.");
        return;
    }

    hid.value = id;

    if (conf) {
        frm.action = url;
        frm.method = "post"; // Aseguramos el método POST
        frm.submit();
    }
}

//--funciones de usuario

//++funciones de cliente
function IrListadoClientes() {
    // La ruta es correcta si el script se llama desde el index (root) o un nivel abajo (listados/ o fichas/)
    document.location = '../listados/listado_clientes.php';
}

function IrFichaCliente(list = false) {
    //Controlo que la llamada venga o no de la lista para gestionar la vuelta
    let qList = "";
    if (list) {
        qList = "?listado=true";
    }
    let frm = document.forms[0];
    if (frm) {
        // La ruta es correcta si se llama desde listados/
        frm.action = `../fichas/ficha_cliente.php${qList}`;
        frm.submit();
    }
}

function anadirCliente(list = false) {
    //Controlo que la llamada venga o no de la lista para gestionar la vuelta
    let qList = "";
    if (list) {
        qList = "&listado=true";
    }
    let frm = document.forms[0];
    if (frm) {
        // La ruta es correcta si se llama desde fichas/ficha_cliente.php
        frm.action = `../fichas/ficha_cliente_guardar.php?action=anadir${qList}`;
        frm.submit();
    }
}

function modificarCliente(list = false) {
    //Controlo que la llamada venga o no de la lista para gestionar la vuelta
    let qList = "";
    if (list) {
        qList = "&listado=true";
    }

    let frm = document.forms[0];
    if (frm) {
        // La ruta es correcta si se llama desde fichas/ficha_cliente.php
        frm.action = `../fichas/ficha_cliente_guardar.php?action=guardar${qList}`;
        frm.submit();
    }
}

function eliminarCliente(id) {
    // Me hago una funcion de javascript para lanzar el submit del form oculto 
    let url = '../fichas/ficha_cliente_guardar.php?action=eliminar&listado=true';
    let conf = confirm(`¿Seguro que deseas eliminar este cliente, con id ${id}?`);

    // El formulario oculto en listado_clientes.php es <form id="frmEli">
    let frm = document.getElementById('frmEli'); // <--- CORRECCIÓN CLAVE
    let hid = document.getElementById('cliente_id');

    if (!frm || !hid) {
        console.error("Error: No se encontró el formulario 'frmEli' o el campo 'cliente_id'.");
        return;
    }

    hid.value = id;

    if (conf) {
        frm.action = url;
        frm.method = "post"; // Aseguramos el método POST
        frm.submit();
    }
}

//--funciones de cliente

function login() {
    let frm = document.forms[0];
    if (frm) {
        frm.action = `acceder.php?action=login`; // Ruta correcta si se llama desde login.php (root)
        frm.submit();
    }
}


function cerrarSesion() {
    let frm = document.forms[0];
    if (frm) {
        frm.action = `../acceder.php?action=cerrarsesion`; // Ruta correcta si se llama desde login.php (root)
        frm.submit();
    }
}

//++funciones de contacto
function IrListadoContactos(cliente_id) {
    // La ruta es relativa a la carpeta actual (si estoy en fichas/, la ruta es ../listados/)
    if (cliente_id && cliente_id != 0) {
        // Ir a la lista filtrada por cliente
        document.location = `../listados/listado_contactos.php?cliente_id=${cliente_id}`;
    } else {
        // Ir a la lista global de todos los contactos (desde listados/listado_clientes.php)
        document.location = '../listados/listado_contactos.php';
    }
}

function IrFichaContacto(cliente_id, contacto_id = 0) {
    // La ruta es relativa a la carpeta actual (si estoy en listados/, la ruta es ../fichas/)
    let url = `../fichas/ficha_contacto.php?cliente_id=${cliente_id}`;
    if (contacto_id != 0) {
        url += `&contacto_id=${contacto_id}`;
    }
    document.location = url;
}

function anadirContacto(cliente_id) {
    let frm = document.forms[0];
    if (frm) {
        // La acción se establece como POST a ficha_contacto_guardar.php (asumo que está en fichas/)
        frm.action = `../fichas/ficha_contacto_guardar.php?action=anadir`; 
        frm.submit();
    }
}

function modificarContacto(cliente_id) {
    let frm = document.forms[0];
    if (frm) {
        // La acción se establece como POST a ficha_contacto_guardar.php (asumo que está en fichas/)
        frm.action = `../fichas/ficha_contacto_guardar.php?action=guardar`; 
        frm.submit();
    }
}

function eliminarContacto(contacto_id, cliente_id) {
    // La acción de eliminar se gestiona por GET 
    // La ruta es relativa a la carpeta actual (si estoy en listados/, la ruta es ../fichas/)
    let url = `../fichas/ficha_contacto_guardar.php?action=eliminar&contacto_id=${contacto_id}&cliente_id=${cliente_id}`;
    let conf = confirm(`¿Seguro que deseas eliminar este contacto, con id ${contacto_id}?`);

    if (conf) {
        // Redirige y el archivo PHP lo manejará.
        document.location = url;
    }
}
//--funciones de contacto