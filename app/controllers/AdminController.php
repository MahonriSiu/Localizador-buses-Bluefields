<?php
require_once(__DIR__ . "/../models/Usuario.php");
require_once(__DIR__ . "/../models/Bus.php");
require_once(__DIR__ . "/../models/Emisor.php");
require_once(__DIR__ . "/../models/Configuracion.php");
require_once(__DIR__ . "/../models/SolicitudReseteo.php");

class AdminController {
    private $modeloUsuario;
    private $modeloBus;
    private $modeloEmisor;
    private $modeloConfiguracion;
    private $modeloSolicitud;

    public function __construct($conexion) {
        $this->modeloUsuario = new Usuario($conexion);
        $this->modeloBus = new Bus($conexion);
        $this->modeloEmisor = new Emisor($conexion);
        $this->modeloConfiguracion = new Configuracion($conexion);
        $this->modeloSolicitud = new SolicitudReseteo($conexion);
    }

    public function iniciarSesion($correo, $contrasena) {
        session_start();
        header("Content-Type: application/json");

        $usuario = $this->modeloUsuario->verificarLogin($correo, $contrasena);

        if ($usuario && $usuario['rol'] === 'admin') {
            session_regenerate_id(true);
            $_SESSION['admin_autenticado'] = true;
            $_SESSION['usuario_id'] = $usuario['id'];
            echo json_encode(array("exito" => true));
        } else {
            echo json_encode(array("exito" => false, "mensaje" => "Correo o contrasena incorrectos"));
        }
    }

    private function verificarSesion() {
        session_start();
        if (!isset($_SESSION['admin_autenticado'])) {
            header("Content-Type: application/json");
            echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
            exit;
        }
    }

    public function obtenerResumen() {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $buses = $this->modeloBus->obtenerTodos();

        $habilitados = 0;
        $transmitiendo = 0;
        foreach ($buses as $b) {
            if ($b['habilitado'] == 1) $habilitados++;
            if ($b['activo'] == 1) $transmitiendo++;
        }

        echo json_encode(array(
            "total_buses" => count($buses),
            "buses_habilitados" => $habilitados,
            "buses_transmitiendo" => $transmitiendo
        ));
    }

    public function crearCuenta($nombre, $correo, $rol) {
        $this->verificarSesion();
        header("Content-Type: application/json");

        if (!in_array($rol, ['admin', 'auditor', 'propietario'])) {
            echo json_encode(array("exito" => false, "mensaje" => "Rol invalido"));
            return;
        }

        if (trim($nombre) === '' || trim($correo) === '') {
            echo json_encode(array("exito" => false, "mensaje" => "Nombre y correo son obligatorios"));
            return;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array("exito" => false, "mensaje" => "El correo no tiene un formato valido"));
            return;
        }

        $existente = $this->modeloUsuario->obtenerPorCorreo($correo);
        if ($existente) {
            echo json_encode(array("exito" => false, "mensaje" => "Ese correo ya esta registrado en el sistema"));
            return;
        }

        $id = $this->modeloUsuario->crearConHistorialVisible($nombre, $correo, "admin123", $rol);
        echo json_encode(array("exito" => true, "id" => $id));
    }

    public function obtenerCuentas() {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $auditores = $this->modeloUsuario->obtenerPorRol('auditor');
        $propietarios = $this->modeloUsuario->obtenerPorRol('propietario');

        echo json_encode(array(
            "auditores" => $auditores,
            "propietarios" => $propietarios
        ));
    }

    public function verHistorialContrasenas($usuarioId) {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $historial = $this->modeloUsuario->obtenerHistorialContrasenas($usuarioId);
        echo json_encode(array("exito" => true, "historial" => $historial));
    }

    public function resetearContrasena($usuarioId) {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $nuevaContrasena = $this->generarContrasenaAleatoria();
        $this->modeloUsuario->cambiarContrasena($usuarioId, $nuevaContrasena);

        echo json_encode(array("exito" => true, "nueva_contrasena" => $nuevaContrasena));
    }

    private function generarContrasenaAleatoria() {
        return substr(str_shuffle("ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789"), 0, 8);
    }

    public function obtenerSolicitudesReseteo() {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $solicitudes = $this->modeloSolicitud->obtenerPendientes();
        echo json_encode(array("solicitudes" => $solicitudes));
    }

    public function atenderSolicitud($id) {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $this->modeloSolicitud->marcarAtendida($id);
        echo json_encode(array("exito" => true));
    }

    public function crearBus($nombre, $origen, $destino, $propietarioId) {
        $this->verificarSesion();
        header("Content-Type: application/json");

        if (trim($nombre) === '' || trim($origen) === '' || trim($destino) === '') {
            echo json_encode(array("exito" => false, "mensaje" => "Nombre, origen y destino son obligatorios"));
            return;
        }

        if ($propietarioId <= 0) {
            echo json_encode(array("exito" => false, "mensaje" => "Debes seleccionar un propietario valido. Crea uno primero en Cuentas."));
            return;
        }

        $busId = $this->modeloBus->crear($nombre, $origen, $destino, $propietarioId);
        $codigo = $this->modeloEmisor->crear($busId);

        echo json_encode(array("exito" => true, "bus_id" => $busId, "codigo_acceso" => $codigo));
    }

    public function toggleBus($id, $habilitado) {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $this->modeloBus->toggleHabilitado($id, $habilitado);
        echo json_encode(array("exito" => true));
    }

    public function eliminarBus($id) {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $this->modeloBus->eliminar($id);
        echo json_encode(array("exito" => true));
    }

    public function obtenerBusesCompletos() {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $buses = $this->modeloBus->obtenerTodosCompletos();
        echo json_encode(array("buses" => $buses));
    }

    public function configurarHorario($horaApertura, $horaCierre) {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $this->modeloConfiguracion->actualizarHorario($horaApertura, $horaCierre);
        echo json_encode(array("exito" => true));
    }

    public function obtenerHorario() {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $config = $this->modeloConfiguracion->obtener();
        echo json_encode(array("exito" => true, "config" => $config));
    }
}
?>