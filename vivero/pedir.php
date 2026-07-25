<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

if (isset($_GET['id_producto']) && isset($_GET['cantidad']) && isset($_GET['id_inv'])) {
    $id_prod = (int)$_GET['id_producto'];
    $cantidad = (int)$_GET['cantidad'];
    $id_inv = (int)$_GET['id_inv'];

    // Sumar la cantidad óptima de pedido al stock actual
    $sql = "UPDATE productos SET stock_actual = stock_actual + $cantidad WHERE id_producto = $id_prod";

    if ($conn->query($sql) === TRUE) {
        header("Location: inventario.php?id=$id_inv");
        exit();
    } else {
        echo "Error al actualizar stock: " . $conn->error;
    }
}
