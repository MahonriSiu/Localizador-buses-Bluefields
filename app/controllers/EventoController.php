<?php
require_once(__DIR__ . "/../models/Evento.php");

class EventoController {
    private $modeloEvento;

    public function __construct($conexion) {
        $this->modeloEvento = new Evento($conexion);
    }

    public function obtenerActivosPublico() {
        header("Content-Type: application/json");
        echo json_encode(array("eventos" => $this->modeloEvento->obtenerActivosPublico()));
    }

    private function verificarSesionAdmin() {
        session_start();
        if (!isset($_SESSION['admin_autenticado'])) {
            header("Content-Type: application/json");
            echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
            exit;
        }
    }

    public function obtenerTodosAdmin() {
        $this->verificarSesionAdmin();
        header("Content-Type: application/json");
        echo json_encode(array("eventos" => $this->modeloEvento->obtenerTodosAdmin()));
    }

    public function crear($nombre, $color, $fechaInicio, $fechaFin, $siempreActivo) {
        $this->verificarSesionAdmin();
        header("Content-Type: application/json");

        if (trim($nombre) === '') {
            echo json_encode(array("exito" => false, "mensaje" => "El nombre del evento es obligatorio"));
            return;
        }
        if (!$siempreActivo && (trim($fechaInicio) === '' || trim($fechaFin) === '')) {
            echo json_encode(array("exito" => false, "mensaje" => "Selecciona el rango de fechas, o marca el evento como permanente"));
            return;
        }

        $inicio = $siempreActivo ? null : $fechaInicio;
        $fin = $siempreActivo ? null : $fechaFin;

        $id = $this->modeloEvento->crear($nombre, $color, $inicio, $fin, $siempreActivo);
        echo json_encode(array("exito" => true, "id" => $id));
    }

    public function actualizar($id, $nombre, $color, $fechaInicio, $fechaFin, $siempreActivo) {
        $this->verificarSesionAdmin();
        header("Content-Type: application/json");

        if (trim($nombre) === '') {
            echo json_encode(array("exito" => false, "mensaje" => "El nombre del evento es obligatorio"));
            return;
        }

        $inicio = $siempreActivo ? null : $fechaInicio;
        $fin = $siempreActivo ? null : $fechaFin;

        $this->modeloEvento->actualizar($id, $nombre, $color, $inicio, $fin, $siempreActivo);
        echo json_encode(array("exito" => true));
    }

    public function toggle($id, $habilitado) {
        $this->verificarSesionAdmin();
        header("Content-Type: application/json");
        $this->modeloEvento->toggleHabilitado($id, $habilitado);
        echo json_encode(array("exito" => true));
    }

    public function eliminar($id) {
        $this->verificarSesionAdmin();
        header("Content-Type: application/json");
        $this->modeloEvento->eliminar($id);
        echo json_encode(array("exito" => true));
    }
}
?>