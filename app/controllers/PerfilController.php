<?php
class PerfilController {
    private $modeloUsuario;

    public function __construct($conexion) {
        require_once(__DIR__ . "/../models/Usuario.php");
        $this->modeloUsuario = new Usuario($conexion);
    }

    private function usuarioSesionId() {
        session_start();
        if (isset($_SESSION['admin_autenticado']) && isset($_SESSION['usuario_id'])) return $_SESSION['usuario_id'];
        if (isset($_SESSION['auditor_autenticado']) && isset($_SESSION['usuario_id'])) return $_SESSION['usuario_id'];
        if (isset($_SESSION['propietario_autenticado']) && isset($_SESSION['usuario_id'])) return $_SESSION['usuario_id'];
        return null;
    }

    public function obtenerPerfil() {
        header("Content-Type: application/json");
        $id = $this->usuarioSesionId();
        if (!$id) {
            echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
            return;
        }
        $usuario = $this->modeloUsuario->obtenerPorId($id);
        echo json_encode(array(
            "exito" => true,
            "nombre" => $usuario['nombre'],
            "correo" => $usuario['correo'],
            "rol" => $usuario['rol'],
            "foto_perfil" => $usuario['foto_perfil']
        ));
    }

    public function actualizarNombre($nombreNuevo) {
        header("Content-Type: application/json");
        $id = $this->usuarioSesionId();
        if (!$id) {
            echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
            return;
        }
        if (trim($nombreNuevo) === '') {
            echo json_encode(array("exito" => false, "mensaje" => "El nombre no puede estar vacio"));
            return;
        }
        $this->modeloUsuario->actualizarNombre($id, $nombreNuevo);
        echo json_encode(array("exito" => true));
    }

    public function cambiarContrasena($actual, $nueva) {
        header("Content-Type: application/json");
        $id = $this->usuarioSesionId();
        if (!$id) {
            echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
            return;
        }
        if (strlen($nueva) < 6) {
            echo json_encode(array("exito" => false, "mensaje" => "La contrasena nueva debe tener al menos 6 caracteres"));
            return;
        }
        $resultado = $this->modeloUsuario->cambiarContrasenaPropia($id, $actual, $nueva);
        if ($resultado === "exito") {
            echo json_encode(array("exito" => true));
        } elseif ($resultado === "contrasena_actual_incorrecta") {
            echo json_encode(array("exito" => false, "mensaje" => "La contrasena actual no es correcta"));
        } else {
            echo json_encode(array("exito" => false, "mensaje" => "Error al cambiar la contrasena"));
        }
    }

    public function subirFoto() {
        header("Content-Type: application/json");
        $id = $this->usuarioSesionId();
        if (!$id) {
            echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
            return;
        }

        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(array("exito" => false, "mensaje" => "No se recibio ninguna imagen"));
            return;
        }

        $tiposPermitidos = array('image/jpeg' => 'jpg', 'image/png' => 'png');
        $tipo = mime_content_type($_FILES['foto']['tmp_name']);

        if (!array_key_exists($tipo, $tiposPermitidos)) {
            echo json_encode(array("exito" => false, "mensaje" => "Solo se permiten imagenes JPG o PNG"));
            return;
        }

        if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            echo json_encode(array("exito" => false, "mensaje" => "La imagen no puede pesar mas de 2MB"));
            return;
        }

        $extension = $tiposPermitidos[$tipo];
        $nombreArchivo = "usuario_" . $id . "_" . time() . "." . $extension;
        $carpetaDestino = __DIR__ . "/../../assets/img/perfiles";

        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0755, true);
        }

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $carpetaDestino . "/" . $nombreArchivo)) {
            $this->modeloUsuario->actualizarFotoPerfil($id, $nombreArchivo);
            echo json_encode(array("exito" => true, "foto_perfil" => $nombreArchivo));
        } else {
            echo json_encode(array("exito" => false, "mensaje" => "No se pudo guardar la imagen"));
        }
    }
}
?>