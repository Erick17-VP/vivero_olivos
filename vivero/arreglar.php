<?php
include 'conexion.php';

// Encriptamos la contraseña "admin123" de forma real
$password_correcta = password_hash("admin123", PASSWORD_DEFAULT);

// Actualizamos a todos los usuarios en la base de datos
$sql = "UPDATE usuarios SET password_hash = '$password_correcta'";

if ($conn->query($sql) === TRUE) {
    echo "<h1>¡Éxito! Las contraseñas se han arreglado.</h1>";
    echo "<p>Ya puedes volver al login e ingresar con <b>admin123</b>.</p>";
    echo "<a href='login.php'>Ir al Login</a>";
} else {
    echo "Error: " . $conn->error;
}
