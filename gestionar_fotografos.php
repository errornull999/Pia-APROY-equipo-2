<?php
require_once 'database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $especialidad = $_POST['especialidad'];
    $experiencia = $_POST['experiencia'];
    $activo = isset($_POST['activo']) ? 1 : 0;
    
    if (isset($_POST['id']) && $_POST['id'] > 0) {
        // Editar
        $stmt = $pdo->prepare("UPDATE fotografos SET nombre = ?, especialidad = ?, experiencia_anos = ?, activo = ? WHERE id = ?");
        $stmt->execute([$nombre, $especialidad, $experiencia, $activo, $_POST['id']]);
        $mensaje = "Fotógrafo actualizado";
    } else {
        // Agregar
        $stmt = $pdo->prepare("INSERT INTO fotografos (nombre, especialidad, experiencia_anos, activo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $especialidad, $experiencia, $activo]);
        $mensaje = "Fotógrafo agregado";
    }
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $stmt = $pdo->prepare("DELETE FROM fotografos WHERE id = ?");
    $stmt->execute([$_GET['eliminar']]);
    $mensaje = "Fotógrafo eliminado";
}

// Obtener fotógrafos
$stmt = $pdo->query("SELECT * FROM fotografos ORDER BY id");
$fotografos = $stmt->fetchAll();

// Obtener para editar
$editando = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM fotografos WHERE id = ?");
    $stmt->execute([$_GET['editar']]);
    $editando = $stmt->fetch();
}

include 'header.php';
?>

<h2>Gestión de Fotógrafos</h2>

<?php if(isset($mensaje)): ?>
    <div class="alert alert-success"><?php echo $mensaje; ?></div>
<?php endif; ?>

<div class="card-grid">
    <!-- Formulario -->
    <div class="card">
        <h3><?php echo $editando ? 'Editar Fotógrafo' : 'Agregar Nuevo Fotógrafo'; ?></h3>
        <form method="POST">
            <?php if($editando): ?>
                <input type="hidden" name="id" value="<?php echo $editando['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Nombre del Fotógrafo</label>
                <input type="text" name="nombre" value="<?php echo $editando ? htmlspecialchars($editando['nombre']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Especialidad / Descripción</label>
                <input type="text" name="especialidad" value="<?php echo $editando ? htmlspecialchars($editando['especialidad']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Años de Experiencia</label>
                <input type="number" name="experiencia" value="<?php echo $editando ? $editando['experiencia_anos'] : '0'; ?>" required>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="activo" <?php echo ($editando && $editando['activo']) || !$editando ? 'checked' : ''; ?>>
                    Activo (disponible para citas)
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary"><?php echo $editando ? 'Actualizar' : 'Agregar'; ?></button>
            <?php if($editando): ?>
                <a href="gestionar_fotografos.php" class="btn">Cancelar</a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- Lista de fotógrafos -->
    <div class="card">
        <h3>Equipo de Fotógrafos</h3>
        <table class="table">
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Especialidad</th><th>Experiencia</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach($fotografos as $f): ?>
                <tr>
                    <td><?php echo $f['id']; ?></td>
                    <td><?php echo htmlspecialchars($f['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($f['especialidad']); ?></td>
                    <td><?php echo $f['experiencia_anos']; ?> años</td>
                    <td><?php echo $f['activo'] ? '✅ Activo' : '❌ Inactivo'; ?></td>
                    <td>
                        <a href="?editar=<?php echo $f['id']; ?>" class="btn btn-primary" style="padding: 5px 10px;">Editar</a>
                        <a href="?eliminar=<?php echo $f['id']; ?>" class="btn btn-danger" style="padding: 5px 10px;" onclick="return confirm('¿Eliminar este fotógrafo? Se perderán sus citas asociadas')">Eliminar</a>
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