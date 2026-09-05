<?php
require_once(__DIR__ . "/../models/Bus.php");
require_once(__DIR__ . "/../models/Parada.php");
require_once(__DIR__ . "/../models/Configuracion.php");
require_once(__DIR__ . "/../models/RegistroAcceso.php");
require_once(__DIR__ . "/../models/HistorialPosicion.php");

class BusController {
    private $modeloBus;
    private $modeloParada;
    private $modeloConfiguracion;
    private $modeloRegistroAcceso;
    private $modeloHistorial;
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->modeloBus = new Bus($conexion);
        $this->modeloParada = new Parada($conexion);
        $this->modeloConfiguracion = new Configuracion($conexion);
        $this->modeloRegistroAcceso = new RegistroAcceso($conexion);
        $this->modeloHistorial = new HistorialPosicion($conexion);
    }

    public function obtenerBusesPublicos() {
        header("Content-Type: application/json");
        $buses = $this->modeloBus->obtenerPublicos();
        echo json_encode($buses);
    }

    public function obtenerEstadoBus($id) {
        header("Content-Type: application/json");

        if (!$this->modeloConfiguracion->estaDentroDeHorario()) {
            echo json_encode(array("en_reposo" => true, "bus" => null));
            return;
        }

        $bus = $this->modeloBus->obtenerPorId($id);
        echo json_encode(array("en_reposo" => false, "bus" => $bus));
    }

    public function obtenerParadas() {
        header("Content-Type: application/json");
        $paradas = $this->modeloParada->obtenerTodas();
        echo json_encode($paradas);
    }

    public function estimarLlegada($busId, $paradaId) {
        header("Content-Type: application/json");

        $bus = $this->modeloBus->obtenerPorId($busId);
        $parada = $this->obtenerParadaPorId($paradaId);

        if (!$bus || !$parada) {
            echo json_encode(array("exito" => false, "mensaje" => "Bus o parada no encontrados"));
            return;
        }

        $distanciaKm = $this->modeloHistorial->haversine($bus['lat'], $bus['lng'], $parada['lat'], $parada['lng']);
        $velocidadKmH = $this->modeloHistorial->calcularVelocidadPromedio($busId);

        if (!$velocidadKmH || $velocidadKmH < 1) {
            $velocidadKmH = 20;
        }

        $minutosEstimados = round(($distanciaKm / $velocidadKmH) * 60);

        echo json_encode(array(
            "exito" => true,
            "distancia_km" => round($distanciaKm, 2),
            "minutos_estimados" => $minutosEstimados
        ));
    }

    public function estimarLlegadaDesdeUsuario($busId, $latUsuario, $lngUsuario) {
        header("Content-Type: application/json");

        $bus = $this->modeloBus->obtenerPorId($busId);

        if (!$bus) {
            echo json_encode(array("exito" => false, "mensaje" => "Bus no encontrado"));
            return;
        }
        if (!$bus['lat'] || !$bus['lng']) {
            echo json_encode(array("exito" => false, "mensaje" => "El bus aun no ha transmitido su ubicacion"));
            return;
        }
        if (!$latUsuario || !$lngUsuario) {
            echo json_encode(array("exito" => false, "mensaje" => "No se pudo obtener tu ubicacion"));
            return;
        }

        $distanciaKm = $this->modeloHistorial->haversine($bus['lat'], $bus['lng'], $latUsuario, $lngUsuario);
        $velocidadKmH = $this->modeloHistorial->calcularVelocidadPromedio($busId);

        if (!$velocidadKmH || $velocidadKmH < 1) {
            $velocidadKmH = 20;
        }

        $minutosEstimados = round(($distanciaKm / $velocidadKmH) * 60);

        echo json_encode(array(
            "exito" => true,
            "distancia_km" => round($distanciaKm, 2),
            "distancia_m" => round($distanciaKm * 1000),
            "minutos_estimados" => $minutosEstimados
        ));
    }

    public function obtenerRecorridoBus($busId) {
        header("Content-Type: application/json");

        $puntos = $this->modeloHistorial->obtenerRecorridoReciente($busId, 300);
        $primerRegistro = $this->modeloHistorial->obtenerPrimerRegistro($busId);

        $diasAprendizaje = 0;
        $rutaAprendida = false;

        if ($primerRegistro) {
            $dias = (strtotime('now') - strtotime($primerRegistro)) / 86400;
            $diasAprendizaje = floor($dias) + 1;
            $rutaAprendida = $dias >= 3;
        }

        echo json_encode(array(
            "puntos" => $puntos,
            "dias_aprendizaje" => min($diasAprendizaje, 3),
            "ruta_aprendida" => $rutaAprendida
        ));
    }

    private function obtenerParadaPorId($id) {
        $sql = "SELECT * FROM paradas WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function registrarUsuarioFinal($nombre, $telefono) {
        header("Content-Type: application/json");

        $telefonoLimpio = preg_replace('/[^0-9]/', '', $telefono);

        if (strlen($telefonoLimpio) !== 8) {
            echo json_encode(array("exito" => false, "mensaje" => "El numero debe tener 8 digitos"));
            return;
        }
        if (trim($nombre) === '') {
            echo json_encode(array("exito" => false, "mensaje" => "El nombre es obligatorio"));
            return;
        }

        $sql = "INSERT INTO usuarios_finales (nombre, telefono) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $nombre, $telefonoLimpio);

        if ($stmt->execute()) {
            $nuevoId = $this->conexion->insert_id;
            $this->modeloRegistroAcceso->registrar('usuario_final_registro', $nuevoId);
            session_start();
            $_SESSION['usuario_final_id'] = $nuevoId;
            echo json_encode(array("exito" => true));
        } else {
            echo json_encode(array("exito" => false, "mensaje" => "Este numero ya esta registrado"));
        }
    }

    public function iniciarSesionUsuarioFinal($telefono) {
        header("Content-Type: application/json");

        $telefonoLimpio = preg_replace('/[^0-9]/', '', $telefono);

        $sql = "SELECT * FROM usuarios_finales WHERE telefono = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $telefonoLimpio);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuarioFinal = $resultado->fetch_assoc();

        if ($usuarioFinal) {
            session_start();
            $_SESSION['usuario_final_id'] = $usuarioFinal['id'];
            // cada reingreso queda registrado, no solo el primer registro
            $this->modeloRegistroAcceso->registrar('usuario_final_acceso', $usuarioFinal['id']);
            echo json_encode(array("exito" => true, "nombre" => $usuarioFinal['nombre']));
        } else {
            echo json_encode(array("exito" => false, "mensaje" => "Numero no registrado"));
        }
    }
    
    public function obtenerRutaDefinida($busId) {
        header("Content-Type: application/json");

        require_once(__DIR__ . "/../models/RutaBus.php");
        $modeloRuta = new RutaBus($this->conexion);
        $ruta = $modeloRuta->obtenerPorBus($busId);

        echo json_encode(array(
            "puntos" => $ruta ? json_decode($ruta['puntos']) : array(),
            "color" => $ruta ? $ruta['color'] : '#F27127'
        ));
    }
    
        public function registrarAccesoSiCorresponde() {
        header("Content-Type: application/json");
        session_start();

        if (!isset($_SESSION['usuario_final_id'])) {
            echo json_encode(array("exito" => false));
            return;
        }

        $usuarioFinalId = $_SESSION['usuario_final_id'];
        $ultimo = $this->modeloRegistroAcceso->obtenerUltimoAccesoUsuario($usuarioFinalId);

        $debeRegistrar = true;
        if ($ultimo) {
            $minutos = (strtotime('now') - strtotime($ultimo)) / 60;
            if ($minutos < 20) $debeRegistrar = false;
        }

        if ($debeRegistrar) {
            $this->modeloRegistroAcceso->registrar('usuario_final_acceso', $usuarioFinalId);
        }

        echo json_encode(array("exito" => true, "registrado" => $debeRegistrar));
    }
}
?>