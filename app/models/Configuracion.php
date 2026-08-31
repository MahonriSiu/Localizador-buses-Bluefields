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

    public function estaDentroDeHorario() {
        $config = $this->obtener();
        $apertura = $config['hora_apertura'];
        $cierre = $config['hora_cierre'];
        $horaActual = date("H:i:s");

        // horario normal dentro del mismo dia, ej: 06:00 a 21:00
        if ($apertura <= $cierre) {
            return ($horaActual >= $apertura && $horaActual <= $cierre);
        }

        // horario que cruza la medianoche, ej: 22:00 a 05:00
        return ($horaActual >= $apertura || $horaActual <= $cierre);
    }
}
?>