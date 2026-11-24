<?php
$conexion = new mysqli("localhost", "root", "", "bd_empa_2");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
