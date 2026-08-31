<?php
require_once(__DIR__ . "/../models/Resena.php");

class ResenaController {
    private $modeloResena;

    public function __construct($conexion) {
        $this->modeloResena = new Resena($conexion);
    }

    public function enviar($nombre, $comentario, $calificacion) {
        header("Content-Type: application/json");

        if (trim($comentario) === '') {
            echo json_encode(array("exito" => false, "mensaje" => "Escribe tu comentario antes de enviar"));
            return;
        }

        $nombreFinal = trim($nombre) !== '' ? $nombre : 'Anonimo';
        $calificacionFinal = ($calificacion > 0 && $calificacion <= 5) ? $calificacion : null;

        $this->modeloResena->crear($nombreFinal, $comentario, $calificacionFinal);
        echo json_encode(array("exito" => true));
    }
}
?>