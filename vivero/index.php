<?php
session_start();
// Candado: Si no han iniciado sesión, los regresamos al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Menú - Vivero Los Olivos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Barra superior con el usuario y botón de salir -->
    <div class="bg-dark text-white p-3 d-flex justify-content-between align-items-center">
        <span>👤 Bienvenido, <b><?php echo $_SESSION['usuario']; ?></b> (Rol: <?php echo $_SESSION['rol']; ?>)</span>
        <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
    </div>

    <div class="container mt-5 text-center">
        <h1 class="mb-5">🌿 Sistema de Inventarios - Vivero Los Olivos</h1>

        <div class="row justify-content-center">
            <!-- Tarjeta Inventario 1 -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg p-3">
                    <div class="card-body">
                        <h2 class="card-title">🌱 Plantas</h2>
                        <p class="text-muted">Surtido cada 20 días</p>
                        <a href="inventario.php?id=1" class="btn btn-success btn-lg w-100">Abrir Inventario</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Inventario 2 -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg p-3">
                    <div class="card-body">
                        <h2 class="card-title">📦 Plásticos</h2>
                        <p class="text-muted">Surtido cada 30 días</p>
                        <a href="inventario.php?id=2" class="btn btn-primary btn-lg w-100">Abrir Inventario</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Inventario 3 -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg p-3">
                    <div class="card-body">
                        <h2 class="card-title">🛠️ Herramientas</h2>
                        <p class="text-muted">Surtido cada 30 días</p>
                        <a href="inventario.php?id=3" class="btn btn-secondary btn-lg w-100">Abrir Inventario</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>