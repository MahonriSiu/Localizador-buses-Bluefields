<?php
class Bus {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM buses WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function obtenerTodosCompletos() {
        $sql = "SELECT buses.*, usuarios.nombre AS nombre_propietario FROM buses
                LEFT JOIN usuarios ON buses.propietario_id = usuarios.id
                ORDER BY buses.id DESC";
        $resultado = $this->conexion->query($sql);
        $buses = array();
        while ($fila = $resultado->fetch_assoc()) {
            $buses[] = $fila;
        }
        return $buses;
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM buses";
        $resultado = $this->conexion->query($sql);
        $buses = array();
        while ($fila = $resultado->fetch_assoc()) {
            $buses[] = $fila;
        }
        return $buses;
    }

    public function obtenerPublicos() {
        $sql = "SELECT id, nombre, origen, destino, descripcion FROM buses WHERE habilitado = 1";
        $resultado = $this->conexion->query($sql);
        $buses = array();
        while ($fila = $resultado->fetch_assoc()) {
            $buses[] = $fila;
        }
        return $buses;
    }

    public function obtenerPorPropietario($propietarioId) {
        $sql = "SELECT * FROM buses WHERE propietario_id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $propietarioId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $buses = array();
        while ($fila = $resultado->fetch_assoc()) {
            $buses[] = $fila;
        }
        return $buses;
    }

    public function actualizarPosicion($id, $lat, $lng) {
        $sql = "UPDATE buses SET lat = ?, lng = ?, timestamp_actualizacion = NOW(), activo = 1 WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ddi", $lat, $lng, $id);
        return $stmt->execute();
    }

    public function crear($nombre, $origen, $destino, $descripcion, $propietarioId) {
        $sql = "INSERT INTO buses (nombre, origen, destino, descripcion, propietario_id, habilitado) VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssi", $nombre, $origen, $destino, $descripcion, $propietarioId);
        $stmt->execute();
        return $this->conexion->insert_id;
    }

    public function toggleHabilitado($id, $habilitado) {
        $sql = "UPDATE buses SET habilitado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $habilitado, $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $sql = "DELETE FROM buses WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>