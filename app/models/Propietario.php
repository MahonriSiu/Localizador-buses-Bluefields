<?php
class Propietario {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerTodos() {
        $sql = "SELECT id, nombre, correo FROM usuarios WHERE rol = 'propietario'";
        $resultado = $this->conexion->query($sql);
        $propietarios = array();
        while ($fila = $resultado->fetch_assoc()) {
            $propietarios[] = $fila;
        }
        return $propietarios;
    }

    public function obtenerBusesDePropietario($propietarioId) {
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
}
?>