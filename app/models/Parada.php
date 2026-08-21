<?php
class Parada {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerPorBus($busId) {
        $sql = "SELECT * FROM paradas WHERE bus_id = ? ORDER BY orden ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $busId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $paradas = array();
        while ($fila = $resultado->fetch_assoc()) {
            $paradas[] = $fila;
        }
        return $paradas;
    }

    public function crear($busId, $nombre, $lat, $lng, $orden) {
        $sql = "INSERT INTO paradas (bus_id, nombre, lat, lng, orden) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("isddi", $busId, $nombre, $lat, $lng, $orden);
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