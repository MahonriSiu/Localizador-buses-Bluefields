<?php
class PuntoCreativo {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerPorEventoPublico($eventoId) {
        $sql = "SELECT * FROM puntos_ruta_creativa WHERE evento_id = ? ORDER BY orden ASC, id ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $eventoId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $puntos = array();
        while ($fila = $resultado->fetch_assoc()) {
            if ($fila['visible'] == 1) {
                $fila['imagenes'] = $this->obtenerImagenes($fila['id']);
            } else {
                $fila['imagenes'] = array();
            }
            $puntos[] = $fila;
        }
        return $puntos;
    }

    public function obtenerPorEventoAdmin($eventoId) {
        $sql = "SELECT p.*,
                (SELECT COUNT(*) FROM imagenes_ruta_creativa i WHERE i.punto_id = p.id) AS total_imagenes
                FROM puntos_ruta_creativa p WHERE p.evento_id = ? ORDER BY p.orden ASC, p.id ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $eventoId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $puntos = array();
        while ($fila = $resultado->fetch_assoc()) {
            $puntos[] = $fila;
        }
        return $puntos;
    }

    public function crear($eventoId, $nombre, $descripcion, $lat, $lng, $orden, $visible) {
        $sql = "INSERT INTO puntos_ruta_creativa (evento_id, nombre, descripcion, lat, lng, orden, visible) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("issddii", $eventoId, $nombre, $descripcion, $lat, $lng, $orden, $visible);
        $stmt->execute();
        return $this->conexion->insert_id;
    }

    public function actualizar($id, $nombre, $descripcion, $lat, $lng, $orden, $visible) {
        $sql = "UPDATE puntos_ruta_creativa SET nombre = ?, descripcion = ?, lat = ?, lng = ?, orden = ?, visible = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssddiii", $nombre, $descripcion, $lat, $lng, $orden, $visible, $id);
        return $stmt->execute();
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM puntos_ruta_creativa WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function eliminar($id) {
        $sql = "DELETE FROM puntos_ruta_creativa WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function obtenerImagenes($puntoId) {
        $sql = "SELECT id, nombre_archivo FROM imagenes_ruta_creativa WHERE punto_id = ? ORDER BY id ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $puntoId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $imagenes = array();
        while ($fila = $resultado->fetch_assoc()) {
            $imagenes[] = $fila;
        }
        return $imagenes;
    }

    public function agregarImagen($puntoId, $nombreArchivo) {
        $sql = "INSERT INTO imagenes_ruta_creativa (punto_id, nombre_archivo) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("is", $puntoId, $nombreArchivo);
        return $stmt->execute();
    }

    public function eliminarImagen($imagenId) {
        $sql = "DELETE FROM imagenes_ruta_creativa WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $imagenId);
        return $stmt->execute();
    }

    public function actualizarPortada($puntoId, $nombreArchivo) {
        $sql = "UPDATE puntos_ruta_creativa SET imagen_portada = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nombreArchivo, $puntoId);
        return $stmt->execute();
    }
}
?>