<?php
class UsuarioFinal {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerTodos() {
        $sql = "SELECT id, nombre, telefono, fecha_registro FROM usuarios_finales ORDER BY fecha_registro DESC";
        $resultado = $this->conexion->query($sql);
        $usuarios = array();
        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }
        return $usuarios;
    }
}
?>