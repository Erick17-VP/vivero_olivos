<?php
session_start();
// Si el usuario ya tiene una sesión activa, lo mandamos directo al menú
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Los Olivos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-secondary d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow-lg p-4" style="width: 25rem;">
        <div class="text-center mb-4">
            <h2>🌿 Los Olivos</h2>
            <p class="text-muted">Acceso al Sistema de Inventario</p>
        </div>

        <!-- El atributo autocomplete="off" evita que el navegador guarde los datos -->
        <form action="validar.php" method="POST" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <!-- autocomplete="new-password" es un truco extra para engañar a Chrome y que no sugiera correos -->
                <input type="text" name="usuario" class="form-control" required autocomplete="new-password">
            </div>
            <div class="mb-4">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-success w-100">Ingresar al Sistema</button>
        </form>
    </div>

</body>

</html>