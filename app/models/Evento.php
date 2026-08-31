<?php
class Evento {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // publico: eventos que deben mostrarse HOY, ya sea porque son de duracion
    // permanente (Ruta Creativa) o porque la fecha de hoy cae dentro de su rango
    public function obtenerActivosPublico() {
        $sql = "SELECT * FROM eventos WHERE habilitado = 1
                AND (siempre_activo = 1 OR (fecha_inicio <= CURDATE() AND fecha_fin >= CURDATE()))
                ORDER BY siempre_activo DESC, fecha_inicio ASC";
        $resultado = $this->conexion->query($sql);
        $eventos = array();
        while ($fila = $resultado->fetch_assoc()) {
            $eventos[] = $fila;
        }
        return $eventos;
    }

    public function obtenerTodosAdmin() {
        $sql = "SELECT e.*,
                (SELECT COUNT(*) FROM puntos_ruta_creativa p WHERE p.evento_id = e.id) AS total_puntos
                FROM eventos e ORDER BY e.siempre_activo DESC, e.fecha_creacion DESC";
        $resultado = $this->conexion->query($sql);
        $eventos = array();
        while ($fila = $resultado->fetch_assoc()) {
            $eventos[] = $fila;
        }
        return $eventos;
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM eventos WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function crear($nombre, $color, $fechaInicio, $fechaFin, $siempreActivo) {
        $sql = "INSERT INTO eventos (nombre, color, fecha_inicio, fecha_fin, siempre_activo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssi", $nombre, $color, $fechaInicio, $fechaFin, $siempreActivo);
        $stmt->execute();
        return $this->conexion->insert_id;
    }

    // permite renombrar/recolorear un evento existente, incluyendo el de Ruta Creativa
    public function actualizar($id, $nombre, $color, $fechaInicio, $fechaFin, $siempreActivo) {
        $sql = "UPDATE eventos SET nombre = ?, color = ?, fecha_inicio = ?, fecha_fin = ?, siempre_activo = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssii", $nombre, $color, $fechaInicio, $fechaFin, $siempreActivo, $id);
        return $stmt->execute();
    }

    public function toggleHabilitado($id, $habilitado) {
        $sql = "UPDATE eventos SET habilitado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $habilitado, $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $sql = "DELETE FROM eventos WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>