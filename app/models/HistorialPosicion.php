<?php
class HistorialPosicion {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrar($busId, $lat, $lng) {
        $sql = "INSERT INTO historial_posiciones (bus_id, lat, lng) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("idd", $busId, $lat, $lng);

        if (mt_rand(1, 100) === 1) {
            $this->limpiarRegistrosAntiguos();
        }

        return $resultado;
    }

    private function limpiarRegistrosAntiguos() {
        $sql = "DELETE FROM historial_posiciones WHERE fecha_hora < (NOW() - INTERVAL 7 DAY) LIMIT 500";
        $this->conexion->query($sql);
    }

    public function obtenerRecorridoReciente($busId, $limite = 200) {
        $sql = "SELECT lat, lng, fecha_hora FROM historial_posiciones
                WHERE bus_id = ? ORDER BY fecha_hora DESC LIMIT ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $busId, $limite);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $puntos = array();
        while ($fila = $resultado->fetch_assoc()) {
            $puntos[] = $fila;
        }
        return array_reverse($puntos);
    }

    public function calcularVelocidadPromedio($busId, $limite = 10) {
        $sql = "SELECT lat, lng, fecha_hora FROM historial_posiciones
                WHERE bus_id = ? ORDER BY fecha_hora DESC LIMIT ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $busId, $limite);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $puntos = array();
        while ($fila = $resultado->fetch_assoc()) {
            $puntos[] = $fila;
        }

        if (count($puntos) < 2) {
            return null;
        }

        $puntos = array_reverse($puntos);
        $distanciaTotalKm = 0;
        $tiempoTotalHoras = 0;

        for ($i = 1; $i < count($puntos); $i++) {
            $anterior = $puntos[$i - 1];
            $actual = $puntos[$i];

            $distanciaTotalKm += $this->haversine(
                $anterior['lat'], $anterior['lng'],
                $actual['lat'], $actual['lng']
            );

            $segundos = strtotime($actual['fecha_hora']) - strtotime($anterior['fecha_hora']);
            $tiempoTotalHoras += $segundos / 3600;
        }

        if ($tiempoTotalHoras <= 0) {
            return null;
        }

        return $distanciaTotalKm / $tiempoTotalHoras;
    }

    public function haversine($lat1, $lng1, $lat2, $lng2) {
        $radioTierra = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $radioTierra * $c;
    }

    public function obtenerPrimerRegistro($busId) {
        $sql = "SELECT MIN(fecha_hora) AS primera FROM historial_posiciones WHERE bus_id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $busId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        return $fila['primera'];
    }
}
?>