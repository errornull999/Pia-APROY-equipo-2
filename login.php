<?php
require_once 'database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Buscar usuario por email
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    
    // Verificar contraseña (funciona con y sin encriptar)
    $password_correcta = false;
    
    if ($usuario) {
        // Primero probar comparación normal (texto plano)
        if ($password == $usuario['password']) {
            $password_correcta = true;
        }
        // Si no, probar con password_verify (encriptado)
        elseif (password_verify($password, $usuario['password'])) {
            $password_correcta = true;
        }
    }
    
    if ($usuario && $password_correcta) {
        // Guardar datos en sesión
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
        $_SESSION['rol'] = $usuario['rol'];
        
        // REDIRIGIR SEGÚN EL ROL
        if ($usuario['rol'] == 'admin') {
            header('Location: admin.php');
            exit();
        } else {
            header('Location: dashboard.php');
            exit();
        }
    } else {
        $error = 'Correo o contraseña incorrectos';
    }
}

include 'header.php';
?>

<div class="form-container">
    <h2>¡Bienvenido de nuevo!</h2>
    <p>Ingresa tus datos para continuar.</p>
    
    <?php if($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" name="email" placeholder="coreo@coreo" required>
        </div>
        
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" placeholder="***" required>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">Iniciar Sesión</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
    </p>
</div>

<?php include 'footer.php'; ?>