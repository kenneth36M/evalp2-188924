<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Cálculos Matemáticos</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🧮</text></svg>">
</head>
<body>
    <!-- Partículas flotantes de fondo -->
    <div class="floating-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="dashboard-container">
        <div class="dashboard-welcome">
            <div class="welcome-icon">👋</div>
            <h2>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Explora las herramientas matemáticas disponibles</p>
        </div>
        
        <div class="menu-options">
            <a href="calculos.php" class="menu-card">
                <div class="menu-card-icon">📐</div>
                <div class="menu-card-title">Calcular Áreas y Volúmenes</div>
                <div class="menu-card-description">
                    Calcula áreas de figuras geométricas y volúmenes de sólidos
                </div>
            </a>
            
            <a href="cuadrantes.php" class="menu-card">
                <div class="menu-card-icon">📊</div>
                <div class="menu-card-title">Identificación de Cuadrantes</div>
                <div class="menu-card-description">
                    Identifica y visualiza puntos en el plano cartesiano
                </div>
            </a>
        </div>
        
        <div class="dashboard-footer">
            <a href="logout.php" class="logout-btn">
                <span class="logout-icon">🚪</span>
                Cerrar Sesión
            </a>
        </div>
    </div>

    <script>
        // Efecto de partículas interactivas
        document.addEventListener('mousemove', function(e) {
            const particles = document.querySelectorAll('.particle');
            particles.forEach((particle, index) => {
                const speed = (index + 1) * 0.02;
                const x = e.clientX * speed;
                const y = e.clientY * speed;
                particle.style.transform = `translate(${x}px, ${y}px)`;
            });
        });

        // Animación de entrada para las tarjetas
        const menuCards = document.querySelectorAll('.menu-card');
        menuCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.style.animation = 'slideUp 0.6s ease-out forwards';
        });

        // Efecto de hover mejorado para las tarjetas
        menuCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Animación de bienvenida
        const welcomeIcon = document.querySelector('.welcome-icon');
        if (welcomeIcon) {
            welcomeIcon.style.animation = 'bounce 2s infinite';
        }
    </script>
</body>
</html>