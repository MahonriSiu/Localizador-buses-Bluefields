<?php
class RegistroAcceso {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrar($tipoUsuario, $usuarioFinalId = null) {
        $sql = "INSERT INTO registro_accesos (tipo_usuario, usuario_final_id) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $tipoUsuario, $usuarioFinalId);
        return $stmt->execute();
    }

    public function contarAccesosHoy() {
        $sql = "SELECT COUNT(*) AS total FROM registro_accesos WHERE DATE(fecha_hora) = CURDATE()";
        $resultado = $this->conexion->query($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'];
    }

    public function contarRegistrosUsuariosFinales() {
        $sql = "SELECT COUNT(*) AS total FROM usuarios_finales";
        $resultado = $this->conexion->query($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'];
    }

    public function obtenerHistorialUsuariosFinales($limite = 300) {
        $sql = "SELECT r.tipo_usuario, r.fecha_hora, u.nombre, u.telefono
                FROM registro_accesos r
                INNER JOIN usuarios_finales u ON u.id = r.usuario_final_id
                WHERE r.usuario_final_id IS NOT NULL
                ORDER BY r.fecha_hora DESC
                LIMIT ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $historial = array();
        while ($fila = $resultado->fetch_assoc()) {
            $historial[] = $fila;
        }
        return $historial;
    }
}
?>