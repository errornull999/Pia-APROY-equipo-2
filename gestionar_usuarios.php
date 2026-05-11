<?php
require_once 'database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Cambiar rol de usuario
if (isset($_GET['cambiar_rol'])) {
    $id = $_GET['cambiar_rol'];
    $stmt = $pdo->prepare("UPDATE usuarios SET rol = IF(rol = 'cliente', 'admin', 'cliente') WHERE id = ?");
    $stmt->execute([$id]);
    $mensaje = "Rol de usuario actualizado";
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ? AND rol != 'admin'");
    $stmt->execute([$id]);
    $mensaje = "Usuario eliminado";
}

// Obtener usuarios
$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY fecha_registro DESC");
$usuarios = $stmt->fetchAll();

include 'header.php';
?>

<h2>Gestión de Usuarios</h2>

<?php if(isset($mensaje)): ?>
    <div class="alert alert-success"><?php echo $mensaje; ?></div>
<?php endif; ?>

<div class="card">
    <table class="table">
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Fecha Registro</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php foreach($usuarios as $u): ?>
            <tr>
                <td><?php echo $u['id']; ?></td>
                <td><?php echo htmlspecialchars($u['nombre_completo']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td>
                    <span style="background: <?php echo $u['rol'] == 'admin' ? '#ed8936' : '#48bb78'; ?>; color: white; padding: 3px 8px; border-radius: 5px;">
                        <?php echo ucfirst($u['rol']); ?>
                    </span>
                </td>
                <td><?php echo date('d/m/Y H:i', strtotime($u['fecha_registro'])); ?></td>
                <td>
                    <?php if($u['rol'] != 'admin'): ?>
                        <a href="?cambiar_rol=<?php echo $u['id']; ?>" class="btn btn-primary" style="padding: 5px 10px;">Hacer Admin</a>
                        <a href="?eliminar=<?php echo $u['id']; ?>" class="btn btn-danger" style="padding: 5px 10px;" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                    <?php else: ?>
                        <span style="color: #999;">Admin principal</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    <a href="index.php" class="btn">← Volver al Panel</a>
</div>

<?php include 'footer.php'; ?>