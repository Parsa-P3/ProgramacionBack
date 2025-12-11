

function IrLogin() {
    let frm = document.forms[0];
    frm.action = `login.php`;
    frm.submit();
}

//++funciones de usuario
function IrListadoUsuarios() {
    document.location='listado.php';
}

function IrFicha(list = false) {
     //Controlo que la llamada venga o no de la lista para gestionar la vuelta
    let qList = "";
    if (list) {
        qList = "?listado=true";
    }
    let frm = document.forms[0];
    frm.action = `ficha.php${qList}`;
    frm.submit();
}

function anadirUsuario(list = false) {
    //Controlo que la llamada venga o no de la lista para gestionar la vuelta
    let qList = "";
    if (list) {
        qList = "&listado=true";
    }
    let frm = document.forms[0];
    frm.action = `ficha_guardar.php?action=anadir${qList}`;
    frm.submit();
}

function modificarUsuario(list = false) {
    //Controlo que la llamada venga o no de la lista para gestionar la vuelta
    let qList = "";
    if (list) {
        qList = "&listado=true";
    }

    let frm = document.forms[0];
    frm.action = `ficha_guardar.php?action=guardar${qList}`;
    frm.submit();
}

function eliminarUsuario(id) {
    //Me hago una funcion de javascript para lanzar el submit del form oculto 
    let url = 'ficha_guardar.php?action=eliminar&listado=true';
    let conf = confirm(`¿Seguro que deseas eliminar este usuario, con id ${id}?`);

    let hid = document.getElementById('usuario_id');
    hid.value = id;

    if (conf) {
        let frm = document.forms[0];
        frm.action = url;
        frm.submit()
    }
}

//--funciones de usuario

//++funciones de cliente
function IrListadoClientes() {
    document.location='listado_clientes.php';
}

function IrFichaCliente(list = false) {
     //Controlo que la llamada venga o no de la lista para gestionar la vuelta
    let qList = "";
    if (list) {
        qList = "?listado=true";
    }
    let frm = document.forms[0];
    frm.action = `ficha_cliente.php${qList}`;
    frm.submit();
}

function anadirCliente(list = false) {
    //Controlo que la llamada venga o no de la lista para gestionar la vuelta
    let qList = "";
    if (list) {
        qList = "&listado=true";
    }
    let frm = document.forms[0];
    frm.action = `ficha_cliente_guardar.php?action=anadir${qList}`;
    frm.submit();
}

function modificarCliente(list = false) {
    //Controlo que la llamada venga o no de la lista para gestionar la vuelta
    let qList = "";
    if (list) {
        qList = "&listado=true";
    }

    let frm = document.forms[0];
    frm.action = `ficha_cliente_guardar.php?action=guardar${qList}`;
    frm.submit();
}

function eliminarCliente(id) {
    //Me hago una funcion de javascript para lanzar el submit del form oculto 
    let url = 'ficha_cliente_guardar.php?action=eliminar&listado=true';
    let conf = confirm(`¿Seguro que deseas eliminar este cliente, con id ${id}?`);

    let hid = document.getElementById('cliente_id');
    hid.value = id;

    if (conf) {
        let frm = document.forms[0];
        frm.action = url;
        frm.submit()
    }
}

//--funciones de cliente

function login() {
    let frm = document.forms[0];
    frm.action = `acceder.php?action=login`;
    frm.submit();
}



function cerrarSesion(){
    let frm = document.forms[0];
    frm.action = `acceder.php?action=cerrarsesion`;
    frm.submit();
}

//... (el código existente continúa arriba)

//++funciones de contacto
function IrListadoContactos(cliente_id) {
    if (cliente_id && cliente_id != 0) {
        // Ir a la lista filtrada por cliente
        document.location = `listado_contactos.php?cliente_id=${cliente_id}`;
    } else {
        // Ir a la lista global de todos los contactos
        document.location = 'listado_contactos.php';
    }
}

function IrFichaContacto(cliente_id, contacto_id = 0) {
    let url = `ficha_contacto.php?cliente_id=${cliente_id}`;
    if (contacto_id != 0) {
        url += `&contacto_id=${contacto_id}`;
    }
    document.location = url;
}

function anadirContacto(cliente_id) {
    let frm = document.forms[0];
    frm.action = `ficha_contacto_guardar.php?action=anadir`; // Los IDs van en el POST
    frm.submit();
}

function modificarContacto(cliente_id) {
    let frm = document.forms[0];
    frm.action = `ficha_contacto_guardar.php?action=guardar`; // Los IDs van en el POST
    frm.submit();
}

function eliminarContacto(contacto_id, cliente_id) {
    // La acción de eliminar se gestiona por GET para simplificar
    let url = `ficha_contacto_guardar.php?action=eliminar&contacto_id=${contacto_id}&cliente_id=${cliente_id}`;
    let conf = confirm(`¿Seguro que deseas eliminar este contacto, con id ${contacto_id}?`);

    if (conf) {
        // Redirige y el archivo PHP lo manejará.
        document.location = url; 
    }
}
//--funciones de contacto