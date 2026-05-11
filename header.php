<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solace - Sesiones Fotográficas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo"><img class="img2"  src="img/logo.jpeg" alt=""></a>
        <ul class="nav-links">
            <li><a href="index.php">Inicio</a></li>
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <?php if($_SESSION['rol'] == 'admin'): ?>
                    <li><a href="admin.php">Admin Panel</a></li>
                    <li><a href="calendario.php">Calendario</a></li>
                <?php endif; ?>
                
                <li><a href="dashboard.php">Mi Panel</a></li>
                <li><a href="mis_citas.php">Mis Citas</a></li>
                <li><a href="agendar.php">Agendar</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            <?php else: ?>
                
                <li><a href="login.php">Iniciar Sesión</a></li>
                <li><a href="registro.php">Registrarse</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="container">