<?php
require_once 'database.php';

// Si no está logueado O no es un cliente, lo mandamos al login o al panel de admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'cliente') {
    if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin') {
        header('Location: admin.php'); // Si es admin intentando entrar a cliente, lo regresamos a su panel
    } else {
        header('Location: login.php');
    }
    exit();
}

// Obtener próximas citas
$stmt = $pdo->prepare("
    SELECT c.*, s.nombre as servicio_nombre, f.nombre as fotografo_nombre 
    FROM citas c
    JOIN servicios s ON c.servicio_id = s.id
    JOIN fotografos f ON c.fotografos_id = f.id
    WHERE c.usuario_id = ? AND c.fecha >= CURDATE()
    ORDER BY c.fecha ASC, c.hora ASC
    LIMIT 5
");
$stmt->execute([$_SESSION['usuario_id']]);
$proximas_citas = $stmt->fetchAll();

include 'header.php'; ?>

<h2>Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?> 👋</h2>
<p>Aquí puedes gestionar tus próximas sesiones.</p>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?php echo count($proximas_citas); ?></div>
        <p>Próximas Sesiones</p>
    </div>
    <div class="stat-card">
        <a href="agendar.php" class="btn btn-primary">+ Agendar Nueva</a>
    </div>
</div>

<?php if(count($proximas_citas) > 0): ?>
    <div class="card">
        <h3>Mis Próximas Citas</h3>
        <?php foreach($proximas_citas as $cita): ?>
            <div class="card" style="margin: 10px 0;">
                <p><strong><?php echo htmlspecialchars($cita['servicio_nombre']); ?></strong></p>
                <p>📅 <?php echo date('d/m/Y', strtotime($cita['fecha'])); ?> a las <?php echo date('H:i', strtotime($cita['hora'])); ?> hrs</p>
                <p>📸 Con <?php echo htmlspecialchars($cita['fotografo_nombre']); ?></p>
                <p>💰 Total: $<?php echo number_format($cita['total'], 0, ',', '.'); ?></p>
                <p>📌 Estado: <strong><?php echo ucfirst($cita['estado']); ?></strong></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="card">
        <p>No tienes sesiones agendadas.</p>
        <a href="agendar.php" class="btn btn-primary">Agendar mi primera sesión</a>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>