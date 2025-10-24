<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$result = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validación de campos
    if (!isset($_POST['x']) || !isset($_POST['y'])) {
        $error = 'Todos los campos son requeridos';
    } else {
        $x = $_POST['x'];
        $y = $_POST['y'];

        // Validar que sean números
        if (!is_numeric($x) || !is_numeric($y)) {
            $error = 'Los valores deben ser numéricos';
        } else {
            $x = floatval($x);
            $y = floatval($y);

            // Casos especiales
            if ($x == 0 && $y == 0) {
                $result = "El punto está en el origen (0,0)";
            } else if ($x == 0) {
                $result = "El punto está sobre el eje Y";
            } else if ($y == 0) {
                $result = "El punto está sobre el eje X";
            } else {
                // Determinar el cuadrante
                if ($x > 0 && $y > 0) {
                    $result = "El punto ($x, $y) está en el Cuadrante I";
                } else if ($x < 0 && $y > 0) {
                    $result = "El punto ($x, $y) está en el Cuadrante II";
                } else if ($x < 0 && $y < 0) {
                    $result = "El punto ($x, $y) está en el Cuadrante III";
                } else {
                    $result = "El punto ($x, $y) está en el Cuadrante IV";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identificación de Cuadrantes - Sistema Matemático</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📊</text></svg>">
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

    <div class="calculator-container">
        <div class="page-header">
            <div class="page-icon">📊</div>
            <h2>Identificación de Cuadrantes</h2>
            <p class="page-subtitle">Visualiza y identifica puntos en el plano cartesiano</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error">
                <span class="error-icon">⚠️</span>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="plane-info">
            <h3>🎯 Reglas de los Cuadrantes</h3>
            <div class="quadrant-rules">
                <div class="quadrant-rule quadrant-i">
                    <span class="quadrant-color"></span>
                    <span class="quadrant-text"><strong>Cuadrante I:</strong> X > 0, Y > 0</span>
                </div>
                <div class="quadrant-rule quadrant-ii">
                    <span class="quadrant-color"></span>
                    <span class="quadrant-text"><strong>Cuadrante II:</strong> X < 0, Y > 0</span>
                </div>
                <div class="quadrant-rule quadrant-iii">
                    <span class="quadrant-color"></span>
                    <span class="quadrant-text"><strong>Cuadrante III:</strong> X < 0, Y < 0</span>
                </div>
                <div class="quadrant-rule quadrant-iv">
                    <span class="quadrant-color"></span>
                    <span class="quadrant-text"><strong>Cuadrante IV:</strong> X > 0, Y < 0</span>
                </div>
            </div>
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label for="x">Coordenada X:</label>
                <input type="number" step="any" id="x" name="x" required placeholder="Ingrese la coordenada X">
            </div>

            <div class="form-group">
                <label for="y">Coordenada Y:</label>
                <input type="number" step="any" id="y" name="y" required placeholder="Ingrese la coordenada Y">
            </div>

            <button type="submit">Identificar Cuadrante</button>
        </form>

        <?php if ($result): ?>
        <div class="result">
            <h3>Resultado:</h3>
            <p><?php echo $result; ?></p>
            <div class="plane-visualization" id="planeVisualization"></div>
        </div>
        <?php endif; ?>

        <a href="dashboard.php" class="back-btn">Volver al Dashboard</a>
        <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
    </div>

    <script>
        // Visualización del plano cartesiano mejorada
        function drawPlane() {
            const canvas = document.createElement('canvas');
            canvas.width = 500;
            canvas.height = 500;
            const ctx = canvas.getContext('2d');
            const scale = 50; // Escala para las coordenadas (50 píxeles = 1 unidad)
            
            // Configurar el plano
            ctx.translate(canvas.width/2, canvas.height/2);
            ctx.scale(1, -1);

            // Fondo con gradiente sutil
            const gradient = ctx.createRadialGradient(0, 0, 0, 0, 0, canvas.width/2);
            gradient.addColorStop(0, 'rgba(255, 255, 255, 0.1)');
            gradient.addColorStop(1, 'rgba(240, 248, 255, 0.3)');
            ctx.fillStyle = gradient;
            ctx.fillRect(-canvas.width/2, -canvas.height/2, canvas.width, canvas.height);

            // Dibujar cuadrícula con colores suaves
            ctx.strokeStyle = 'rgba(200, 200, 200, 0.3)';
            ctx.lineWidth = 0.5;
            for(let i = -canvas.width/2; i <= canvas.width/2; i += scale) {
                // Líneas verticales
                ctx.beginPath();
                ctx.moveTo(i, -canvas.height/2);
                ctx.lineTo(i, canvas.height/2);
                ctx.stroke();
                // Líneas horizontales
                ctx.beginPath();
                ctx.moveTo(-canvas.width/2, i);
                ctx.lineTo(canvas.width/2, i);
                ctx.stroke();
            }
            
            // Dibujar ejes principales con colores vibrantes
            ctx.strokeStyle = '#2c3e50';
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.moveTo(-canvas.width/2, 0);
            ctx.lineTo(canvas.width/2, 0);
            ctx.moveTo(0, -canvas.height/2);
            ctx.lineTo(0, canvas.height/2);
            ctx.stroke();

            // Dibujar marcas en los ejes con mejor estilo
            ctx.scale(1, -1); // Invertir para el texto
            ctx.font = 'bold 14px Inter, Arial, sans-serif';
            ctx.fillStyle = '#2c3e50';
            for(let i = -5; i <= 5; i++) {
                if(i !== 0) {
                    // Marcas en X
                    ctx.fillText(i.toString(), i * scale - 8, 25);
                    // Marcas en Y
                    ctx.fillText(i.toString(), 15, -i * scale + 8);
                }
            }

            // Dibujar etiquetas de los cuadrantes con colores distintivos
            ctx.font = 'bold 18px Inter, Arial, sans-serif';
            ctx.fillStyle = '#ff6b6b';
            ctx.fillText('I', 100, -100);
            ctx.fillStyle = '#4ecdc4';
            ctx.fillText('II', -100, -100);
            ctx.fillStyle = '#45b7d1';
            ctx.fillText('III', -100, 100);
            ctx.fillStyle = '#f39c12';
            ctx.fillText('IV', 100, 100);
            
            // Etiquetas de ejes
            ctx.font = 'bold 16px Inter, Arial, sans-serif';
            ctx.fillStyle = '#2c3e50';
            ctx.fillText('X', canvas.width/2 - 25, 25);
            ctx.fillText('Y', 25, -canvas.height/2 + 25);
            
            // Dibujar punto si hay coordenadas
            const x = parseFloat(document.getElementById('x').value);
            const y = parseFloat(document.getElementById('y').value);
            if (!isNaN(x) && !isNaN(y)) {
                ctx.scale(1, -1); // Volver a la escala original para dibujar el punto
                
                // Determinar color del punto según el cuadrante
                let pointColor = '#ff6b6b'; // Rojo por defecto
                if (x > 0 && y > 0) pointColor = '#ff6b6b'; // Cuadrante I - Rojo
                else if (x < 0 && y > 0) pointColor = '#4ecdc4'; // Cuadrante II - Turquesa
                else if (x < 0 && y < 0) pointColor = '#45b7d1'; // Cuadrante III - Azul
                else if (x > 0 && y < 0) pointColor = '#f39c12'; // Cuadrante IV - Naranja
                else if (x === 0 && y === 0) pointColor = '#9b59b6'; // Origen - Púrpura
                else if (x === 0) pointColor = '#e74c3c'; // Eje Y - Rojo oscuro
                else if (y === 0) pointColor = '#27ae60'; // Eje X - Verde
                
                // Dibujar sombra del punto
                ctx.shadowColor = 'rgba(0, 0, 0, 0.3)';
                ctx.shadowBlur = 10;
                ctx.shadowOffsetX = 2;
                ctx.shadowOffsetY = 2;
                
                // Dibujar círculo de fondo blanco con borde
                ctx.beginPath();
                ctx.arc(x * scale, y * scale, 12, 0, 2 * Math.PI);
                ctx.fillStyle = 'white';
                ctx.fill();
                ctx.strokeStyle = pointColor;
                ctx.lineWidth = 3;
                ctx.stroke();
                
                // Dibujar punto principal
                ctx.shadowColor = 'transparent';
                ctx.beginPath();
                ctx.arc(x * scale, y * scale, 8, 0, 2 * Math.PI);
                ctx.fillStyle = pointColor;
                ctx.fill();
                
                // Dibujar líneas punteadas hasta los ejes con colores
                ctx.setLineDash([8, 4]);
                ctx.strokeStyle = pointColor;
                ctx.lineWidth = 2;
                ctx.globalAlpha = 0.7;
                ctx.beginPath();
                ctx.moveTo(x * scale, 0);
                ctx.lineTo(x * scale, y * scale);
                ctx.moveTo(0, y * scale);
                ctx.lineTo(x * scale, y * scale);
                ctx.stroke();
                ctx.setLineDash([]);
                ctx.globalAlpha = 1;
                
                // Mostrar coordenadas con estilo mejorado
                ctx.scale(1, -1); // Invertir para escribir texto
                ctx.font = 'bold 16px Inter, Arial, sans-serif';
                ctx.fillStyle = pointColor;
                ctx.strokeStyle = 'white';
                ctx.lineWidth = 3;
                
                const coordText = `(${x}, ${y})`;
                const textX = x * scale + 15;
                const textY = -y * scale - 15;
                
                // Sombra del texto
                ctx.strokeText(coordText, textX, textY);
                ctx.fillText(coordText, textX, textY);
                
                // Dibujar un pequeño indicador de cuadrante
                ctx.font = 'bold 12px Inter, Arial, sans-serif';
                ctx.fillStyle = pointColor;
                let quadrantText = '';
                if (x > 0 && y > 0) quadrantText = 'Cuadrante I';
                else if (x < 0 && y > 0) quadrantText = 'Cuadrante II';
                else if (x < 0 && y < 0) quadrantText = 'Cuadrante III';
                else if (x > 0 && y < 0) quadrantText = 'Cuadrante IV';
                else if (x === 0 && y === 0) quadrantText = 'Origen';
                else if (x === 0) quadrantText = 'Eje Y';
                else if (y === 0) quadrantText = 'Eje X';
                
                ctx.strokeStyle = 'white';
                ctx.lineWidth = 2;
                ctx.strokeText(quadrantText, textX, textY + 20);
                ctx.fillText(quadrantText, textX, textY + 20);
            }
            
            // Agregar el canvas al DOM
            const container = document.getElementById('planeVisualization');
            container.innerHTML = '';
            container.appendChild(canvas);
        }

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

        // Animación de entrada para las reglas de cuadrantes
        const quadrantRules = document.querySelectorAll('.quadrant-rule');
        quadrantRules.forEach((rule, index) => {
            rule.style.animationDelay = `${index * 0.1}s`;
            rule.style.animation = 'slideUp 0.6s ease-out forwards';
        });

        // Dibujar cuando se envía el formulario y cuando se cambian los valores
        if (document.querySelector('.result')) {
            drawPlane();
            document.getElementById('x').addEventListener('input', drawPlane);
            document.getElementById('y').addEventListener('input', drawPlane);
        }

        // Efecto de hover para las reglas de cuadrantes
        quadrantRules.forEach(rule => {
            rule.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px) scale(1.02)';
            });
            
            rule.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Animación del icono de la página
        const pageIcon = document.querySelector('.page-icon');
        if (pageIcon) {
            pageIcon.style.animation = 'bounce 2s infinite';
        }
    </script>
</body>
</html>