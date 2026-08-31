<?php
class Emisor {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function verificarCodigo($codigo) {
        $sql = "SELECT * FROM emisores WHERE codigo_acceso = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function crear($busId) {
        $codigo = strtoupper(substr(md5(uniqid()), 0, 6));
        $sql = "INSERT INTO emisores (codigo_acceso, bus_id) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $codigo, $busId);
        $stmt->execute();
        return $codigo;
    }

    public function obtenerPorBus($busId) {
        $sql = "SELECT * FROM emisores WHERE bus_id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $busId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // si el chofer perdio el codigo, esto genera uno nuevo sin tener que crear un bus nuevo
    public function regenerarCodigo($busId) {
        $existente = $this->obtenerPorBus($busId);
        $codigo = strtoupper(substr(md5(uniqid()), 0, 6));

        if ($existente) {
            $sql = "UPDATE emisores SET codigo_acceso = ? WHERE bus_id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("si", $codigo, $busId);
            $stmt->execute();
        } else {
            $sql = "INSERT INTO emisores (codigo_acceso, bus_id) VALUES (?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("si", $codigo, $busId);
            $stmt->execute();
        }

        return $codigo;
    }

    public function obtenerTodosConBus() {
        $sql = "SELECT emisores.*, buses.nombre FROM emisores
                JOIN buses ON emisores.bus_id = buses.id";
        $resultado = $this->conexion->query($sql);
        $lista = array();
        while ($fila = $resultado->fetch_assoc()) {
            $lista[] = $fila;
        }
        return $lista;
    }
}
?>