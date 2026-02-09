<?php

$servername = getenv('MYSQL_HOST') ?: 'db';
$username = 'root';
$password = '1234';
$database = getenv('MYSQL_DATABASE') ?: 'crud_empleados';

// Crear conexión
$conexion = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(['error' => 'Conexión fallida: ' . $conexion->connect_error]));
}

// Establecer charset UTF-8
$conexion->set_charset("utf8mb4");

?>
