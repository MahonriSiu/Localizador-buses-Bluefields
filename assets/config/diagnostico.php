<?php
$host = "sql211.infinityfree.com";
$usuario = "if0_42523829";
$contrasena = "JustoSiu18";
$basedatos = "if0_42523829_localizador";

$conexion = @new mysqli($host, $usuario, $contrasena, $basedatos);

if ($conexion->connect_error) {
    die("ERROR DE CONEXION: " . $conexion->connect_error);
}

echo "Conexion exitosa a la base de datos.<br><br>";

$resultado = $conexion->query("SHOW TABLES");
if (!$resultado) {
    die("Error al listar tablas: " . $conexion->error);
}

echo "Tablas existentes:<br>";
$total = 0;
while ($fila = $resultado->fetch_row()) {
    echo "- " . $fila[0] . "<br>";
    $total++;
}
echo "<br>Total de tablas: " . $total;

echo "<br><br>Usuarios existentes:<br>";
$res2 = $conexion->query("SELECT id, nombre, correo, rol FROM usuarios");
while ($fila = $res2->fetch_assoc()) {
    echo $fila['id'] . " - " . $fila['nombre'] . " - " . $fila['correo'] . " - " . $fila['rol'] . "<br>";
}