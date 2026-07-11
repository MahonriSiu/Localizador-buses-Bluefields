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

    public function obtenerTodos() {
        $sql = "SELECT * FROM buses";
        $resultado = $this->conexion->query($sql);
        $buses = array();
        while ($fila = $resultado->fetch_assoc()) {
            $buses[] = $fila;
        }
        return $buses;
    }

}

?>