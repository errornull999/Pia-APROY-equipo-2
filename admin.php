<?php
require_once 'database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Estadísticas
$stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'cliente'");
$totalClientes = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM citas");
$totalCitas = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM citas WHERE estado = 'pendiente'");
$citasPendientes = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT SUM(total) as total FROM citas WHERE estado = 'completada'");
$ingresos = $stmt->fetch()['total'] ?? 0;

include 'header.php';
?>

<h2>Panel de Administración</h2>
<p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?php echo $totalClientes; ?></div>
        <p>Clientes Registrados</p>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $totalCitas; ?></div>
        <p>Citas Totales</p>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $citasPendientes; ?></div>
        <p>Citas Pendientes</p>
    </div>
    <div class="stat-card">
        <div class="stat-number">$<?php echo number_format($ingresos, 0, ',', '.'); ?></div>
        <p>Ingresos Totales</p>
    </div>
</div>

<div class="card-grid">
    <div class="card">
        <h3>📋 Gestionar Citas</h3>
        <p>Ver, editar y administrar todas las citas del sistema</p>
        <a href="gestionar_citas.php" class="btn btn-primary">Gestionar</a>
    </div>
    
    <div class="card">
        <h3>🎯 Gestionar Servicios</h3>
        <p>Agregar, editar o eliminar servicios fotográficos</p>
        <a href="gestionar_servicios.php" class="btn btn-primary">Gestionar</a>
    </div>
    
    <div class="card">
        <h3>📸 Gestionar Fotógrafos</h3>
        <p>Administrar el equipo de fotógrafos</p>
        <a href="gestionar_fotografos.php" class="btn btn-primary">Gestionar</a>
    </div>
    
    <div class="card">
        <h3>👥 Gestionar Usuarios</h3>
        <p>Ver y administrar clientes registrados</p>
        <a href="gestionar_usuarios.php" class="btn btn-primary">Gestionar</a>
    </div>
</div>
<section class="calendario-seccion">
    <h2>Agenda Mensual</h2>
    <?php include 'calendario.php'; ?>
</section>

<?php include 'footer.php'; ?>