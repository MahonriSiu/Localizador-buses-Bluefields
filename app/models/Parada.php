<?php
class Parada {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerTodas() {
        $sql = "SELECT * FROM paradas ORDER BY id ASC";
        $resultado = $this->conexion->query($sql);
        $paradas = array();
        while ($fila = $resultado->fetch_assoc()) {
            $paradas[] = $fila;
        }
        return $paradas;
    }

    public function crear($nombre, $lat, $lng) {
        $sql = "INSERT INTO paradas (nombre, lat, lng, orden) VALUES (?, ?, ?, 0)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sdd", $nombre, $lat, $lng);
        $stmt->execute();
        return $this->conexion->insert_id;
    }

    public function eliminar($id) {
        $sql = "DELETE FROM paradas WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>