<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

// Validar que se reciba el id de inventario por URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_inventario = (int)$_GET['id'];

// Consultar nombre del inventario
$sql_inv = "SELECT * FROM inventarios WHERE id_inventario = $id_inventario";
$res_inv = $conn->query($sql_inv);

if ($res_inv->num_rows > 0) {
    $inv = $res_inv->fetch_assoc();
} else {
    header("Location: index.php");
    exit();
}

// Lógica del buscador
$busqueda = "";
$condicion_busqueda = "";
if (isset($_GET['buscar']) && $_GET['buscar'] != '') {
    $busqueda = $conn->real_escape_string($_GET['buscar']);
    $condicion_busqueda = " AND (nombre LIKE '%$busqueda%' OR sku LIKE '%$busqueda%')";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inventario: <?php echo $inv['nombre']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <a href="index.php" class="btn btn-outline-dark mb-3">⬅ Volver al Menú</a>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestión de <?php echo $inv['nombre']; ?></h2>
            <div>
                <a href="agregar.php?id_inv=<?php echo $id_inventario; ?>" class="btn btn-success">➕ Agregar Mercancía</a>
            </div>
        </div>

        <!-- Buscador -->
        <form method="GET" action="inventario.php" class="mb-3 d-flex">
            <input type="hidden" name="id" value="<?php echo $id_inventario; ?>">
            <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por Nombre o SKU..." value="<?php echo $busqueda; ?>">
            <button type="submit" class="btn btn-primary">🔍 Buscar</button>
            <a href="inventario.php?id=<?php echo $id_inventario; ?>" class="btn btn-secondary ms-2">Limpiar</a>
        </form>

        <div class="card shadow-sm p-4">
            <table class="table table-hover mt-3 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>SKU</th>
                        <th>Producto</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Estado / Acción Requerida</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_prod = "SELECT * FROM productos WHERE id_inventario = $id_inventario $condicion_busqueda";
                    $res_prod = $conn->query($sql_prod);

                    if ($res_prod && $res_prod->num_rows > 0) {
                        while ($prod = $res_prod->fetch_assoc()) {
                            $stock = $prod['stock_actual'];
                            $reorden = $prod['punto_reorden'];
                            $optimo = $prod['cantidad_optima_pedido'];

                            $alerta = ($stock <= $reorden) ? "table-danger" : "";

                            // Botón directo para pedir si está en alerta roja
                            if ($stock <= $reorden) {
                                $estado = "<a href='pedir.php?id_producto={$prod['id_producto']}&cantidad={$optimo}&id_inv={$id_inventario}' 
                                              class='btn btn-danger btn-sm font-weight-bold'
                                              onclick=\"return confirm('¿Confirmar recepción de $optimo unidades de {$prod['nombre']}?');\">
                                              🛒 Surtir $optimo unid.
                                           </a>";
                            } else {
                                $estado = "<span class='badge bg-success'>✅ Stock Suficiente</span>";
                            }

                            $sku_mostrar = !empty($prod['sku']) ? $prod['sku'] : '-';

                            echo "<tr class='$alerta'>";
                            echo "<td><strong>$sku_mostrar</strong></td>";
                            echo "<td>{$prod['nombre']}</td>";
                            echo "<td><strong>{$stock}</strong></td>";
                            echo "<td>{$reorden}</td>";
                            echo "<td>$estado</td>";
                            echo "<td>";

                            if ($_SESSION['rol'] == 'gerente') {
                                echo "<a href='editar.php?id_producto={$prod['id_producto']}&id_inv={$id_inventario}' class='btn btn-sm btn-primary me-1'>Editar</a>";
                                echo "<a href='eliminar.php?id_producto={$prod['id_producto']}&id_inv={$id_inventario}' 
                                      class='btn btn-sm btn-danger' 
                                      onclick=\"return confirm('⚠️ ¿Estás seguro de eliminar: {$prod['nombre']}?');\">Eliminar</a>";
                            } else {
                                echo "<span class='text-muted badge bg-light text-dark'>Solo lectura</span>";
                            }

                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No se encontraron productos.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>