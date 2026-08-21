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

    // el admin siempre crea con la contrasena por defecto, el usuario la cambia despues
    public function crearConHistorialVisible($nombre, $correo, $contrasenaPlano, $rol) {
        $hash = password_hash($contrasenaPlano, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol, debe_cambiar_contrasena) VALUES (?, ?, ?, ?, TRUE)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $correo, $hash, $rol);
        $stmt->execute();
        $nuevoId = $this->conexion->insert_id;

        if ($rol === 'auditor' || $rol === 'propietario') {
            $this->guardarEnHistorial($nuevoId, $contrasenaPlano);
        }

        return $nuevoId;
    }

    // esta la usa el admin cuando resetea desde su panel
    public function cambiarContrasena($usuarioId, $contrasenaNuevaPlano) {
        $usuario = $this->obtenerPorId($usuarioId);
        if (!$usuario) return false;

        $hash = password_hash($contrasenaNuevaPlano, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET contrasena = ?, debe_cambiar_contrasena = TRUE WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $hash, $usuarioId);
        $stmt->execute();

        if ($usuario['rol'] === 'auditor' || $usuario['rol'] === 'propietario') {
            $this->guardarEnHistorial($usuarioId, $contrasenaNuevaPlano);
        }

        return true;
    }

    // esta la usa el usuario mismo, pidiendo su contrasena actual primero
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

        if ($usuario['rol'] === 'auditor' || $usuario['rol'] === 'propietario') {
            $this->guardarEnHistorial($usuarioId, $contrasenaNueva);
        }

        return "exito";
    }

    private function guardarEnHistorial($usuarioId, $contrasenaPlano) {
        $sql = "INSERT INTO historial_contrasenas (usuario_id, contrasena_anterior) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("is", $usuarioId, $contrasenaPlano);
        $stmt->execute();
    }

    public function obtenerHistorialContrasenas($usuarioId) {
        $sql = "SELECT contrasena_anterior, fecha_cambio FROM historial_contrasenas WHERE usuario_id = ? ORDER BY fecha_cambio DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $historial = array();
        while ($fila = $resultado->fetch_assoc()) {
            $historial[] = $fila;
        }
        return $historial;
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
}
?>