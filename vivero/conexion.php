<?php
// conexion.php
$servidor = "localhost";
$usuario = "root"; // Usuario por defecto en XAMPP
$password = "VEPE-1702";    // XAMPP normalmente no tiene contraseña
$base_datos = "vivero_olivos";

// Crear la conexión
$conn = new mysqli($servidor, $usuario, $password, $base_datos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
