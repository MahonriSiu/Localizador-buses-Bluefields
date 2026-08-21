const URL_BASE = "/public";
let envioActivo = false;
let wakeLock = null;

async function iniciarSesionEmisor(codigo) {
    const resultado = await llamarApi(URL_BASE + "/emisor_login.php", { codigo: codigo });

    if (resultado.exito) {
        window.location.href = URL_BASE + "/emisor/panel.php";
    } else {
        mostrarMensaje("mensaje-login", resultado.mensaje, true);
    }
}

async function pedirWakeLock() {
    try {
        if ("wakeLock" in navigator) {
            wakeLock = await navigator.wakeLock.request("screen");
        }
    } catch (error) {
        console.log("No se pudo mantener la pantalla activa: " + error.message);
    }
}

function iniciarEnvioPosicion() {
    if (!navigator.geolocation) {
        mostrarMensaje("mensaje-panel", "Este dispositivo no soporta geolocalizacion", true);
        return;
    }

    envioActivo = true;
    pedirWakeLock();
    document.getElementById("estado-envio").textContent = "Enviando ubicacion...";

    enviarPosicionActual();
    setInterval(function () {
        if (envioActivo) enviarPosicionActual();
    }, 5000);
}

function enviarPosicionActual() {
    navigator.geolocation.getCurrentPosition(async function (posicion) {
        const lat = posicion.coords.latitude;
        const lng = posicion.coords.longitude;

        await llamarApi(URL_BASE + "/emisor_actualizar.php", { lat: lat, lng: lng });

        document.getElementById("ultima-actualizacion").textContent =
            "Ultima actualizacion: " + new Date().toLocaleTimeString();
    }, function (error) {
        mostrarMensaje("mensaje-panel", "No se pudo obtener la ubicacion", true);
    });
}

function detenerEnvioPosicion() {
    envioActivo = false;
    document.getElementById("estado-envio").textContent = "Envio detenido";
    if (wakeLock) {
        wakeLock.release();
        wakeLock = null;
    }
}

document.addEventListener("visibilitychange", function () {
    if (envioActivo && document.visibilityState === "visible") {
        pedirWakeLock();
    }
});