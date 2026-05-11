<?php
require_once 'database.php';
include 'header.php';

// Obtener servicios activos
$stmt = $pdo->query("SELECT * FROM servicios WHERE activo = 1");
$servicios = $stmt->fetchAll();
?>

<div class="hero">
    <h1>Cada momento merece ser eterno</h1>
    <p>Capturamos tus recuerdos más valiosos con profesionalismo y pasión.</p>
    <a href="agendar.php" class="btn btn-primary">Agendar Sesión</a>
    <a href="#servicios" class="btn btn-primary2">Ver Servicios</a>
</div>


<section id="servicios">
    <h2 style="text-align: center; color: white;">Nuestros Servicios</h2>
    <p style="text-align: center; color: white;">Calidad excepcional en cada captura.</p>
    
    <div class="card-grid">
        <?php foreach($servicios as $servicio): ?>
        <div class="card">
            <h3><?php echo htmlspecialchars($servicio['nombre']); ?></h3>
            <p><?php echo htmlspecialchars($servicio['descripcion']); ?></p>
            <p class="precio">$<?php echo number_format($servicio['precio'], 0, ',', '.'); ?></p>
            <p>⏱️ <?php echo $servicio['duracion_minutos']; ?> minutos</p>
            <a href="agendar.php?servicio=<?php echo $servicio['id']; ?>" class="btn btn-primary">Agendar →</a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section id="portfolio" class="container">
    <h2 style="text-align: center; color: white; margin-top: 50px;">Nuestro Portafolio</h2>
    <p style="text-align: center; color: white; margin-bottom: 30px;">Una muestra de nuestros momentos favoritos capturados.</p>
    
    <div class="portfolio-grid">
        <div class="portfolio-item">
            <img src="img/boda1.jpg" alt="Sesión de Boda">
        </div>
        <div class="portfolio-item">
            <img src="img/casual1.jpg" alt="Sesión Casual">
        </div>
        <div class="portfolio-item">
            <img src="img/graduacion1.jpg" alt="Sesión de Graduación">
        </div>
        <div class="portfolio-item">
            <img src="img/infantil1.jpg" alt="Sesión Infantil">
        </div>
        <div class="portfolio-item">
            <img src="img/evento1.jpg" alt="Evento Social">
        </div>
        <div class="portfolio-item">
            <img src="img/retrato1.jpg" alt="Retrato Profesional">
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>