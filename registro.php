<?php
require_once 'database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        // Verificar si el email ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = 'El correo electrónico ya está registrado';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_completo, email, password, rol) VALUES (?, ?, ?, 'cliente')");
            
            if ($stmt->execute([$nombre, $email, $hashed_password])) {
                $success = 'Registro exitoso. Ahora puedes iniciar sesión.';
            } else {
                $error = 'Error al registrar el usuario';
            }
        }
    }
}

include 'header.php'; ?>

<div class="form-container">
    <h2>Crea tu cuenta</h2>
    <p>Regístrate para agendar tu primera sesión.</p>
    
    <?php if($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Nombre Completo</label>
            <input type="text" name="nombre" required>
        </div>
        
        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label>Confirmar Contraseña</label>
            <input type="password" name="confirm_password" required>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">Registrarse</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
    </p>
</div>

<?php include 'footer.php'; ?>