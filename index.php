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
    
    <div class="carousel-container">
        <button class="carousel-btn prev-btn" onclick="moveSlide(-1)">&#10094;</button>
        
        <div class="carousel-wrapper">
            <div class="carousel-slide">
                <img src="img/boda1.jpg" alt="Sesión de Boda">
                <img src="img/casual1.jpg" alt="Sesión Casual">
                <img src="img/graduacion1.jpg" alt="Sesión de Graduación">
                <img src="img/infantil1.jpg" alt="Sesión Infantil">
            </div>
        </div>
        
        <button class="carousel-btn next-btn" onclick="moveSlide(1)">&#10095;</button>
    </div>
</section>

<script>
    let currentIndex = 0;

    function moveSlide(direction) {
        const slides = document.querySelectorAll('.carousel-slide img');
        const totalSlides = slides.length;
        const carousel = document.querySelector('.carousel-slide');

        currentIndex += direction;

        // Validar límites para que sea infinito
        if (currentIndex >= totalSlides) {
            currentIndex = 0;
        } else if (currentIndex < 0) {
            currentIndex = totalSlides - 1;
        }

        // Mover el carrusel
        const offset = -currentIndex * 100;
        carousel.style.transform = `translateX(${offset}%)`;
    }
</script>

<?php include 'footer.php'; ?>