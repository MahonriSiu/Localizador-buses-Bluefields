<?php
require_once(__DIR__ . "/../models/Usuario.php");
require_once(__DIR__ . "/../models/Bus.php");
require_once(__DIR__ . "/../models/Emisor.php");
require_once(__DIR__ . "/../models/Configuracion.php");
require_once(__DIR__ . "/../models/SolicitudReseteo.php");
require_once(__DIR__ . "/../models/UsuarioFinal.php");
require_once(__DIR__ . "/../models/Resena.php");

define('CORREO_ADMIN_PRINCIPAL', 'mahonrisiu@gmail.com');
class AdminController {
    private $modeloUsuario;
    private $modeloBus;
    private $modeloEmisor;
    private $modeloConfiguracion;
    private $modeloSolicitud;
    private $modeloUsuarioFinal;
    private $modeloResena;

    public function __construct($conexion) {
        $this->modeloUsuario = new Usuario($conexion);
        $this->modeloBus = new Bus($conexion);
        $this->modeloEmisor = new Emisor($conexion);
        $this->modeloConfiguracion = new Configuracion($conexion);
        $this->modeloSolicitud = new SolicitudReseteo($conexion);
        $this->modeloUsuarioFinal = new UsuarioFinal($conexion);
        $this->modeloResena = new Resena($conexion);
    }

    public function iniciarSesion($correo, $contrasena) {
        session_start();
        header("Content-Type: application/json");

        $usuario = $this->modeloUsuario->verificarLogin($correo, $contrasena);

        if ($usuario && $usuario['rol'] === 'admin') {
            session_regenerate_id(true);
            $_SESSION['admin_autenticado'] = true;
            $_SESSION['usuario_id'] = $usuario['id'];
            echo json_encode(array("exito" => true, "debe_cambiar" => (bool)$usuario['debe_cambiar_contrasena']));
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

        $admins = $this->modeloUsuario->obtenerPorRol('admin');
        $auditores = $this->modeloUsuario->obtenerPorRol('auditor');
        $propietarios = $this->modeloUsuario->obtenerPorRol('propietario');

        echo json_encode(array(
            "admins" => $admins,
            "auditores" => $auditores,
            "propietarios" => $propietarios,
            "total_cuentas" => count($admins) + count($auditores) + count($propietarios)
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

        $objetivo = $this->modeloUsuario->obtenerPorId($usuarioId);
        if (!$objetivo) {
            echo json_encode(array("exito" => false, "mensaje" => "Usuario no encontrado"));
            return;
        }

        if ($objetivo['rol'] === 'admin') {
            $solicitante = $this->modeloUsuario->obtenerPorId($_SESSION['usuario_id']);
            if (strtolower($solicitante['correo']) !== CORREO_ADMIN_PRINCIPAL) {
                echo json_encode(array("exito" => false, "mensaje" => "Solo el administrador principal puede resetear la contrasena de otro admin"));
                return;
            }
        }

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

    public function crearBus($nombre, $origen, $destino, $descripcion, $propietarioId) {
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

        $busId = $this->modeloBus->crear($nombre, $origen, $destino, $descripcion, $propietarioId);
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

    public function obtenerCodigoEmisor($busId) {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $emisor = $this->modeloEmisor->obtenerPorBus($busId);
        if ($emisor) {
            echo json_encode(array("exito" => true, "codigo" => $emisor['codigo_acceso']));
        } else {
            echo json_encode(array("exito" => false, "mensaje" => "Este bus no tiene codigo asignado"));
        }
    }

    public function regenerarCodigoEmisor($busId) {
        $this->verificarSesion();
        header("Content-Type: application/json");

        $codigo = $this->modeloEmisor->regenerarCodigo($busId);
        echo json_encode(array("exito" => true, "codigo" => $codigo));
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
    
            public function guardarRutaBus($busId, $puntosJson, $color) {
        $this->verificarSesion();
        header("Content-Type: application/json");

        require_once(__DIR__ . "/../models/RutaBus.php");
        $modeloRuta = new RutaBus($GLOBALS['conexion']);

        if ($busId <= 0 || trim($puntosJson) === '') {
            echo json_encode(array("exito" => false, "mensaje" => "Faltan datos de la ruta"));
            return;
        }

        $colorFinal = preg_match('/^#[0-9A-Fa-f]{6}$/', $color) ? $color : '#F27127';
        $modeloRuta->guardar($busId, $puntosJson, $colorFinal);
        echo json_encode(array("exito" => true));
    }

    public function obtenerRutaBusAdmin($busId) {
        $this->verificarSesion();
        header("Content-Type: application/json");

        require_once(__DIR__ . "/../models/RutaBus.php");
        $modeloRuta = new RutaBus($GLOBALS['conexion']);
        $ruta = $modeloRuta->obtenerPorBus($busId);

        echo json_encode(array(
            "puntos" => $ruta ? json_decode($ruta['puntos']) : array(),
            "color" => $ruta ? $ruta['color'] : '#F27127'
        ));
    }
}
?>