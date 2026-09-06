<?php
class Csrf {
    public static function generarToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validar($tokenRecibido) {
        if (!isset($_SESSION['csrf_token']) || !$tokenRecibido) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $tokenRecibido);
    }

    public static function rechazarSiInvalido($tokenRecibido) {
        if (!self::validar($tokenRecibido)) {
            http_response_code(403);
            header("Content-Type: application/json");
            echo json_encode(array("exito" => false, "mensaje" => "Token de seguridad invalido. Recarga la pagina e intenta de nuevo."));
            exit;
        }
    }
}
?>