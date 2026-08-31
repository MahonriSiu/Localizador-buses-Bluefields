<?php
require_once(__DIR__ . "/../models/Usuario.php");
require_once(__DIR__ . "/../models/Bus.php");
require_once(__DIR__ . "/../models/RegistroAcceso.php");
require_once(__DIR__ . "/../models/UsuarioFinal.php");
require_once(__DIR__ . "/../models/Resena.php");

class AuditorController {
    private $modeloUsuario;
    private $modeloBus;
    private $modeloRegistroAcceso;
    private $modeloUsuarioFinal;
    private $modeloResena;

    public function __construct($conexion) {
        $this->modeloUsuario = new Usuario($conexion);
        $this->modeloBus = new Bus($conexion);
        $this->modeloRegistroAcceso = new RegistroAcceso($conexion);
        $this->modeloUsuarioFinal = new UsuarioFinal($conexion);
        $this->modeloResena = new Resena($conexion);
    }

    public function iniciarSesion($correo, $contrasena) {
        session_start();
        header("Content-Type: application/json");

        $usuario = $this->modeloUsuario->verificarLogin($correo, $contrasena);

        if ($usuario && $usuario['rol'] === 'auditor') {
            session_regenerate_id(true);
            $_SESSION['auditor_autenticado'] = true;
            $_SESSION['usuario_id'] = $usuario['id'];
            echo json_encode(array("exito" => true, "debe_cambiar" => (bool)$usuario['debe_cambiar_contrasena']));
        } else {
            echo json_encode(array("exito" => false, "mensaje" => "Correo o contrasena incorrectos"));
        }
    }

    private function verificarSesion() {
        session_start();
        if (!isset($_SESSION['auditor_autenticado'])) {
            header("Content-Type: application/json");
            echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
            exit;
        }
    }

    public function verPanel() {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $totalRegistrados = $this->modeloRegistroAcceso->contarRegistrosUsuariosFinales();
        $usosHoy = $this->modeloRegistroAcceso->contarAccesosHoy();
        $buses = $this->modeloBus->obtenerTodos();

        echo json_encode(array(
            "total_registrados" => $totalRegistrados,
            "usos_hoy" => $usosHoy,
            "buses" => $buses
        ));
    }

    public function obtenerBuses() {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $buses = $this->modeloBus->obtenerTodos();
        echo json_encode(array("buses" => $buses));
    }

    public function obtenerUsuariosFinales() {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $usuarios = $this->modeloUsuarioFinal->obtenerTodos();
        echo json_encode(array("usuarios" => $usuarios));
    }
    
        public function obtenerHistorialAccesos() {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $modeloRegistro = new RegistroAcceso($GLOBALS['conexion']);
        echo json_encode(array("historial" => $modeloRegistro->obtenerHistorialUsuariosFinales()));
    }

    public function obtenerResenas() {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $resenas = $this->modeloResena->obtenerTodas();
        echo json_encode(array("resenas" => $resenas));
    }
}
?>