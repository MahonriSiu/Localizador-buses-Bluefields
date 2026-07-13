<?php

class Emisor {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function verificarCodigo($codigo) {
        $sql = "SELECT * FROM emisores WHERE codigo_acceso = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function actualizarPosicion($busId, $lat, $lng) {
        $sql = "UPDATE buses SET lat = ?, lng = ?, timestamp_actualizacion = NOW(), activo = 1 WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ddi", $lat, $lng, $busId);
        return $stmt->execute();
    }

}

?>