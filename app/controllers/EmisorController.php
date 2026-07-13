<?php

require_once(__DIR__ . "/../models/Emisor.php");

class EmisorController {

    private $modeloEmisor;

    public function __construct($conexion) {
        $this->modeloEmisor = new Emisor($conexion);
    }

    public function iniciarSesion($codigo) {
        session_start();

        $emisor = $this->modeloEmisor->verificarCodigo($codigo);

        header("Content-Type: application/json");

        if ($emisor) {
            $_SESSION['emisor_autenticado'] = true;
            $_SESSION['bus_id'] = $emisor['bus_id'];
            echo json_encode(array("exito" => true));
        } else {
            echo json_encode(array("exito" => false, "mensaje" => "Codigo invalido"));
        }
    }

    public function recibirUbicacion($lat, $lng) {
        session_start();

        header("Content-Type: application/json");

        if (!isset($_SESSION['emisor_autenticado'])) {
            echo json_encode(array("exito" => false, "mensaje" => "No autorizado"));
            return;
        }

        $busId = $_SESSION['bus_id'];
        $resultado = $this->modeloEmisor->actualizarPosicion($busId, $lat, $lng);

        echo json_encode(array("exito" => $resultado));
    }

}

?>