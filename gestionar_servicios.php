<?php
require_once 'database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Procesar formulario de agregar/editar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $duracion = $_POST['duracion'];
    $precio = $_POST['precio'];
    $activo = isset($_POST['activo']) ? 1 : 0;
    
    if (isset($_POST['id']) && $_POST['id'] > 0) {
        // Editar
        $stmt = $pdo->prepare("UPDATE servicios SET nombre = ?, descripcion = ?, duracion_minutos = ?, precio = ?, activo = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $duracion, $precio, $activo, $_POST['id']]);
        $mensaje = "Servicio actualizado";
    } else {
        // Agregar
        $stmt = $pdo->prepare("INSERT INTO servicios (nombre, descripcion, duracion_minutos, precio, activo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $duracion, $precio, $activo]);
        $mensaje = "Servicio agregado";
    }
}

// Eliminar servicio
if (isset($_GET['eliminar'])) {
    $stmt = $pdo->prepare("DELETE FROM servicios WHERE id = ?");
    $stmt->execute([$_GET['eliminar']]);
    $mensaje = "Servicio eliminado";
}

// Obtener servicios
$stmt = $pdo->query("SELECT * FROM servicios ORDER BY id");
$servicios = $stmt->fetchAll();

// Obtener servicio para editar
$editando = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = ?");
    $stmt->execute([$_GET['editar']]);
    $editando = $stmt->fetch();
}

include 'header.php';
?>

<h2>Gestión de Servicios</h2>

<?php if(isset($mensaje)): ?>
    <div class="alert alert-success"><?php echo $mensaje; ?></div>
<?php endif; ?>

<div class="card-grid">
    <!-- Formulario -->
    <div class="card">
        <h3><?php echo $editando ? 'Editar Servicio' : 'Agregar Nuevo Servicio'; ?></h3>
        <form method="POST">
            <?php if($editando): ?>
                <input type="hidden" name="id" value="<?php echo $editando['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Nombre del Servicio</label>
                <input type="text" name="nombre" value="<?php echo $editando ? htmlspecialchars($editando['nombre']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3" style="width: 100%; padding: 8px;"><?php echo $editando ? htmlspecialchars($editando['descripcion']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Duración (minutos)</label>
                <input type="number" name="duracion" value="<?php echo $editando ? $editando['duracion_minutos'] : '60'; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Precio ($)</label>
                <input type="number" name="precio" step="100" value="<?php echo $editando ? $editando['precio'] : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="activo" <?php echo ($editando && $editando['activo']) || !$editando ? 'checked' : ''; ?>>
                    Activo (visible para clientes)
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary"><?php echo $editando ? 'Actualizar' : 'Agregar'; ?></button>
            <?php if($editando): ?>
                <a href="gestionar_servicios.php" class="btn">Cancelar</a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- Lista de servicios -->
    <div class="card">
        <h3>Servicios Actuales</h3>
        <table class="table">
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Duración</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach($servicios as $s): ?>
                <tr>
                    <td><?php echo $s['id']; ?></td>
                    <td><?php echo htmlspecialchars($s['nombre']); ?></td>
                    <td><?php echo $s['duracion_minutos']; ?> min</td>
                    <td>$<?php echo number_format($s['precio'], 0, ',', '.'); ?></td>
                    <td><?php echo $s['activo'] ? '✅ Activo' : '❌ Inactivo'; ?></td>
                    <td>
                        <a href="?editar=<?php echo $s['id']; ?>" class="btn btn-primary" style="padding: 5px 10px;">Editar</a>
                        <a href="?eliminar=<?php echo $s['id']; ?>" class="btn btn-danger" style="padding: 5px 10px;" onclick="return confirm('¿Eliminar este servicio?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 20px;">
    <a href="admin.php" class="btn">← Volver al Panel</a>
</div>

<?php include 'footer.php'; ?>