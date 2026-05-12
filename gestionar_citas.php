<?php
require_once 'database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Procesar acciones
if (isset($_GET['accion'])) {
    $id = $_GET['id'];
    
    if ($_GET['accion'] == 'confirmar') {
        $stmt = $pdo->prepare("UPDATE citas SET estado = 'confirmada' WHERE id = ?");
        $stmt->execute([$id]);
        $mensaje = "Cita confirmada";
    } elseif ($_GET['accion'] == 'cancelar') {
        $stmt = $pdo->prepare("UPDATE citas SET estado = 'cancelada' WHERE id = ?");
        $stmt->execute([$id]);
        $mensaje = "Cita cancelada";
    } elseif ($_GET['accion'] == 'completar') {
        $stmt = $pdo->prepare("UPDATE citas SET estado = 'completada' WHERE id = ?");
        $stmt->execute([$id]);
        $mensaje = "Cita completada";
    } elseif ($_GET['accion'] == 'eliminar') {
        $stmt = $pdo->prepare("DELETE FROM citas WHERE id = ?");
        $stmt->execute([$id]);
        $mensaje = "Cita eliminada";
    }
}

// Obtener todas las citas con detalles
$stmt = $pdo->query("
    SELECT c.*, u.nombre_completo as cliente, u.email, s.nombre as servicio, f.nombre as fotografo
    FROM citas c
    JOIN usuarios u ON c.usuario_id = u.id
    JOIN servicios s ON c.servicio_id = s.id
    JOIN fotografos f ON c.fotografos_id = f.id
    ORDER BY c.fecha DESC, c.hora DESC
");
$citas = $stmt->fetchAll();

include 'header.php';
?>

<h2>Gestión de Citas</h2>

<?php if(isset($mensaje)): ?>
    <div class="alert alert-success"><?php echo $mensaje; ?></div>
<?php endif; ?>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Email</th>
                <th>Servicio</th>
                <th>Fotógrafo</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($citas as $cita): ?>
            <tr>
                <td><?php echo $cita['id']; ?></td>
                <td><?php echo htmlspecialchars($cita['cliente']); ?></td>
                <td><?php echo htmlspecialchars($cita['email']); ?></td>
                <td><?php echo htmlspecialchars($cita['servicio']); ?></td>
                <td><?php echo htmlspecialchars($cita['fotografo']); ?></td>
                <td><?php echo date('d/m/Y', strtotime($cita['fecha'])); ?></td>
                <td><?php echo $cita['hora']; ?></td>
                <td>$<?php echo number_format($cita['total'], 0, ',', '.'); ?></td>
                <td>
                    <span style="background: 
                        <?php echo $cita['estado'] == 'confirmada' ? '#48bb78' : ($cita['estado'] == 'pendiente' ? '#ed8936' : ($cita['estado'] == 'completada' ? '#4299e1' : '#f56565')); ?>; 
                        color: white; padding: 5px 10px; border-radius: 5px;">
                        <?php echo ucfirst($cita['estado']); ?>
                    </span>
                </td>
                <td>
                    <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                        <?php if($cita['estado'] == 'pendiente'): ?>
                            <a href="?accion=confirmar&id=<?php echo $cita['id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">Confirmar</a>
                        <?php endif; ?>
                        <?php if($cita['estado'] == 'confirmada'): ?>
                            <a href="?accion=completar&id=<?php echo $cita['id']; ?>" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Completar</a>
                        <?php endif; ?>
                        <?php if($cita['estado'] != 'cancelada' && $cita['estado'] != 'completada'): ?>
                            <a href="?accion=cancelar&id=<?php echo $cita['id']; ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('¿Cancelar esta cita?')">Cancelar</a>
                        <?php endif; ?>
                        <a href="?accion=eliminar&id=<?php echo $cita['id']; ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px; background: #c00;" onclick="return confirm('¿Eliminar permanentemente?')">Eliminar</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    <a href="admin.php" class="btn">← Volver al Panel</a>
</div>

<?php include 'footer.php'; ?>