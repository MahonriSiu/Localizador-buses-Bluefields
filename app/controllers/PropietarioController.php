<?php
require_once(__DIR__ . "/../models/Usuario.php");
require_once(__DIR__ . "/../models/Propietario.php");
require_once(__DIR__ . "/../models/HistorialPosicion.php");
require_once(__DIR__ . "/../models/Bus.php");

class PropietarioController {
    private $modeloUsuario;
    private $modeloPropietario;
    private $modeloHistorial;
    private $modeloBus;

    public function __construct($conexion) {
        $this->modeloUsuario = new Usuario($conexion);
        $this->modeloPropietario = new Propietario($conexion);
        $this->modeloHistorial = new HistorialPosicion($conexion);
        $this->modeloBus = new Bus($conexion);
    }

    public function iniciarSesion($correo, $contrasena) {
        session_start();
        header("Content-Type: application/json");

        if ($this->modeloUsuario->contarIntentosFallidosRecientes($correo) >= 5) {
            echo json_encode(array("exito" => false, "mensaje" => "Demasiados intentos fallidos. Espera 15 minutos."));
            return;
        }

        $usuario = $this->modeloUsuario->verificarLogin($correo, $contrasena);

        if ($usuario && $usuario['rol'] === 'propietario') {
            $this->modeloUsuario->limpiarIntentosFallidos($correo);
            session_regenerate_id(true);
            $_SESSION['propietario_autenticado'] = true;
            $_SESSION['propietario_id'] = $usuario['id'];
            $_SESSION['usuario_id'] = $usuario['id'];
            echo json_encode(array("exito" => true, "debe_cambiar" => (bool)$usuario['debe_cambiar_contrasena']));
        } else {
            $this->modeloUsuario->registrarIntentoFallido($correo);
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

        if (!$this->modeloBus->perteneceAPropietario($busId, $_SESSION['propietario_id'])) {
            http_response_code(403);
            echo json_encode(array("exito" => false, "mensaje" => "Este bus no te pertenece"));
            return;
        }

        $recorrido = $this->modeloHistorial->obtenerRecorridoReciente($busId);
        echo json_encode(array("recorrido" => $recorrido));
    }
}
?>