<?php
class SolicitudReseteo {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function crear($correo) {
        $sql = "INSERT INTO solicitudes_reseteo (correo) VALUES (?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $correo);
        return $stmt->execute();
    }

    public function obtenerPendientes() {
        $sql = "SELECT * FROM solicitudes_reseteo WHERE atendida = FALSE ORDER BY fecha_solicitud ASC";
        $resultado = $this->conexion->query($sql);
        $solicitudes = array();
        while ($fila = $resultado->fetch_assoc()) {
            $solicitudes[] = $fila;
        }
        return $solicitudes;
    }

    public function marcarAtendida($id) {
        $sql = "UPDATE solicitudes_reseteo SET atendida = TRUE WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>