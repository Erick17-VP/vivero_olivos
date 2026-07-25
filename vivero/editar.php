<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'gerente') {
    header("Location: index.php");
    exit();
}

include 'conexion.php';

$id_producto = (int)$_GET['id_producto'];
$id_inv = isset($_GET['id_inv']) ? (int)$_GET['id_inv'] : 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sku = $conn->real_escape_string($_POST['sku']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $precio_costo = (float)$_POST['precio_costo'];
    $precio_venta = (float)$_POST['precio_venta'];
    $stock = (int)$_POST['stock'];
    $reorden = (int)$_POST['punto_reorden'];
    $optimo = (int)$_POST['cantidad_optima_pedido'];

    $sql = "UPDATE productos SET 
            sku = '$sku', 
            nombre = '$nombre', 
            precio_costo = $precio_costo, 
            precio_venta = $precio_venta, 
            stock_actual = $stock,
            punto_reorden = $reorden,
            cantidad_optima_pedido = $optimo
            WHERE id_producto = $id_producto";

    if ($conn->query($sql) === TRUE) {
        header("Location: inventario.php?id=$id_inv");
        exit();
    } else {
        $error = "Error al actualizar: " . $conn->error;
    }
}

$sql_prod = "SELECT * FROM productos WHERE id_producto = $id_producto";
$prod = $conn->query($sql_prod)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">

    <div class="card shadow p-4 w-100" style="max-width: 600px;">
        <h3 class="mb-4 text-center">✏️ Editar Producto</h3>

        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-bold">SKU</label>
                <input type="text" name="sku" class="form-control" value="<?php echo $prod['sku']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Nombre del Producto</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $prod['nombre']; ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Stock Actual</label>
                    <input type="number" name="stock" class="form-control" value="<?php echo $prod['stock_actual']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Stock Mínimo de Alerta</label>
                    <input type="number" name="punto_reorden" class="form-control" value="<?php echo $prod['punto_reorden']; ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Cantidad Normal a Pedir al Proveedor</label>
                <input type="number" name="cantidad_optima_pedido" class="form-control" value="<?php echo $prod['cantidad_optima_pedido']; ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Precio Costo ($)</label>
                    <input type="number" step="0.01" name="precio_costo" class="form-control" value="<?php echo $prod['precio_costo']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Precio Venta ($)</label>
                    <input type="number" step="0.01" name="precio_venta" class="form-control" value="<?php echo $prod['precio_venta']; ?>" required>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <a href="inventario.php?id=<?php echo $id_inv; ?>" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>

</body>

</html>