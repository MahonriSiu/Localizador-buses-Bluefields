const URL_BASE = "/public";

async function iniciarSesionAdmin(correo, contrasena) {
    const resultado = await llamarApi(URL_BASE + "/admin_login.php", { correo: correo, contrasena: contrasena });

    if (resultado.exito) {
        window.location.href = resultado.debe_cambiar
            ? URL_BASE + "/cambiar_contrasena.php"
            : URL_BASE + "/admin/panel.php";
    } else {
        mostrarMensaje("mensaje-login", resultado.mensaje, true);
    }
}

async function cargarResumen() {
    const resultado = await llamarApi(URL_BASE + "/admin_resumen.php", {});
    animarNumero(document.getElementById("total-buses"), resultado.total_buses);
    animarNumero(document.getElementById("buses-habilitados"), resultado.buses_habilitados);
    animarNumero(document.getElementById("buses-transmitiendo"), resultado.buses_transmitiendo);
}

async function crearCuenta(nombre, correo, rol) {
    const resultado = await llamarApi(URL_BASE + "/admin_crear_cuenta.php", { nombre: nombre, correo: correo, rol: rol });

    if (resultado.exito) {
        mostrarMensaje("mensaje-cuentas", "Cuenta creada correctamente con contrasena admin123", false);
        cargarCuentas();
    } else {
        mostrarMensaje("mensaje-cuentas", resultado.mensaje, true);
    }
}

async function cargarCuentas() {
    const resultado = await llamarApi(URL_BASE + "/admin_obtener_cuentas.php", {});

    transicionDom(function () {
        const totalEl = document.getElementById("total-cuentas");
        if (totalEl) totalEl.textContent = resultado.total_cuentas;

        const cuerpoAdmins = document.getElementById("cuerpo-tabla-admins");
        if (cuerpoAdmins && resultado.admins) {
            cuerpoAdmins.innerHTML = "";
            resultado.admins.forEach(function (usuario) {
                cuerpoAdmins.innerHTML += "<tr><td>" + usuario.nombre + "</td><td>" + usuario.correo + "</td>" +
                    "<td><button class='boton boton-peligro' onclick='resetearContrasena(" + usuario.id + ")'>Resetear</button></td></tr>";
            });
        }

        const cuerpoAuditores = document.getElementById("cuerpo-tabla-auditores");
        const cuerpoPropietarios = document.getElementById("cuerpo-tabla-propietarios");

        if (cuerpoAuditores) {
            cuerpoAuditores.innerHTML = "";
            resultado.auditores.forEach(function (usuario) { cuerpoAuditores.innerHTML += filaCuenta(usuario); });
        }
        if (cuerpoPropietarios) {
            cuerpoPropietarios.innerHTML = "";
            resultado.propietarios.forEach(function (usuario) { cuerpoPropietarios.innerHTML += filaCuenta(usuario); });
        }
    });
}

function filaCuenta(usuario) {
    return "<tr>" +
        "<td>" + usuario.nombre + "</td>" +
        "<td>" + usuario.correo + "</td>" +
        "<td><button class='boton boton-secundario' onclick='verHistorial(" + usuario.id + ")'>Ver historial</button></td>" +
        "<td><button class='boton boton-peligro' onclick='resetearContrasena(" + usuario.id + ")'>Resetear</button></td>" +
        "</tr>";
}

async function verHistorial(usuarioId) {
    const resultado = await llamarApi(URL_BASE + "/admin_ver_historial.php", { usuario_id: usuarioId });
    if (!resultado.exito || resultado.historial.length === 0) {
        alert("Este usuario no tiene contrasenas anteriores registradas.");
        return;
    }
    let texto = "Historial de contrasenas:\n\n";
    resultado.historial.forEach(function (fila) { texto += fila.contrasena_anterior + "  (" + fila.fecha_cambio + ")\n"; });
    alert(texto);
}

async function resetearContrasena(usuarioId) {
    const confirmar = confirm("Se va a generar una contrasena nueva para este usuario. Continuar?");
    if (!confirmar) return;

    const resultado = await llamarApi(URL_BASE + "/admin_resetear_contrasena.php", { usuario_id: usuarioId });
    if (resultado.exito) {
        alert("Nueva contrasena: " + resultado.nueva_contrasena + "\n\nCopiala y entregasela al usuario.");
        cargarCuentas();
    }
}

async function cargarSelectorPropietarios(idSelector) {
    const resultado = await llamarApi(URL_BASE + "/admin_obtener_propietarios.php", {});
    const selector = document.getElementById(idSelector);
    selector.innerHTML = "";

    if (!resultado.propietarios || resultado.propietarios.length === 0) {
        selector.innerHTML = "<option value=''>No hay propietarios creados aun</option>";
        return;
    }
    resultado.propietarios.forEach(function (p) {
        const opcion = document.createElement("option");
        opcion.value = p.id;
        opcion.textContent = p.nombre;
        selector.appendChild(opcion);
    });
}

async function crearBus(nombre, origen, destino, descripcion, propietarioId) {
    if (!propietarioId) {
        mostrarMensaje("mensaje-bus", "Debes crear un propietario antes de crear un bus", true);
        return;
    }
    const resultado = await llamarApi(URL_BASE + "/admin_crear_bus.php", {
        nombre: nombre, origen: origen, destino: destino, descripcion: descripcion, propietario_id: propietarioId
    });

    if (resultado.exito) {
        mostrarMensaje("mensaje-bus", "Bus creado. Codigo de acceso del emisor: " + resultado.codigo_acceso, false);
        cargarBusesAdmin();
        cargarSelectorBusesParaParadas();
    } else {
        mostrarMensaje("mensaje-bus", resultado.mensaje, true);
    }
}

async function cargarBusesAdmin() {
    const resultado = await llamarApi(URL_BASE + "/admin_obtener_buses.php", {});
    const cuerpoTabla = document.getElementById("cuerpo-tabla-buses");
    if (!cuerpoTabla) return;

    transicionDom(function () {
        if (!resultado.buses) {
            cuerpoTabla.innerHTML = "<tr><td colspan='6'>" + (resultado.mensaje || "No se pudieron cargar los buses") + "</td></tr>";
            return;
        }
        cuerpoTabla.innerHTML = "";
        if (resultado.buses.length === 0) {
            cuerpoTabla.innerHTML = "<tr><td colspan='6'>Aun no hay buses creados</td></tr>";
            return;
        }

        resultado.buses.forEach(function (bus) {
            const etiqueta = bus.habilitado == 1 ? "<span class='etiqueta-activo'>Habilitado</span>" : "<span class='etiqueta-inactivo'>Deshabilitado</span>";
            cuerpoTabla.innerHTML += "<tr>" +
                "<td>" + bus.nombre + "</td>" +
                "<td>" + bus.origen + " - " + bus.destino + "</td>" +
                "<td>" + (bus.nombre_propietario || "Sin dueno") + "</td>" +
                "<td>" + etiqueta + "</td>" +
                "<td><button class='boton boton-secundario' onclick='toggleBus(" + bus.id + ", " + (bus.habilitado == 1 ? 0 : 1) + ")'>Cambiar estado</button></td>" +
                "<td><button class='boton boton-secundario' onclick='verCodigoEmisor(" + bus.id + ")'>Ver codigo</button> " +
                "<button class='boton boton-peligro' onclick='regenerarCodigoEmisor(" + bus.id + ")'>Regenerar</button></td>" +
                "</tr>";
        });
    });
}

async function verCodigoEmisor(busId) {
    const respuesta = await fetch(URL_BASE + "/admin_obtener_codigo_emisor.php?bus_id=" + busId);
    const resultado = await respuesta.json();
    alert(resultado.exito ? "Codigo de acceso del emisor: " + resultado.codigo : resultado.mensaje);
}

async function regenerarCodigoEmisor(busId) {
    const confirmar = confirm("Esto invalida el codigo anterior. El chofer debera usar el nuevo codigo. Continuar?");
    if (!confirmar) return;
    const resultado = await llamarApi(URL_BASE + "/admin_regenerar_codigo_emisor.php", { id: busId });
    if (resultado.exito) alert("Nuevo codigo de acceso: " + resultado.codigo);
}

async function toggleBus(id, nuevoEstado) {
    await llamarApi(URL_BASE + "/admin_toggle_bus.php", { id: id, habilitado: nuevoEstado });
    cargarBusesAdmin();
}

async function cargarSelectorBusesParaParadas() {
    const resultado = await llamarApi(URL_BASE + "/admin_obtener_buses.php", {});
    const selector = document.getElementById("bus-para-parada");
    if (!selector || !resultado.buses) return;

    selector.innerHTML = "";
    resultado.buses.forEach(function (bus) {
        const opcion = document.createElement("option");
        opcion.value = bus.id;
        opcion.textContent = bus.nombre;
        selector.appendChild(opcion);
    });
    if (resultado.buses.length > 0) cargarParadasDelBus(resultado.buses[0].id);
}

async function agregarParada() {
    const busId = document.getElementById("bus-para-parada").value;
    const nombre = document.getElementById("nombre-parada").value;
    const lat = document.getElementById("lat-parada").value;
    const lng = document.getElementById("lng-parada").value;
    const orden = document.getElementById("orden-parada").value;

    const resultado = await llamarApi(URL_BASE + "/admin_agregar_parada.php", { bus_id: busId, nombre: nombre, lat: lat, lng: lng, orden: orden });
    if (resultado.exito) {
        mostrarMensaje("mensaje-paradas", "Parada agregada correctamente", false);
        cargarParadasDelBus(busId);
    }
}

async function cargarParadasDelBus(busId) {
    const respuesta = await fetch(URL_BASE + "/admin_obtener_paradas.php?bus_id=" + busId);
    const resultado = await respuesta.json();
    const cuerpo = document.getElementById("cuerpo-tabla-paradas");
    if (!cuerpo) return;

    transicionDom(function () {
        cuerpo.innerHTML = "";
        resultado.paradas.forEach(function (parada) {
            cuerpo.innerHTML += "<tr><td>" + parada.nombre + "</td><td>" + parada.orden + "</td>" +
                "<td><button class='boton boton-peligro' onclick='eliminarParada(" + parada.id + ", " + busId + ")'>Eliminar</button></td></tr>";
        });
    });
}

async function eliminarParada(id, busId) {
    await llamarApi(URL_BASE + "/admin_eliminar_parada.php", { id: id });
    cargarParadasDelBus(busId);
}

let temporizadorHorario = null;
function programarGuardadoHorario() {
    if (temporizadorHorario) clearTimeout(temporizadorHorario);
    temporizadorHorario = setTimeout(function () {
        const apertura = document.getElementById("hora-apertura").value;
        const cierre = document.getElementById("hora-cierre").value;
        if (apertura && cierre) configurarHorario(apertura, cierre);
    }, 800);
}

async function configurarHorario(horaApertura, horaCierre) {
    const resultado = await llamarApi(URL_BASE + "/admin_configurar_horario.php", { hora_apertura: horaApertura, hora_cierre: horaCierre });
    if (resultado.exito) mostrarMensaje("mensaje-horario", "Horario guardado automaticamente", false);
}

async function cargarHorarioActual() {
    const resultado = await llamarApi(URL_BASE + "/admin_obtener_horario.php", {});
    if (resultado.exito) {
        document.getElementById("hora-apertura").value = resultado.config.hora_apertura.substring(0, 5);
        document.getElementById("hora-cierre").value = resultado.config.hora_cierre.substring(0, 5);
    }
}

async function cargarSolicitudes() {
    const resultado = await llamarApi(URL_BASE + "/admin_obtener_solicitudes.php", {});
    const cuerpo = document.getElementById("cuerpo-tabla-solicitudes");
    if (!cuerpo) return;

    transicionDom(function () {
        cuerpo.innerHTML = "";
        if (resultado.solicitudes.length === 0) {
            cuerpo.innerHTML = "<tr><td colspan='3'>No hay solicitudes pendientes</td></tr>";
            return;
        }
        resultado.solicitudes.forEach(function (s) {
            cuerpo.innerHTML += "<tr><td>" + s.correo + "</td><td>" + s.fecha_solicitud + "</td>" +
                "<td><button class='boton boton-secundario' onclick='atenderSolicitud(" + s.id + ")'>Marcar atendida</button></td></tr>";
        });
    });
}

async function atenderSolicitud(id) {
    await llamarApi(URL_BASE + "/admin_atender_solicitud.php", { id: id });
    cargarSolicitudes();
}

document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("cuerpo-tabla-buses")) { cargarBusesAdmin(); cargarSelectorPropietarios("propietario-bus"); }
    if (document.getElementById("bus-para-parada")) cargarSelectorBusesParaParadas();
    if (document.getElementById("cuerpo-tabla-auditores")) cargarCuentas();
    if (document.getElementById("cuerpo-tabla-solicitudes")) cargarSolicitudes();
    if (document.getElementById("total-buses")) cargarResumen();
    if (document.getElementById("hora-apertura")) cargarHorarioActual();
});