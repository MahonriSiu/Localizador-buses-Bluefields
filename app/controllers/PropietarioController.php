<?php
require_once(__DIR__ . "/../models/Usuario.php");
require_once(__DIR__ . "/../models/Propietario.php");
require_once(__DIR__ . "/../models/HistorialPosicion.php");

class PropietarioController {
    private $modeloUsuario;
    private $modeloPropietario;
    private $modeloHistorial;

    public function __construct($conexion) {
        $this->modeloUsuario = new Usuario($conexion);
        $this->modeloPropietario = new Propietario($conexion);
        $this->modeloHistorial = new HistorialPosicion($conexion);
    }

    public function iniciarSesion($correo, $contrasena) {
        session_start();
        header("Content-Type: application/json");

        $usuario = $this->modeloUsuario->verificarLogin($correo, $contrasena);

        if ($usuario && $usuario['rol'] === 'propietario') {
            session_regenerate_id(true);
            $_SESSION['propietario_autenticado'] = true;
            $_SESSION['propietario_id'] = $usuario['id'];
            $_SESSION['usuario_id'] = $usuario['id'];
            echo json_encode(array("exito" => true, "debe_cambiar" => (bool)$usuario['debe_cambiar_contrasena']));
        } else {
            echo json_encode(array("exito" => false, "mensaje" => "Correo o contrasena incorrectos"));
        }
    }

    private function verificarSesion() {
        session_start();
        if (!isset($_SESSION['propietario_autenticado'])) {
            header("Content-Type: application/json");
            echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
            exit;
        }
    }

    public function verMisBuses() {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $buses = $this->modeloPropietario->obtenerBusesDePropietario($_SESSION['propietario_id']);
        echo json_encode(array("buses" => $buses));
    }

    public function verRecorridoBus($busId) {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $recorrido = $this->modeloHistorial->obtenerRecorridoReciente($busId);
        echo json_encode(array("recorrido" => $recorrido));
    }
}
?>