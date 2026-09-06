<?php
class AccesoUsuario {
    public static function exigirRegistro($urlBase) {
        session_start();

        $tieneSesion = isset($_SESSION['usuario_final_id'])
            || isset($_SESSION['admin_autenticado'])
            || isset($_SESSION['auditor_autenticado']);

        if (!$tieneSesion) {
            header("Location: " . $urlBase . "/usuario/registro.php");
            exit;
        }
    }
}
?>