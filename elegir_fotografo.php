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
?>

if (!isset($_SESSION['agendar_servicio'])) {
    header('Location: agendar.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['agendar_fotografo'] = $_POST['fotografo_id'];
    header('Location: confirmacion.php');
    exit();
}

$stmt = $pdo->query("SELECT * FROM fotografos WHERE activo = 1");
$fotografos = $stmt->fetchAll();

include 'header.php';
?>

<div class="card">
    <a href="agendar.php" style="color: #667eea;">← Volver al Inicio</a>
    <h2>Paso 2: Elige a tu fotógrafo</h2>
    
    <div class="card-grid">
        <?php foreach($fotografos as $fotografo): ?>
        <div class="card">
            <h3><?php echo htmlspecialchars($fotografo['nombre']); ?></h3>
            <p><?php echo htmlspecialchars($fotografo['especialidad']); ?></p>
            <p>⭐ <?php echo $fotografo['experiencia_anos']; ?> años de experiencia</p>
            
            <form method="POST">
                <input type="hidden" name="fotografo_id" value="<?php echo $fotografo['id']; ?>">
                <button type="submit" class="btn btn-primary">Seleccionar</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>