<?php

require_once(__DIR__ . "/../models/Usuario.php");

class AdminController {

    private $modeloUsuario;

    public function __construct($conexion) {
        $this->modeloUsuario = new Usuario($conexion);
    }

    public function iniciarSesion($correo, $contrasena) {
        session_start();

        $usuario = $this->modeloUsuario->verificarCredenciales($correo, $contrasena);

        header("Content-Type: application/json");

        if ($usuario) {
            $_SESSION['admin_autenticado'] = true;
            $_SESSION['admin_nombre'] = $usuario['nombre'];
            $_SESSION['admin_rol'] = $usuario['rol'];
            echo json_encode(array("exito" => true));
        } else {
            echo json_encode(array("exito" => false, "mensaje" => "Correo o contrasena incorrectos"));
        }
    }

}

?>