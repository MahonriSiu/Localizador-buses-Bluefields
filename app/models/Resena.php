<?php
class Resena {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function crear($nombre, $comentario, $calificacion) {
        $sql = "INSERT INTO resenas (nombre, comentario, calificacion) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $comentario, $calificacion);
        return $stmt->execute();
    }

    public function obtenerTodas() {
        $sql = "SELECT id, nombre, comentario, calificacion, fecha_hora FROM resenas ORDER BY fecha_hora DESC";
        $resultado = $this->conexion->query($sql);
        $resenas = array();
        while ($fila = $resultado->fetch_assoc()) {
            $resenas[] = $fila;
        }
        return $resenas;
    }
}
?>