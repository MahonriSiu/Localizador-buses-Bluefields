<?php
class RegistroAcceso {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrar($tipoUsuario) {
        $sql = "INSERT INTO registro_accesos (tipo_usuario) VALUES (?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $tipoUsuario);
        return $stmt->execute();
    }

    // total de usos del sistema hoy, esto lo pide el panel del auditor
    public function contarAccesosHoy() {
        $sql = "SELECT COUNT(*) AS total FROM registro_accesos WHERE DATE(fecha_hora) = CURDATE()";
        $resultado = $this->conexion->query($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'];
    }

    public function contarRegistrosUsuariosFinales() {
        $sql = "SELECT COUNT(*) AS total FROM usuarios_finales";
        $resultado = $this->conexion->query($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'];
    }
}
?>