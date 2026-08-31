<?php
class AnuncioLocal {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerAleatorio() {
        $sql = "SELECT * FROM anuncios_locales WHERE activo = 1 ORDER BY RAND() LIMIT 1";
        $resultado = $this->conexion->query($sql);
        return $resultado->fetch_assoc();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM anuncios_locales ORDER BY fecha_creacion DESC";
        $resultado = $this->conexion->query($sql);
        $lista = array();
        while ($fila = $resultado->fetch_assoc()) {
            $lista[] = $fila;
        }
        return $lista;
    }

    public function crear($nombreNegocio, $texto, $telefono, $tipoMedia, $urlMedia, $urlAudio) {
        $sql = "INSERT INTO anuncios_locales (nombre_negocio, texto, telefono, tipo_media, url_media, url_audio) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssss", $nombreNegocio, $texto, $telefono, $tipoMedia, $urlMedia, $urlAudio);
        return $stmt->execute();
    }

    public function toggleActivo($id, $activo) {
        $sql = "UPDATE anuncios_locales SET activo = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $activo, $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $sql = "DELETE FROM anuncios_locales WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>