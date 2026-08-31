const EXTERNO_CONFIGURADO = false;
let cicloAnuncioActivo = false;
let anunciosVistosEnCiclo = JSON.parse(sessionStorage.getItem("mibus_anuncios_vistos") || "[]");
let audioAnuncioActual = null;
let temporizadorCierreAnuncio = null;

function iniciarCicloAnuncios() {
    setTimeout(mostrarSiguienteAnuncio, 20000);
}

async function mostrarSiguienteAnuncio() {
    if (cicloAnuncioActivo) {
        programarSiguienteCiclo();
        return;
    }

    const tocaExterno = EXTERNO_CONFIGURADO && Math.random() < 0.3;
    if (tocaExterno) { mostrarAnuncioExterno(); return; }

    const candidato = await fetch(URL_BASE + "/obtener_anuncio.php");
    const resultado = await candidato.json();

    if (!resultado.hay_anuncio) {
        if (EXTERNO_CONFIGURADO) { mostrarAnuncioExterno(); } else { programarSiguienteCiclo(); }
        return;
    }

    let anuncio = resultado.anuncio;
    let intentos = 0;

    while (anunciosVistosEnCiclo.includes(anuncio.id) && intentos < 6) {
        const otro = await fetch(URL_BASE + "/obtener_anuncio.php");
        const otroResultado = await otro.json();
        if (!otroResultado.hay_anuncio) break;
        anuncio = otroResultado.anuncio;
        intentos++;
    }

    if (anunciosVistosEnCiclo.includes(anuncio.id)) {
        anunciosVistosEnCiclo = [];
    }

    anunciosVistosEnCiclo.push(anuncio.id);
    sessionStorage.setItem("mibus_anuncios_vistos", JSON.stringify(anunciosVistosEnCiclo));

    mostrarModalAnuncio(anuncio);
}

function mostrarModalAnuncio(anuncio) {
    cicloAnuncioActivo = true;
    if (temporizadorCierreAnuncio) clearTimeout(temporizadorCierreAnuncio);

    const modal = document.getElementById("modal-anuncio");
    const media = document.getElementById("anuncio-media-grande");
    const botonCerrar = document.getElementById("anuncio-cerrar");
    const botonSonido = document.getElementById("anuncio-sonido");

    document.getElementById("anuncio-titulo-grande").textContent = anuncio.nombre_negocio || "";
    document.getElementById("anuncio-texto-grande").textContent =
        [anuncio.texto, anuncio.telefono].filter(Boolean).join(" · ");

    botonCerrar.classList.remove("visible");
    botonSonido.classList.remove("visible");
    media.innerHTML = "";
    audioAnuncioActual = null;

    let esVideo = false;
    let hayAudio = false;
    let videoElemento = null;

    if (anuncio.tipo_media === 'imagen' && anuncio.url_media) {
        media.innerHTML = "<img src='" + URL_BASE + "/asset.php?tipo=anuncio&archivo=" + anuncio.url_media + "' alt=''>";
    } else if (anuncio.tipo_media === 'video' && anuncio.url_media) {
        esVideo = true;
        hayAudio = true;
        media.innerHTML = "<video id='video-anuncio-actual' src='" + URL_BASE + "/asset.php?tipo=anuncio&archivo=" + anuncio.url_media + "' autoplay muted loop playsinline preload='auto'></video>";
        videoElemento = media.querySelector("video");
    } else {
        media.innerHTML = "<div class='anuncio-media-vacia'>📣</div>";
    }

    // audio separado (independiente de si hay imagen o no)
    if (anuncio.url_audio) {
        hayAudio = true;
        audioAnuncioActual = new Audio(URL_BASE + "/asset.php?tipo=anuncio-audio&archivo=" + anuncio.url_audio);
        audioAnuncioActual.loop = true;
    }

    if (hayAudio) {
        botonSonido.classList.add("visible");
        botonSonido.textContent = "🔇";
        botonSonido.onclick = function () { alternarSonidoAnuncio(videoElemento); };
    }

    modal.classList.add("visible");

    setTimeout(function () {
        botonCerrar.classList.add("visible");
    }, 6000);

    if (!esVideo) {
        if (audioAnuncioActual) {
            audioAnuncioActual.addEventListener("loadedmetadata", function () {
                const duracionMs = Math.max(14000, (audioAnuncioActual.duration * 1000) + 1500);
                if (temporizadorCierreAnuncio) clearTimeout(temporizadorCierreAnuncio);
                temporizadorCierreAnuncio = setTimeout(cerrarModalAnuncio, duracionMs);
            });
            audioAnuncioActual.load();
        } else {
            temporizadorCierreAnuncio = setTimeout(cerrarModalAnuncio, 14000);
        }
    }
}

function alternarSonidoAnuncio(videoElemento) {
    const boton = document.getElementById("anuncio-sonido");
    const activo = boton.textContent === "🔇";

    if (videoElemento) {
        videoElemento.muted = !activo;
    }
    if (audioAnuncioActual) {
        if (activo) audioAnuncioActual.play().catch(function () {});
        else audioAnuncioActual.pause();
    }

    boton.textContent = activo ? "🔊" : "🔇";
}

function mostrarAnuncioExterno() {
    programarSiguienteCiclo();
}

function cerrarModalAnuncio() {
    if (temporizadorCierreAnuncio) clearTimeout(temporizadorCierreAnuncio);

    const modal = document.getElementById("modal-anuncio");
    modal.classList.remove("visible");

    const video = document.getElementById("anuncio-media-grande").querySelector("video");
    if (video) video.pause();
    if (audioAnuncioActual) { audioAnuncioActual.pause(); audioAnuncioActual = null; }

    cicloAnuncioActivo = false;
    programarSiguienteCiclo();
}

function programarSiguienteCiclo() {
    cicloAnuncioActivo = false;
    const espera = 20000 + Math.floor(Math.random() * 10000);
    setTimeout(mostrarSiguienteAnuncio, espera);
}

document.addEventListener("DOMContentLoaded", function () {
    const botonCerrar = document.getElementById("anuncio-cerrar");
    if (botonCerrar) botonCerrar.addEventListener("click", cerrarModalAnuncio);
    iniciarCicloAnuncios();
});