<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

if (!isset($_GET['id_inv'])) {
    header("Location: index.php");
    exit();
}

$id_inv = (int)$_GET['id_inv'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Datos básicos ingresados por el usuario
    $sku = $conn->real_escape_string($_POST['sku']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $precio_costo = (float)$_POST['precio_costo'];
    $precio_venta = (float)$_POST['precio_venta'];
    $stock = (int)$_POST['stock'];

    // 2. CÁLCULO AUTOMÁTICO EN SEGUNDO PLANO
    // Estimamos la demanda anual basada en el stock inicial (ej. rotación de 10 lotes al año)
    $demanda_estimada = $stock * 10;

    // Costos estimados automáticos según el valor de la mercancía
    $costo_pedido = 15.00; // Costo fijo estimado de envío/gestión
    $costo_mantenimiento = max(1.00, $precio_costo * 0.15); // 15% del costo del producto para almacenamiento

    // Fórmula EOQ (Cantidad óptima a pedir)
    $optimo = ceil(sqrt((2 * $demanda_estimada * $costo_pedido) / $costo_mantenimiento));

    // Punto de Reorden Automático (Alerta cuando el stock baje al 20% del lote inicial o mínimo 5 unidades)
    $reorden = max(5, ceil($stock * 0.20));

    // 3. Guardar en la base de datos
    $sql = "INSERT INTO productos (id_inventario, sku, nombre, precio_costo, precio_venta, stock_actual, punto_reorden, fecha_ultima_recepcion, cantidad_optima_pedido) 
            VALUES ($id_inv, '$sku', '$nombre', $precio_costo, $precio_venta, $stock, $reorden, CURDATE(), $optimo)";

    if ($conn->query($sql) === TRUE) {
        header("Location: inventario.php?id=$id_inv");
        exit();
    } else {
        $error = "Error al guardar el producto: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center min-vh-100 py-4">

    <div class="card shadow p-4 w-100" style="max-width: 600px;">
        <h3 class="mb-4 text-center">➕ Agregar Nuevo Producto</h3>

        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">SKU (Código)</label>
                    <input type="text" name="sku" class="form-control" placeholder="Ej. PL-001" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nombre del Producto</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej. Rosal Rojo" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Stock Inicial</label>
                    <input type="number" name="stock" class="form-control" placeholder="Ej. 50" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Precio Costo ($)</label>
                    <input type="number" step="0.01" name="precio_costo" class="form-control" placeholder="0.00" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Precio Venta ($)</label>
                    <input type="number" step="0.01" name="precio_venta" class="form-control" placeholder="0.00" required>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <a href="inventario.php?id=<?php echo $id_inv; ?>" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-success">💾 Guardar Producto</button>
            </div>
        </form>
    </div>

</body>

</html>