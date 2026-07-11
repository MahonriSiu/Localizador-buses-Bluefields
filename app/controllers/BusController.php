<?php

require_once(__DIR__ . "/../models/Bus.php");

class BusController {

    private $modeloBus;

    public function __construct($conexion) {
        $this->modeloBus = new Bus($conexion);
    }

    public function obtenerPosicionBus($id) {
        $bus = $this->modeloBus->obtenerPorId($id);

        if ($bus) {
            header("Content-Type: application/json");
            echo json_encode($bus);
        } else {
            header("Content-Type: application/json");
            echo json_encode(array("error" => "Bus no encontrado"));
        }
    }

}

?>