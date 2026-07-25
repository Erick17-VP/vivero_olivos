<?php
session_start();
include 'conexion.php';

// Recibimos los datos del formulario
$user = $_POST['usuario'];
$pass = $_POST['password'];

// Buscamos al usuario en la base de datos
$sql = "SELECT * FROM usuarios WHERE usuario = '$user'";
$resultado = $conn->query($sql);

if ($row = $resultado->fetch_assoc()) {
    // Verificamos si la contraseña escrita coincide con el Hash guardado
    if (password_verify($pass, $row['password_hash'])) {
        // ¡Éxito! Guardamos los datos en la sesión
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['rol'] = $row['rol']; // 'gerente' o 'empleado'

        // Lo enviamos al menú principal
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Contraseña incorrecta'); window.location='login.php';</script>";
    }
} else {
    echo "<script>alert('El usuario no existe'); window.location='login.php';</script>";
}
