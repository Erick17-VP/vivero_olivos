<?php
session_start(); // Retomamos la sesión actual
session_unset(); // Vaciamos las variables (rol, usuario)
session_destroy(); // Destruimos la sesión por completo

// Redirigimos de vuelta a la pantalla de login
header("Location: login.php");
exit();
