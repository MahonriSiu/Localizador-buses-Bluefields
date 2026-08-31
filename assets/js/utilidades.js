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

function sonidoTransicion() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const ganancia = ctx.createGain();

        osc.type = "sine";
        osc.frequency.setValueAtTime(420, ctx.currentTime);
        osc.frequency.exponentialRampToValueAtTime(720, ctx.currentTime + 0.12);
        osc.frequency.exponentialRampToValueAtTime(260, ctx.currentTime + 0.22);

        ganancia.gain.setValueAtTime(0.001, ctx.currentTime);
        ganancia.gain.exponentialRampToValueAtTime(0.18, ctx.currentTime + 0.03);
        ganancia.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.28);

        osc.connect(ganancia);
        ganancia.connect(ctx.destination);
        osc.start();
        osc.stop(ctx.currentTime + 0.3);
    } catch (error) { }
}

function transicionDom(callback) {
    sonidoTransicion();
    if (document.startViewTransition) {
        document.startViewTransition(callback);
    } else {
        callback();
    }
}

// anima un numero de "-" o un valor viejo hacia el valor nuevo, en vez de cambiarlo de golpe
function animarNumero(elemento, valorFinal) {
    if (!elemento) return;
    const valorInicial = parseInt(elemento.textContent, 10) || 0;
    const destino = parseInt(valorFinal, 10) || 0;
    const duracion = 400;
    const inicio = performance.now();

    function paso(ahora) {
        const progreso = Math.min((ahora - inicio) / duracion, 1);
        const valorActual = Math.round(valorInicial + (destino - valorInicial) * progreso);
        elemento.textContent = valorActual;
        if (progreso < 1) requestAnimationFrame(paso);
    }
    requestAnimationFrame(paso);
}

document.addEventListener("click", function (evento) {
    if (document.startViewTransition) return;

    const enlace = evento.target.closest("a[href]");
    if (!enlace) return;
    if (enlace.target === "_blank" || enlace.hasAttribute("download")) return;

    let destino;
    try {
        destino = new URL(enlace.href, window.location.href);
    } catch (e) {
        return;
    }

    if (destino.origin !== window.location.origin) return;
    if (destino.href.split("#")[0] === window.location.href.split("#")[0]) return;

    sonidoTransicion();
    evento.preventDefault();
    document.body.classList.add("pagina-saliendo");
    setTimeout(function () {
        window.location.href = enlace.href;
    }, 150);
});