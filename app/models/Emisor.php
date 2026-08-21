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
        // genero un codigo simple de 6 caracteres, facil de escribir para el que maneja el bus
        $codigo = strtoupper(substr(md5(uniqid()), 0, 6));
        $sql = "INSERT INTO emisores (codigo_acceso, bus_id) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $codigo, $busId);
        $stmt->execute();
        return $codigo;
    }

    // el admin necesita ver el codigo de un bus especifico si se pierde
    public function obtenerPorBus($busId) {
        $sql = "SELECT * FROM emisores WHERE bus_id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $busId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function obtenerTodosConBus() {
        $sql = "SELECT emisores.*, buses.ruta_id FROM emisores
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