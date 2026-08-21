<?php
require_once(__DIR__ . "/../models/Emisor.php");
require_once(__DIR__ . "/../models/Bus.php");
require_once(__DIR__ . "/../models/HistorialPosicion.php");

class EmisorController {
    private $modeloEmisor;
    private $modeloBus;
    private $modeloHistorial;

    public function __construct($conexion) {
        $this->modeloEmisor = new Emisor($conexion);
        $this->modeloBus = new Bus($conexion);
        $this->modeloHistorial = new HistorialPosicion($conexion);
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

    // el emisor manda esto cada 5 segundos mientras el bus esta en ruta
    public function actualizarPosicion($lat, $lng) {
        session_start();
        header("Content-Type: application/json");

        if (!isset($_SESSION['emisor_autenticado'])) {
            echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
            return;
        }

        $busId = $_SESSION['bus_id'];
        $this->modeloBus->actualizarPosicion($busId, $lat, $lng);
        $this->modeloHistorial->registrar($busId, $lat, $lng);

        echo json_encode(array("exito" => true));
    }
}
?>