<?php
class Bus {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // "conectado" es un estado calculado en el momento, no una bandera guardada:
    // solo se considera en vivo si transmitio en los ultimos 2 minutos
    private $selectConectado = "(activo = 1 AND timestamp_actualizacion > (NOW() - INTERVAL 2 MINUTE)) AS conectado";

    public function obtenerPorId($id) {
        $sql = "SELECT *, {$this->selectConectado} FROM buses WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function obtenerTodosCompletos() {
        $sql = "SELECT buses.*, usuarios.nombre AS nombre_propietario, {$this->selectConectado}
                FROM buses
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
        $sql = "SELECT *, {$this->selectConectado} FROM buses";
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
        $sql = "SELECT *, {$this->selectConectado} FROM buses WHERE propietario_id = ?";
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

    // valida que las coordenadas caigan dentro de Nicaragua antes de guardar nada.
    // rechaza 0,0 (error clasico de GPS sin señal) y coordenadas absurdas.
    public static function coordenadasValidas($lat, $lng) {
        if ($lat == 0 && $lng == 0) return false;
        if ($lat < 10.5 || $lat > 15.5) return false;
        if ($lng < -87.7 || $lng > -82.5) return false;
        return true;
    }

    public function actualizarPosicion($id, $lat, $lng) {
        if (!self::coordenadasValidas($lat, $lng)) return false;

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

    public function perteneceAPropietario($busId, $propietarioId) {
        $sql = "SELECT id FROM buses WHERE id = ? AND propietario_id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $busId, $propietarioId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->num_rows > 0;
    }
}
?>