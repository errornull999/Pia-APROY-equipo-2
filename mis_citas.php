<?php
require_once 'database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener todas las citas del usuario
$stmt = $pdo->prepare("
    SELECT c.*, s.nombre as servicio_nombre, f.nombre as fotografo_nombre 
    FROM citas c
    JOIN servicios s ON c.servicio_id = s.id
    JOIN fotografos f ON c.fotografos_id = f.id
    WHERE c.usuario_id = ?
    ORDER BY c.fecha DESC
");
$stmt->execute([$_SESSION['usuario_id']]);
$citas = $stmt->fetchAll();

include 'header.php';
?>

<h2>Mis Sesiones</h2>

<?php if(count($citas) > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Fotógrafo</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($citas as $cita): ?>
            <tr>
                <td><?php echo htmlspecialchars($cita['servicio_nombre']); ?></td>
                <td><?php echo htmlspecialchars($cita['fotografo_nombre']); ?></td>
                <td><?php echo date('d/m/Y', strtotime($cita['fecha'])); ?></td>
                <td><?php echo date('H:i', strtotime($cita['hora'])); ?></td>
                <td>$<?php echo number_format($cita['total'], 0, ',', '.'); ?></td>
                <td>
                    <span class="badge badge-<?php echo $cita['estado']; ?>">
                        <?php echo ucfirst($cita['estado']); ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="card">
        <p>No tienes sesiones agendadas.</p>
        <a href="agendar.php" class="btn btn-primary">Agendar Sesión</a>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>