<div class="perfil-widget" id="perfil-widget">
    <span class="perfil-nombre-visible" id="perfil-nombre-visible">-</span>
    <button class="perfil-avatar-boton" id="perfil-boton" onclick="togglePerfilMenu()">
        <img id="perfil-avatar-img" src="" alt="" style="display:none;">
        <span id="perfil-avatar-inicial">?</span>
    </button>
    <div class="perfil-menu" id="perfil-menu">
        <div class="perfil-menu-info">
            <div id="perfil-menu-nombre">-</div>
            <div class="perfil-menu-correo" id="perfil-menu-correo">-</div>
            <div class="perfil-menu-rol" id="perfil-menu-rol">-</div>
        </div>
        <a href="#" onclick="mostrarModalPerfil(); return false;">Editar perfil</a>
        <a href="#" onclick="cerrarSesionActual(); return false;">Cerrar sesion</a>
    </div>
</div>

<div class="modal-overlay" id="modal-perfil">
    <div class="modal-caja">
        <h2>Mi perfil</h2>
        <div id="mensaje-perfil"></div>

        <div class="perfil-foto-actual">
            <img id="perfil-foto-grande" src="" alt="" style="display:none;">
            <span id="perfil-inicial-grande">?</span>
        </div>
        <div class="campo-formulario">
            <label>Cambiar foto de perfil</label>
            <input type="file" id="input-foto-perfil" accept="image/png, image/jpeg">
        </div>

        <div class="campo-formulario">
            <label>Nombre</label>
            <input type="text" id="perfil-nombre-input">
        </div>
        <button class="boton boton-secundario" onclick="guardarNombrePerfil()">Guardar nombre</button>

        <hr style="margin: 20px 0; border: none; border-top: 1px solid #ece2d1;">

        <div class="campo-formulario">
            <label>Contrasena actual</label>
            <input type="password" id="perfil-contrasena-actual">
        </div>
        <div class="campo-formulario">
            <label>Contrasena nueva</label>
            <input type="password" id="perfil-contrasena-nueva">
        </div>
        <button class="boton boton-primario" onclick="cambiarContrasenaPerfil()">Cambiar contrasena</button>

        <button class="boton boton-secundario boton-bloque" style="margin-top: 16px;" onclick="cerrarModalPerfil()">Cerrar</button>
    </div>
</div>