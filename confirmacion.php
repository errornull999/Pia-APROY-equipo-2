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

if (!isset($_SESSION['agendar_servicio']) || !isset($_SESSION['agendar_fotografo'])) {
    header('Location: agendar.php');
    exit();
}

$servicio_id = $_SESSION['agendar_servicio'];
$fotografo_id = $_SESSION['agendar_fotografo'];

// Obtener datos
$stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = ?");
$stmt->execute([$servicio_id]);
$servicio = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM fotografos WHERE id = ?");
$stmt->execute([$fotografo_id]);
$fotografo = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    
    // Validar que no esté ocupado
    $stmt = $pdo->prepare("SELECT id FROM citas WHERE fotografos_id = ? AND fecha = ? AND hora = ? AND estado != 'cancelada'");
    $stmt->execute([$fotografo_id, $fecha, $hora]);
    
    if ($stmt->fetch()) {
        $error = 'El fotógrafo no está disponible en esa fecha y hora';
    } else {
        $stmt = $pdo->prepare("INSERT INTO citas (usuario_id, servicio_id, fotografos_id, fecha, hora, estado, total) VALUES (?, ?, ?, ?, ?, 'confirmada', ?)");
        
        if ($stmt->execute([$_SESSION['usuario_id'], $servicio_id, $fotografo_id, $fecha, $hora, $servicio['precio']])) {
            // Limpiar sesión
            unset($_SESSION['agendar_servicio']);
            unset($_SESSION['agendar_fotografo']);
            
            $success = '¡Sesión agendada exitosamente!';
            header('refresh:2; url=mis_citas.php');
        } else {
            $error = 'Error al agendar la sesión';
        }
    }
}

include 'header.php';
?>

<div class="card">
    <a href="elegir_fotografo.php" style="color: #667eea;">← Volver al Inicio</a>
    <h2>Confirmación Final</h2>
    
    <?php if(isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="card" style="background: #f7fafc;">
        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
        <p><strong>Servicio:</strong> <?php echo htmlspecialchars($servicio['nombre']); ?></p>
        <p><strong>Fotógrafo:</strong> <?php echo htmlspecialchars($fotografo['nombre']); ?></p>
        <p><strong>Total:</strong> $<?php echo number_format($servicio['precio'], 0, ',', '.'); ?></p>
    </div>
    
    <form method="POST">
        <div class="form-group">
            <label>Fecha</label>
            <input type="date" name="fecha" min="<?php echo date('Y-m-d'); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Hora</label>
            <select name="hora" required>
                <option value="09:00:00">09:00 AM</option>
                <option value="10:00:00">10:00 AM</option>
                <option value="11:00:00">11:00 AM</option>
                <option value="12:00:00">12:00 PM</option>
                <option value="14:00:00">02:00 PM</option>
                <option value="15:00:00">03:00 PM</option>
                <option value="16:00:00">04:00 PM</option>
                <option value="17:00:00">05:00 PM</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Confirmar y Agendar</button>
    </form>
</div>

<?php include 'footer.php'; ?>