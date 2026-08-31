async function cargarPerfil() {
    const resultado = await llamarApi(URL_BASE + "/perfil_obtener.php", {});
    if (!resultado.exito) return;

    const inicial = resultado.nombre.charAt(0).toUpperCase();

    document.getElementById("perfil-nombre-visible").textContent = resultado.nombre;
    document.getElementById("perfil-menu-nombre").textContent = resultado.nombre;
    document.getElementById("perfil-menu-correo").textContent = resultado.correo;
    document.getElementById("perfil-menu-rol").textContent = etiquetaRol(resultado.rol);
    document.getElementById("perfil-nombre-input").value = resultado.nombre;

    const imgBoton = document.getElementById("perfil-avatar-img");
    const inicialBoton = document.getElementById("perfil-avatar-inicial");
    const imgGrande = document.getElementById("perfil-foto-grande");
    const inicialGrande = document.getElementById("perfil-inicial-grande");

    if (resultado.foto_perfil) {
        const urlFoto = URL_BASE + "/asset.php?tipo=perfil&archivo=" + resultado.foto_perfil;
        imgBoton.src = urlFoto; imgBoton.style.display = "block"; inicialBoton.style.display = "none";
        imgGrande.src = urlFoto; imgGrande.style.display = "block"; inicialGrande.style.display = "none";
    } else {
        inicialBoton.textContent = inicial; inicialBoton.style.display = "flex"; imgBoton.style.display = "none";
        inicialGrande.textContent = inicial; inicialGrande.style.display = "flex"; imgGrande.style.display = "none";
    }
}

function etiquetaRol(rol) {
    if (rol === 'admin') return 'Administrador';
    if (rol === 'auditor') return 'Auditor';
    if (rol === 'propietario') return 'Propietario';
    return rol;
}

function togglePerfilMenu() {
    const menu = document.getElementById("perfil-menu");
    menu.classList.toggle("visible");
}

function mostrarModalPerfil() {
    document.getElementById("perfil-menu").classList.remove("visible");
    document.getElementById("modal-perfil").classList.add("visible");
}

function cerrarModalPerfil() {
    document.getElementById("modal-perfil").classList.remove("visible");
}

async function guardarNombrePerfil() {
    const nombre = document.getElementById("perfil-nombre-input").value;
    const resultado = await llamarApi(URL_BASE + "/perfil_actualizar_nombre.php", { nombre: nombre });
    if (resultado.exito) {
        mostrarMensaje("mensaje-perfil", "Nombre actualizado", false);
        cargarPerfil();
    } else {
        mostrarMensaje("mensaje-perfil", resultado.mensaje, true);
    }
}

async function cambiarContrasenaPerfil() {
    const actual = document.getElementById("perfil-contrasena-actual").value;
    const nueva = document.getElementById("perfil-contrasena-nueva").value;
    const resultado = await llamarApi(URL_BASE + "/perfil_cambiar_contrasena.php", {
        contrasena_actual: actual, contrasena_nueva: nueva
    });

    if (resultado.exito) {
        mostrarMensaje("mensaje-perfil", "Contrasena actualizada correctamente", false);
        document.getElementById("perfil-contrasena-actual").value = "";
        document.getElementById("perfil-contrasena-nueva").value = "";
    } else {
        mostrarMensaje("mensaje-perfil", resultado.mensaje, true);
    }
}

async function subirFotoPerfil(input) {
    if (!input.files || !input.files[0]) return;

    const formData = new FormData();
    formData.append("foto", input.files[0]);

    try {
        const respuesta = await fetch(URL_BASE + "/perfil_subir_foto.php", { method: "POST", body: formData });
        const resultado = await respuesta.json();

        if (resultado.exito) {
            mostrarMensaje("mensaje-perfil", "Foto de perfil actualizada", false);
            cargarPerfil();
        } else {
            mostrarMensaje("mensaje-perfil", resultado.mensaje, true);
        }
    } catch (error) {
        mostrarMensaje("mensaje-perfil", "No se pudo subir la imagen", true);
    }
}

function cerrarSesionActual() {
    window.location.href = URL_BASE + "/cerrar_sesion.php";
}

document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("perfil-widget")) {
        cargarPerfil();
        document.getElementById("input-foto-perfil").addEventListener("change", function () {
            subirFotoPerfil(this);
        });
    }

    document.addEventListener("click", function (evento) {
        const widget = document.getElementById("perfil-widget");
        if (widget && !widget.contains(evento.target)) {
            document.getElementById("perfil-menu").classList.remove("visible");
        }
    });
});