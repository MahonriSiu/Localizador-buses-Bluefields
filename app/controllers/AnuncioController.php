<?php
require_once(__DIR__ . "/../models/AnuncioLocal.php");

class AnuncioController {
    private $modeloAnuncio;

    public function __construct($conexion) {
        $this->modeloAnuncio = new AnuncioLocal($conexion);
    }

    public function obtenerParaMostrar() {
        header("Content-Type: application/json");
        $anuncio = $this->modeloAnuncio->obtenerAleatorio();

        if ($anuncio) {
            echo json_encode(array("hay_anuncio" => true, "anuncio" => $anuncio));
        } else {
            echo json_encode(array("hay_anuncio" => false));
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

    // sin restricciones de contenido: el admin decide que poner. Un espacio vacio
    // significa que ahi no va nada, no es un error.
    public function crear($nombreNegocio, $texto, $telefono) {
        $this->verificarSesionAdmin();
        header("Content-Type: application/json");

        $tipoMedia = 'ninguno';
        $urlMedia = null;
        $urlAudio = null;

        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $tiposPermitidos = array(
                'image/jpeg' => array('jpg', 'imagen'),
                'image/png' => array('png', 'imagen'),
                'video/mp4' => array('mp4', 'video')
            );

            $mime = mime_content_type($_FILES['media']['tmp_name']);

            if (!array_key_exists($mime, $tiposPermitidos)) {
                echo json_encode(array("exito" => false, "mensaje" => "Solo se permiten imagenes JPG/PNG o video MP4"));
                return;
            }

            $limiteBytes = ($tiposPermitidos[$mime][1] === 'video') ? 40 * 1024 * 1024 : 8 * 1024 * 1024;
            if ($_FILES['media']['size'] > $limiteBytes) {
                echo json_encode(array("exito" => false, "mensaje" => "El archivo es muy pesado (maximo " . ($limiteBytes / 1024 / 1024) . "MB)"));
                return;
            }

            $extension = $tiposPermitidos[$mime][0];
            $tipoMedia = $tiposPermitidos[$mime][1];
            $nombreArchivo = "anuncio_" . time() . "_" . rand(1000, 9999) . "." . $extension;
            $carpetaDestino = __DIR__ . "/../../assets/img/anuncios";

            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0755, true);
            }

            if (move_uploaded_file($_FILES['media']['tmp_name'], $carpetaDestino . "/" . $nombreArchivo)) {
                $urlMedia = $nombreArchivo;
            }
        }

        // audio opcional e independiente: sirve para poner sonido a un anuncio de
        // imagen (una foto con un jingle de fondo), o musica/voz extra en cualquier caso
        if (isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
            $tiposAudioPermitidos = array('audio/mpeg' => 'mp3', 'audio/mp4' => 'm4a', 'audio/wav' => 'wav', 'audio/x-wav' => 'wav');
            $mimeAudio = mime_content_type($_FILES['audio']['tmp_name']);

            if (array_key_exists($mimeAudio, $tiposAudioPermitidos)) {
                if ($_FILES['audio']['size'] <= 8 * 1024 * 1024) {
                    $extensionAudio = $tiposAudioPermitidos[$mimeAudio];
                    $nombreAudio = "audio_" . time() . "_" . rand(1000, 9999) . "." . $extensionAudio;
                    $carpetaAudio = __DIR__ . "/../../assets/audio/anuncios";

                    if (!is_dir($carpetaAudio)) {
                        mkdir($carpetaAudio, 0755, true);
                    }

                    if (move_uploaded_file($_FILES['audio']['tmp_name'], $carpetaAudio . "/" . $nombreAudio)) {
                        $urlAudio = $nombreAudio;
                    }
                }
            }
        }

        if ($tipoMedia === 'ninguno' && !$urlAudio && trim($nombreNegocio) === '' && trim($texto) === '') {
            echo json_encode(array("exito" => false, "mensaje" => "El anuncio no puede quedar completamente vacio: agrega al menos texto, imagen, video o audio"));
            return;
        }

        $this->modeloAnuncio->crear($nombreNegocio, $texto, $telefono, $tipoMedia, $urlMedia, $urlAudio);
        echo json_encode(array("exito" => true));
    }

    public function obtenerTodos() {
        $this->verificarSesionAdmin();
        header("Content-Type: application/json");
        echo json_encode(array("anuncios" => $this->modeloAnuncio->obtenerTodos()));
    }

    public function toggle($id, $activo) {
        $this->verificarSesionAdmin();
        header("Content-Type: application/json");
        $this->modeloAnuncio->toggleActivo($id, $activo);
        echo json_encode(array("exito" => true));
    }

    public function eliminar($id) {
        $this->verificarSesionAdmin();
        header("Content-Type: application/json");
        $this->modeloAnuncio->eliminar($id);
        echo json_encode(array("exito" => true));
    }
}
?>