<?php
require_once(__DIR__ . "/../models/PuntoCreativo.php");

class RutaCreativaController {
    private $modeloPunto;

    public function __construct($conexion) {
        $this->modeloPunto = new PuntoCreativo($conexion);
    }

    public function obtenerPorEventoPublico($eventoId) {
        header("Content-Type: application/json");
        try {
            echo json_encode(array("puntos" => $this->modeloPunto->obtenerPorEventoPublico($eventoId)));
        } catch (Throwable $error) {
            echo json_encode(array("puntos" => array(), "error" => $error->getMessage()));
        }
    }

    private function verificarSesionAdmin() {
        session_start();
        if (!isset($_SESSION['admin_autenticado'])) {
            header("Content-Type: application/json");
            echo json_encode(array("exito" => false, "mensaje" => "Sesion no valida"));
            exit;
        }
    }

    public function crearPunto($eventoId, $nombre, $descripcion, $lat, $lng, $orden, $visible) {
        $this->verificarSesionAdmin();
        require_once(__DIR__ . "/../utilidades/Csrf.php");
        Csrf::rechazarSiInvalido(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '');        
        header("Content-Type: application/json");

        if ($eventoId <= 0) {
            echo json_encode(array("exito" => false, "mensaje" => "Selecciona a que evento pertenece este punto"));
            return;
        }
        if ($lat == 0 || $lng == 0) {
            echo json_encode(array("exito" => false, "mensaje" => "Latitud y longitud son obligatorias"));
            return;
        }
        if ($lng > 0) {
            echo json_encode(array("exito" => false, "mensaje" => "La longitud debe ser negativa en Nicaragua"));
            return;
        }
        if ($visible && trim($nombre) === '') {
            echo json_encode(array("exito" => false, "mensaje" => "Un punto visible necesita nombre"));
            return;
        }

        $nombreFinal = trim($nombre) !== '' ? $nombre : 'Punto del recorrido';
        $descripcionFinal = trim($descripcion) !== '' ? $descripcion : '';

        try {
            $id = $this->modeloPunto->crear($eventoId, $nombreFinal, $descripcionFinal, $lat, $lng, $orden, $visible);
            echo json_encode(array("exito" => true, "id" => $id));
        } catch (Throwable $error) {
            echo json_encode(array("exito" => false, "mensaje" => "Error al guardar: " . $error->getMessage()));
        }
    }

    public function actualizarPunto($id, $nombre, $descripcion, $lat, $lng, $orden, $visible) {
        $this->verificarSesionAdmin();
        require_once(__DIR__ . "/../utilidades/Csrf.php");
        Csrf::rechazarSiInvalido(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '');        
        header("Content-Type: application/json");

        if ($lng > 0) {
            echo json_encode(array("exito" => false, "mensaje" => "La longitud debe ser negativa en Nicaragua"));
            return;
        }
        if ($visible && trim($nombre) === '') {
            echo json_encode(array("exito" => false, "mensaje" => "Un punto visible necesita nombre"));
            return;
        }

        $nombreFinal = trim($nombre) !== '' ? $nombre : 'Punto del recorrido';

        $this->modeloPunto->actualizar($id, $nombreFinal, $descripcion, $lat, $lng, $orden, $visible);
        echo json_encode(array("exito" => true));
    }

    public function obtenerPuntoAdmin($id) {
        $this->verificarSesionAdmin();
        header("Content-Type: application/json");
        echo json_encode(array("punto" => $this->modeloPunto->obtenerPorId($id)));
    }

    public function obtenerPorEventoAdmin($eventoId) {
        $this->verificarSesionAdmin();
        header("Content-Type: application/json");
        echo json_encode(array("puntos" => $this->modeloPunto->obtenerPorEventoAdmin($eventoId)));
    }

    public function obtenerImagenesAdmin($puntoId) {
        $this->verificarSesionAdmin();
        header("Content-Type: application/json");
        echo json_encode(array("imagenes" => $this->modeloPunto->obtenerImagenes($puntoId)));
    }

    public function subirImagen($puntoId) {
        $this->verificarSesionAdmin();
        require_once(__DIR__ . "/../utilidades/Csrf.php");
        Csrf::rechazarSiInvalido(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '');        
        header("Content-Type: application/json");
        $this->procesarSubidaImagen($puntoId, 'imagen', false);
    }

    public function subirPortada($puntoId) {
        $this->verificarSesionAdmin();
        require_once(__DIR__ . "/../utilidades/Csrf.php");
        Csrf::rechazarSiInvalido(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '');        
        header("Content-Type: application/json");
        $this->procesarSubidaImagen($puntoId, 'portada', true);
    }

    private function procesarSubidaImagen($puntoId, $campoArchivo, $esPortada) {
        if (!isset($_FILES[$campoArchivo]) || $_FILES[$campoArchivo]['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(array("exito" => false, "mensaje" => "No se recibio ninguna imagen"));
            return;
        }

        $tiposPermitidos = array('image/jpeg' => 'jpg', 'image/png' => 'png');
        $mime = mime_content_type($_FILES[$campoArchivo]['tmp_name']);

        if (!array_key_exists($mime, $tiposPermitidos)) {
            echo json_encode(array("exito" => false, "mensaje" => "Solo se permiten imagenes JPG o PNG"));
            return;
        }
        if ($_FILES[$campoArchivo]['size'] > 8 * 1024 * 1024) {
            echo json_encode(array("exito" => false, "mensaje" => "La imagen no puede pesar mas de 8MB"));
            return;
        }

        $extension = $tiposPermitidos[$mime];
        $nombreArchivo = ($esPortada ? "portada_" : "ruta_") . $puntoId . "_" . time() . "_" . rand(1000, 9999) . "." . $extension;
        $carpetaDestino = __DIR__ . "/../../assets/img/ruta-creativa";

        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0755, true);
        }

        if (move_uploaded_file($_FILES[$campoArchivo]['tmp_name'], $carpetaDestino . "/" . $nombreArchivo)) {
            if ($esPortada) {
                $this->modeloPunto->actualizarPortada($puntoId, $nombreArchivo);
            } else {
                $this->modeloPunto->agregarImagen($puntoId, $nombreArchivo);
            }
            echo json_encode(array("exito" => true, "nombre_archivo" => $nombreArchivo));
        } else {
            echo json_encode(array("exito" => false, "mensaje" => "No se pudo guardar la imagen"));
        }
    }

    public function eliminarPunto($id) {
        $this->verificarSesionAdmin();
        require_once(__DIR__ . "/../utilidades/Csrf.php");
        Csrf::rechazarSiInvalido(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '');        
        header("Content-Type: application/json");
        $this->modeloPunto->eliminar($id);
        echo json_encode(array("exito" => true));
    }

    public function eliminarImagen($imagenId) {
        $this->verificarSesionAdmin();
        require_once(__DIR__ . "/../utilidades/Csrf.php");
        Csrf::rechazarSiInvalido(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '');        
        header("Content-Type: application/json");
        $this->modeloPunto->eliminarImagen($imagenId);
        echo json_encode(array("exito" => true));
    }
}
?>