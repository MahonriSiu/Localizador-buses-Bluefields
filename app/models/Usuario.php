<?php
class Usuario {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function verificarLogin($correo, $contrasena) {
        $sql = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return $usuario;
        }
        return null;
    }

    public function crearConHistorialVisible($nombre, $correo, $contrasenaPlano, $rol) {
        $hash = password_hash($contrasenaPlano, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol, debe_cambiar_contrasena) VALUES (?, ?, ?, ?, TRUE)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $correo, $hash, $rol);
        $stmt->execute();
        return $this->conexion->insert_id;
    }

    public function cambiarContrasena($usuarioId, $contrasenaNuevaPlano) {
        $usuario = $this->obtenerPorId($usuarioId);
        if (!$usuario) return false;

        $hash = password_hash($contrasenaNuevaPlano, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET contrasena = ?, debe_cambiar_contrasena = TRUE WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $hash, $usuarioId);
        $stmt->execute();

        return true;
    }

    public function cambiarContrasenaPropia($usuarioId, $contrasenaActual, $contrasenaNueva) {
        $usuario = $this->obtenerPorId($usuarioId);
        if (!$usuario) return "usuario_no_encontrado";

        if (!password_verify($contrasenaActual, $usuario['contrasena'])) {
            return "contrasena_actual_incorrecta";
        }

        $hash = password_hash($contrasenaNueva, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET contrasena = ?, debe_cambiar_contrasena = FALSE WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $hash, $usuarioId);
        $stmt->execute();

        return "exito";
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function obtenerPorCorreo($correo) {
        $sql = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function obtenerPorRol($rol) {
        $sql = "SELECT * FROM usuarios WHERE rol = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $rol);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuarios = array();
        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }
        return $usuarios;
    }

    public function actualizarNombre($id, $nombre) {
        $sql = "UPDATE usuarios SET nombre = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nombre, $id);
        return $stmt->execute();
    }

    public function actualizarFotoPerfil($id, $rutaFoto) {
        $sql = "UPDATE usuarios SET foto_perfil = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $rutaFoto, $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>