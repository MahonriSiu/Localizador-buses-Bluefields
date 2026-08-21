<?php
class Configuracion {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtener() {
        $sql = "SELECT * FROM configuracion LIMIT 1";
        $resultado = $this->conexion->query($sql);
        return $resultado->fetch_assoc();
    }

    public function actualizarHorario($horaApertura, $horaCierre) {
        $config = $this->obtener();
        $sql = "UPDATE configuracion SET hora_apertura = ?, hora_cierre = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssi", $horaApertura, $horaCierre, $config['id']);
        return $stmt->execute();
    }

    // esto lo usa la vista publica para saber si mostrar el mapa o el mensaje de reposo
    public function estaDentroDeHorario() {
        $config = $this->obtener();
        $horaActual = date("H:i:s");
        return ($horaActual >= $config['hora_apertura'] && $horaActual <= $config['hora_cierre']);
    }
}
?>