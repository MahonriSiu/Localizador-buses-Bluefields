const PASOS_TUTORIAL = [
    { selector: null, texto: "La linea roja que ves en el mapa es el Circuito Creativo: Historia y cultura en el paseo de la Autonomia. Tocala para conocer cada lugar." },
    { selector: "#selector-ruta", texto: "Aqui eliges el bus que quieres seguir en el mapa." },
    { selector: "#boton-notificaciones", texto: "Activa los avisos para saber cuando el bus esta cerca de tu parada." },
    { selector: "#boton-info-bus", texto: "Toca aqui para ver a cuantos metros esta el bus y el tiempo estimado de llegada." },
    { selector: "#boton-mi-ubicacion", texto: "Este boton te centra en tu propia ubicacion. El punto azul en el mapa eres tu." },
    { selector: "#fab-resena", texto: "Aqui puedes dejarnos tu opinion o alguna recomendacion." }
];

let pasoActualTutorial = 0;
let elementoResaltadoActual = null;

function iniciarTutorial() {
    if (localStorage.getItem("mibus_tutorial_visto") === "1") return;
    pasoActualTutorial = 0;
    construirOverlayTutorial();
    mostrarPasoTutorial(0);
}

function construirOverlayTutorial() {
    if (document.getElementById("tutorial-overlay")) return;

    const overlay = document.createElement("div");
    overlay.id = "tutorial-overlay";
    overlay.innerHTML =
        '<div class="tutorial-punto-marca" id="tutorial-punto-marca"></div>' +
        '<div class="tutorial-caja" id="tutorial-caja">' +
            '<div class="mascota-tutorial" id="mascota-tutorial">' +
                '<div class="mascota-gorra"></div>' +
                '<div class="mascota-cuerpo"></div>' +
                '<div class="mascota-brazo" id="mascota-brazo"></div>' +
                '<div class="mascota-cara">' +
                    '<div class="mascota-ojo izq"></div>' +
                    '<div class="mascota-ojo der"></div>' +
                    '<div class="mascota-boca"></div>' +
                '</div>' +
            '</div>' +
            '<div class="tutorial-texto-caja">' +
                '<div class="tutorial-globo" id="tutorial-globo"></div>' +
                '<div class="tutorial-pasos-indicador" id="tutorial-puntos"></div>' +
                '<div class="tutorial-acciones">' +
                    '<button class="tutorial-omitir" onclick="omitirTutorial()">Omitir</button>' +
                    '<button class="tutorial-siguiente" id="tutorial-boton-siguiente" onclick="siguientePasoTutorial()">Siguiente</button>' +
                '</div>' +
            '</div>' +
        '</div>';
    document.body.appendChild(overlay);

    const puntos = document.getElementById("tutorial-puntos");
    PASOS_TUTORIAL.forEach(function (_, i) {
        const p = document.createElement("div");
        p.className = "tutorial-punto";
        p.id = "punto-tutorial-" + i;
        puntos.appendChild(p);
    });
}

function mostrarPasoTutorial(indice) {
    if (elementoResaltadoActual) elementoResaltadoActual.classList.remove("tutorial-resaltado");

    const paso = PASOS_TUTORIAL[indice];
    const elemento = paso.selector ? document.querySelector(paso.selector) : null;

    document.querySelectorAll(".tutorial-punto").forEach(function (p, i) {
        p.classList.toggle("activo", i === indice);
    });

    document.getElementById("tutorial-boton-siguiente").textContent =
        indice === PASOS_TUTORIAL.length - 1 ? "Listo" : "Siguiente";

    escribirTextoTutorial(paso.texto);

    const marca = document.getElementById("tutorial-punto-marca");

    if (elemento) {
        elemento.classList.add("tutorial-resaltado");
        elementoResaltadoActual = elemento;
        const rect = elemento.getBoundingClientRect();
        marca.style.display = "block";
        marca.style.left = (rect.left + rect.width / 2 - 7) + "px";
        marca.style.top = (rect.top - 7) + "px";
    } else {
        marca.style.display = "none";
        elementoResaltadoActual = null;
    }
}

function escribirTextoTutorial(texto) {
    const globo = document.getElementById("tutorial-globo");
    globo.textContent = "";
    let i = 0;
    const velocidad = 16;
    function letra() {
        if (i < texto.length) {
            globo.textContent += texto.charAt(i);
            i++;
            setTimeout(letra, velocidad);
        }
    }
    letra();
}

function siguientePasoTutorial() {
    if (pasoActualTutorial < PASOS_TUTORIAL.length - 1) {
        pasoActualTutorial++;
        mostrarPasoTutorial(pasoActualTutorial);
    } else {
        cerrarTutorial();
    }
}

function omitirTutorial() {
    cerrarTutorial();
}

function cerrarTutorial() {
    if (elementoResaltadoActual) elementoResaltadoActual.classList.remove("tutorial-resaltado");

    const overlay = document.getElementById("tutorial-overlay");
    const caja = document.getElementById("tutorial-caja");
    if (caja) caja.classList.add("tutorial-saliendo");

    if (overlay) {
        overlay.style.transition = "opacity 0.3s ease";
        overlay.style.opacity = "0";
        setTimeout(function () { overlay.remove(); }, 320);
    }

    localStorage.setItem("mibus_tutorial_visto", "1");
    sessionStorage.removeItem("mibus_es_nuevo");
}

document.addEventListener("DOMContentLoaded", function () {
    const parametros = new URLSearchParams(window.location.search);
    const esNuevoPorUrl = parametros.get("nuevo") === "1";
    const esNuevoPorSesion = sessionStorage.getItem("mibus_es_nuevo") === "1";

    if (esNuevoPorUrl || esNuevoPorSesion) {
        setTimeout(iniciarTutorial, 900);
    }
});