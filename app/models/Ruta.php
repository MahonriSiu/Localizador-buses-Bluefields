<?php

class Ruta {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerTodas() {
        $sql = "SELECT * FROM rutas";
        $resultado = $this->conexion->query($sql);
        $rutas = array();
        while ($fila = $resultado->fetch_assoc()) {
            $rutas[] = $fila;
        }
        return $rutas;
    }

}

?>