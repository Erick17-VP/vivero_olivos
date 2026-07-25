<?php
session_start();
// Candado de seguridad: Si no está logueado o NO es gerente, lo sacamos
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'gerente') {
    header("Location: index.php");
    exit();
}

include 'conexion.php';

// Verificamos que hayamos recibido los IDs necesarios
if (isset($_GET['id_producto']) && isset($_GET['id_inv'])) {
    $id_producto = $_GET['id_producto'];
    $id_inv = $_GET['id_inv'];

    // Instrucción SQL para borrar
    $sql = "DELETE FROM productos WHERE id_producto = $id_producto";

    if ($conn->query($sql) === TRUE) {
        // Si se borró con éxito, regresamos a la pantalla del inventario
        header("Location: inventario.php?id=" . $id_inv);
        exit();
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
} else {
    echo "Faltan datos para eliminar.";
}
