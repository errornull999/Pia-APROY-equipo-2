<?php
require_once 'database.php';

// Validar que sea admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header('Location: login.php');
    exit();
}

$cita_id = $_GET['id'] ?? $_POST['cita_id'] ?? 0;
$error = null;

// Lógica para guardar la asignación (cuando se presiona el botón)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fotografo_id = $_POST['fotografos_id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    // 1. Validar que el fotógrafo NO esté ocupado a esa misma hora
    $stmt = $pdo->prepare("SELECT id FROM citas WHERE fotografos_id = ? AND fecha = ? AND hora = ? AND estado != 'cancelada' AND id != ?");
    $stmt->execute([$fotografo_id, $fecha, $hora, $cita_id]);

    if ($stmt->fetch()) {
        $error = "❌ El fotógrafo seleccionado ya tiene otra sesión agendada en esa fecha y hora.";
    } else {
        // 2. Si está libre, actualizar la cita y regresarlo a la lista
        $update = $pdo->prepare("UPDATE citas SET fotografos_id = ?, estado = 'confirmada' WHERE id = ?");
        $update->execute([$fotografo_id, $cita_id]);
        
        // Redirigir de vuelta al panel de citas
        header('Location: gestionar_citas.php');
        exit();
    }
}

// Obtener los datos de esta cita específica para mostrarlos en pantalla
$stmt = $pdo->prepare("SELECT c.*, s.nombre as servicio, u.nombre_completo as cliente FROM citas c JOIN servicios s ON c.servicio_id = s.id JOIN usuarios u ON c.usuario_id = u.id WHERE c.id = ?");
$stmt->execute([$cita_id]);
$cita = $stmt->fetch();

// Obtener la lista de fotógrafos reales (excluyendo al 99 "Pendiente")
$fotografos = $pdo->query("SELECT id, nombre FROM fotografos WHERE id != 99 AND activo = 1")->fetchAll();

include 'header.php';
?>

<div class="card">
    <h2>Asignar Fotógrafo a la Sesión</h2>
    
    <?php if($error): ?>
        <div class="alert alert-error" style="background: #fed7d7; color: #c53030; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div style="background: #f7fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($cita['cliente']); ?></p>
        <p><strong>Servicio a realizar:</strong> <?php echo htmlspecialchars($cita['servicio']); ?></p>
        <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($cita['fecha'])); ?></p>
        <p><strong>Hora:</strong> <?php echo $cita['hora']; ?></p>
    </div>

    <form method="POST">
        <input type="hidden" name="cita_id" value="<?php echo $cita['id']; ?>">
        <input type="hidden" name="fecha" value="<?php echo $cita['fecha']; ?>">
        <input type="hidden" name="hora" value="<?php echo $cita['hora']; ?>">

        <div class="form-group">
            <label>Selecciona al fotógrafo disponible:</label>
            <select name="fotografos_id" required>
                <option value="">-- Elige un fotógrafo del equipo --</option>
                <?php foreach($fotografos as $f): ?>
                    <option value="<?php echo $f['id']; ?>"><?php echo htmlspecialchars($f['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <br>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Asignar y Confirmar Cita</button>
            <a href="gestionar_citas.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>