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

$paso = isset($_GET['paso']) ? $_GET['paso'] : 1;
$servicio_id = isset($_GET['servicio']) ? $_GET['servicio'] : (isset($_POST['servicio_id']) ? $_POST['servicio_id'] : null);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $paso == 1) {
    $_SESSION['agendar_servicio'] = $_POST['servicio_id'];
    $_SESSION['agendar_fotografo'] = 99; // Forzamos el ID "Pendiente"
    header('Location: confirmacion.php'); // Saltamos directo al calendario
    exit();
}

$stmt = $pdo->query("SELECT * FROM servicios WHERE activo = 1");
$servicios = $stmt->fetchAll();

include 'header.php';
?>

<div class="card">
    <a href="dashboard.php" style="color: #667eea;">← Volver al Inicio</a>
    <h2>Paso 1: Elige tu sesión</h2>
    
    <div class="card-grid">
        <?php foreach($servicios as $servicio): ?>
        <div class="card">
            <h3><?php echo htmlspecialchars($servicio['nombre']); ?></h3>
            <p><?php echo htmlspecialchars($servicio['descripcion']); ?></p>
            <p class="precio">$<?php echo number_format($servicio['precio'], 0, ',', '.'); ?></p>
            <p>⏱️ <?php echo $servicio['duracion_minutos']; ?> min</p>
            
            <form method="POST">
                <input type="hidden" name="servicio_id" value="<?php echo $servicio['id']; ?>">
                <button type="submit" class="btn btn-primary">Seleccionar</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>