// funciones compartidas entre las vistas, nada de esto depende de un rol especifico

function calcularDistanciaKm(lat1, lng1, lat2, lng2) {
    const radioTierra = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLng / 2) * Math.sin(dLng / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return radioTierra * c;
}

function calcularDistanciaMetros(lat1, lng1, lat2, lng2) {
    return calcularDistanciaKm(lat1, lng1, lat2, lng2) * 1000;
}

async function llamarApi(url, datos) {
    try {
        const respuesta = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams(datos)
        });

        const texto = await respuesta.text();

        try {
            return JSON.parse(texto);
        } catch (errorParseo) {
            console.error("Respuesta no era JSON valido desde " + url + ":", texto);
            return { exito: false, mensaje: "El servidor respondio de forma inesperada. Revisa la consola para el detalle." };
        }
    } catch (errorRed) {
        console.error("Error de red llamando a " + url + ":", errorRed);
        return { exito: false, mensaje: "No se pudo conectar con el servidor." };
    }
}

function mostrarMensaje(elementoId, texto, esError) {
    const contenedor = document.getElementById(elementoId);
    if (!contenedor) return;
    contenedor.textContent = texto;
    contenedor.className = esError ? "mensaje-error" : "mensaje-exito";
    contenedor.style.display = "block";
}

function cambiarPestana(nombrePestana) {
    document.querySelectorAll(".pestana").forEach(function (el) {
        el.classList.remove("activa");
    });
    document.querySelectorAll(".seccion-oculta, .seccion-pestana").forEach(function (el) {
        el.classList.add("seccion-oculta");
    });

    const pestanaActiva = document.querySelector('[data-pestana="' + nombrePestana + '"]');
    if (pestanaActiva) pestanaActiva.classList.add("activa");

    const seccionActiva = document.getElementById("seccion-" + nombrePestana);
    if (seccionActiva) seccionActiva.classList.remove("seccion-oculta");
}