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
        // Visualización del plano cartesiano ultra detallada
        function drawPlane() {
            const canvas = document.createElement('canvas');
            canvas.width = 700;
            canvas.height = 700;
            const ctx = canvas.getContext('2d');
            const scale = 60; // Escala para las coordenadas (60 píxeles = 1 unidad)
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            
            // Configurar el plano
            ctx.translate(centerX, centerY);
            ctx.scale(1, -1);

            // Fondo con gradiente radial detallado
            const gradient = ctx.createRadialGradient(0, 0, 0, 0, 0, canvas.width/2);
            gradient.addColorStop(0, 'rgba(255, 255, 255, 0.15)');
            gradient.addColorStop(0.3, 'rgba(240, 248, 255, 0.1)');
            gradient.addColorStop(0.7, 'rgba(230, 240, 250, 0.05)');
            gradient.addColorStop(1, 'rgba(220, 230, 240, 0.02)');
            ctx.fillStyle = gradient;
            ctx.fillRect(-canvas.width/2, -canvas.height/2, canvas.width, canvas.height);

            // Dibujar cuadrícula principal (unidades enteras)
            ctx.strokeStyle = 'rgba(180, 180, 180, 0.4)';
            ctx.lineWidth = 1;
            for(let i = -Math.floor(canvas.width/2/scale); i <= Math.floor(canvas.width/2/scale); i++) {
                // Líneas verticales
                ctx.beginPath();
                ctx.moveTo(i * scale, -canvas.height/2);
                ctx.lineTo(i * scale, canvas.height/2);
                ctx.stroke();
                // Líneas horizontales
                ctx.beginPath();
                ctx.moveTo(-canvas.width/2, i * scale);
                ctx.lineTo(canvas.width/2, i * scale);
                ctx.stroke();
            }

            // Dibujar cuadrícula secundaria (medios)
            ctx.strokeStyle = 'rgba(200, 200, 200, 0.2)';
            ctx.lineWidth = 0.5;
            for(let i = -Math.floor(canvas.width/2/scale); i <= Math.floor(canvas.width/2/scale); i++) {
                // Líneas verticales de medio
                ctx.beginPath();
                ctx.moveTo(i * scale + scale/2, -canvas.height/2);
                ctx.lineTo(i * scale + scale/2, canvas.height/2);
                ctx.stroke();
                // Líneas horizontales de medio
                ctx.beginPath();
                ctx.moveTo(-canvas.width/2, i * scale + scale/2);
                ctx.lineTo(canvas.width/2, i * scale + scale/2);
                ctx.stroke();
            }
            
            // Dibujar ejes principales con estilo mejorado
            ctx.strokeStyle = '#1a1a1a';
            ctx.lineWidth = 4;
            ctx.beginPath();
            ctx.moveTo(-canvas.width/2, 0);
            ctx.lineTo(canvas.width/2, 0);
            ctx.moveTo(0, -canvas.height/2);
            ctx.lineTo(0, canvas.height/2);
            ctx.stroke();

            // Dibujar flechas en los ejes
            ctx.fillStyle = '#1a1a1a';
            // Flecha eje X (derecha)
            ctx.beginPath();
            ctx.moveTo(canvas.width/2 - 20, 0);
            ctx.lineTo(canvas.width/2 - 10, 5);
            ctx.lineTo(canvas.width/2 - 10, -5);
            ctx.closePath();
            ctx.fill();
            // Flecha eje Y (arriba)
            ctx.beginPath();
            ctx.moveTo(0, canvas.height/2 - 20);
            ctx.lineTo(5, canvas.height/2 - 10);
            ctx.lineTo(-5, canvas.height/2 - 10);
            ctx.closePath();
            ctx.fill();

            // Dibujar marcas en los ejes con estilo profesional
            ctx.scale(1, -1); // Invertir para el texto
            ctx.font = 'bold 12px Inter, Arial, sans-serif';
            ctx.fillStyle = '#2c3e50';
            
            // Marcas en eje X
            for(let i = -Math.floor(canvas.width/2/scale); i <= Math.floor(canvas.width/2/scale); i++) {
                if(i !== 0) {
                    ctx.fillText(i.toString(), i * scale - 6, 20);
                    // Líneas de marca en el eje
                    ctx.strokeStyle = '#2c3e50';
                    ctx.lineWidth = 2;
                    ctx.beginPath();
                    ctx.moveTo(i * scale, -5);
                    ctx.lineTo(i * scale, 5);
                    ctx.stroke();
                }
            }
            
            // Marcas en eje Y
            for(let i = -Math.floor(canvas.height/2/scale); i <= Math.floor(canvas.height/2/scale); i++) {
                if(i !== 0) {
                    ctx.fillText(i.toString(), 15, -i * scale + 4);
                    // Líneas de marca en el eje
                    ctx.strokeStyle = '#2c3e50';
                    ctx.lineWidth = 2;
                    ctx.beginPath();
                    ctx.moveTo(-5, i * scale);
                    ctx.lineTo(5, i * scale);
                    ctx.stroke();
                }
            }

            // Dibujar etiquetas de los cuadrantes con colores distintivos y fondos
            ctx.font = 'bold 20px Inter, Arial, sans-serif';
            
            // Cuadrante I
            ctx.fillStyle = 'rgba(255, 107, 107, 0.1)';
            ctx.fillRect(50, -150, 100, 100);
            ctx.fillStyle = '#ff6b6b';
            ctx.fillText('I', 100, -100);
            
            // Cuadrante II
            ctx.fillStyle = 'rgba(78, 205, 196, 0.1)';
            ctx.fillRect(-150, -150, 100, 100);
            ctx.fillStyle = '#4ecdc4';
            ctx.fillText('II', -100, -100);
            
            // Cuadrante III
            ctx.fillStyle = 'rgba(69, 183, 209, 0.1)';
            ctx.fillRect(-150, 50, 100, 100);
            ctx.fillStyle = '#45b7d1';
            ctx.fillText('III', -100, 100);
            
            // Cuadrante IV
            ctx.fillStyle = 'rgba(243, 156, 18, 0.1)';
            ctx.fillRect(50, 50, 100, 100);
            ctx.fillStyle = '#f39c12';
            ctx.fillText('IV', 100, 100);
            
            // Etiquetas de ejes con estilo mejorado
            ctx.font = 'bold 18px Inter, Arial, sans-serif';
            ctx.fillStyle = '#1a1a1a';
            ctx.fillText('X', canvas.width/2 - 30, 30);
            ctx.fillText('Y', 30, -canvas.height/2 + 30);
            
            // Dibujar punto si hay coordenadas
            const x = parseFloat(document.getElementById('x').value);
            const y = parseFloat(document.getElementById('y').value);
            if (!isNaN(x) && !isNaN(y)) {
                ctx.scale(1, -1); // Volver a la escala original para dibujar el punto
                
                // Determinar color del punto según el cuadrante
                let pointColor = '#ff6b6b';
                let quadrantName = '';
                let quadrantDescription = '';
                
                if (x > 0 && y > 0) {
                    pointColor = '#ff6b6b';
                    quadrantName = 'Cuadrante I';
                    quadrantDescription = 'X > 0, Y > 0';
                } else if (x < 0 && y > 0) {
                    pointColor = '#4ecdc4';
                    quadrantName = 'Cuadrante II';
                    quadrantDescription = 'X < 0, Y > 0';
                } else if (x < 0 && y < 0) {
                    pointColor = '#45b7d1';
                    quadrantName = 'Cuadrante III';
                    quadrantDescription = 'X < 0, Y < 0';
                } else if (x > 0 && y < 0) {
                    pointColor = '#f39c12';
                    quadrantName = 'Cuadrante IV';
                    quadrantDescription = 'X > 0, Y < 0';
                } else if (x === 0 && y === 0) {
                    pointColor = '#9b59b6';
                    quadrantName = 'Origen';
                    quadrantDescription = 'Centro del plano';
                } else if (x === 0) {
                    pointColor = '#e74c3c';
                    quadrantName = 'Eje Y';
                    quadrantDescription = 'Sobre el eje vertical';
                } else if (y === 0) {
                    pointColor = '#27ae60';
                    quadrantName = 'Eje X';
                    quadrantDescription = 'Sobre el eje horizontal';
                }
                
                // Calcular posición exacta del punto
                const pointX = x * scale;
                const pointY = y * scale;
                
                // Dibujar área de influencia del punto
                ctx.fillStyle = pointColor + '15';
                ctx.beginPath();
                ctx.arc(pointX, pointY, 30, 0, 2 * Math.PI);
                ctx.fill();
                
                // Dibujar círculo de fondo blanco más grande para mejor visibilidad
                ctx.shadowColor = 'rgba(0, 0, 0, 0.3)';
                ctx.shadowBlur = 8;
                ctx.shadowOffsetX = 2;
                ctx.shadowOffsetY = 2;
                ctx.beginPath();
                ctx.arc(pointX, pointY, 18, 0, 2 * Math.PI);
                ctx.fillStyle = 'white';
                ctx.fill();
                
                // Dibujar borde del punto
                ctx.shadowColor = 'transparent';
                ctx.strokeStyle = pointColor;
                ctx.lineWidth = 5;
                ctx.beginPath();
                ctx.arc(pointX, pointY, 18, 0, 2 * Math.PI);
                ctx.stroke();
                
                // Dibujar punto principal con gradiente
                const pointGradient = ctx.createRadialGradient(pointX, pointY, 0, pointX, pointY, 12);
                pointGradient.addColorStop(0, pointColor);
                pointGradient.addColorStop(0.7, pointColor);
                pointGradient.addColorStop(1, pointColor + '80');
                ctx.fillStyle = pointGradient;
                ctx.beginPath();
                ctx.arc(pointX, pointY, 12, 0, 2 * Math.PI);
                ctx.fill();
                
                // Dibujar punto central más pequeño para precisión
                ctx.fillStyle = 'white';
                ctx.beginPath();
                ctx.arc(pointX, pointY, 4, 0, 2 * Math.PI);
                ctx.fill();
                
                // Dibujar cruz de precisión en el centro exacto
                ctx.strokeStyle = pointColor;
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(pointX - 8, pointY);
                ctx.lineTo(pointX + 8, pointY);
                ctx.moveTo(pointX, pointY - 8);
                ctx.lineTo(pointX, pointY + 8);
                ctx.stroke();
                
                // Dibujar líneas punteadas hasta los ejes con colores
                ctx.setLineDash([12, 6]);
                ctx.strokeStyle = pointColor;
                ctx.lineWidth = 4;
                ctx.globalAlpha = 0.9;
                ctx.beginPath();
                ctx.moveTo(pointX, 0);
                ctx.lineTo(pointX, pointY);
                ctx.moveTo(0, pointY);
                ctx.lineTo(pointX, pointY);
                ctx.stroke();
                ctx.setLineDash([]);
                ctx.globalAlpha = 1;
                
                // Dibujar marcadores en los ejes para mostrar la posición exacta
                ctx.fillStyle = pointColor;
                ctx.strokeStyle = 'white';
                ctx.lineWidth = 2;
                
                // Marcador en eje X
                ctx.beginPath();
                ctx.arc(pointX, 0, 6, 0, 2 * Math.PI);
                ctx.fill();
                ctx.stroke();
                
                // Marcador en eje Y
                ctx.beginPath();
                ctx.arc(0, pointY, 6, 0, 2 * Math.PI);
                ctx.fill();
                ctx.stroke();
                
                // Mostrar información detallada del punto
                ctx.scale(1, -1); // Invertir para escribir texto
                ctx.font = 'bold 18px Inter, Arial, sans-serif';
                ctx.fillStyle = pointColor;
                ctx.strokeStyle = 'white';
                ctx.lineWidth = 4;
                
                // Mostrar coordenadas con mayor precisión
                const coordText = `(${x.toFixed(2)}, ${y.toFixed(2)})`;
                const textX = pointX + 25;
                const textY = -pointY - 25;
                
                // Sombra del texto de coordenadas
                ctx.strokeText(coordText, textX, textY);
                ctx.fillText(coordText, textX, textY);
                
                // Información del cuadrante
                ctx.font = 'bold 14px Inter, Arial, sans-serif';
                ctx.strokeStyle = 'white';
                ctx.lineWidth = 3;
                ctx.strokeText(quadrantName, textX, textY + 25);
                ctx.fillText(quadrantName, textX, textY + 25);
                
                // Descripción del cuadrante
                ctx.font = '12px Inter, Arial, sans-serif';
                ctx.strokeStyle = 'white';
                ctx.lineWidth = 2;
                ctx.strokeText(quadrantDescription, textX, textY + 45);
                ctx.fillText(quadrantDescription, textX, textY + 45);
                
                // Calcular y mostrar distancia desde el origen
                const distance = Math.sqrt(x * x + y * y);
                const distanceText = `Distancia: ${distance.toFixed(2)}`;
                ctx.strokeText(distanceText, textX, textY + 65);
                ctx.fillText(distanceText, textX, textY + 65);
                
                // Dibujar líneas de referencia adicionales más precisas
                ctx.scale(1, -1);
                ctx.strokeStyle = pointColor + '50';
                ctx.lineWidth = 2;
                ctx.setLineDash([5, 5]);
                
                // Línea horizontal de referencia (Y = constante)
                ctx.beginPath();
                ctx.moveTo(-canvas.width/2, pointY);
                ctx.lineTo(canvas.width/2, pointY);
                ctx.stroke();
                
                // Línea vertical de referencia (X = constante)
                ctx.beginPath();
                ctx.moveTo(pointX, -canvas.height/2);
                ctx.lineTo(pointX, canvas.height/2);
                ctx.stroke();
                
                // Dibujar etiquetas de coordenadas en los ejes
                ctx.font = 'bold 12px Inter, Arial, sans-serif';
                ctx.fillStyle = pointColor;
                ctx.strokeStyle = 'white';
                ctx.lineWidth = 2;
                
                // Etiqueta X en el eje horizontal
                const xLabel = `X = ${x}`;
                ctx.strokeText(xLabel, pointX + 10, 15);
                ctx.fillText(xLabel, pointX + 10, 15);
                
                // Etiqueta Y en el eje vertical
                const yLabel = `Y = ${y}`;
                ctx.strokeText(yLabel, 15, -pointY + 10);
                ctx.fillText(yLabel, 15, -pointY + 10);
                
                ctx.setLineDash([]);
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

        // Función para hacer el canvas responsive
        function makeCanvasResponsive() {
            const canvas = document.querySelector('.plane-visualization canvas');
            if (canvas) {
                const container = canvas.parentElement;
                const maxWidth = Math.min(700, container.offsetWidth - 40);
                const scale = maxWidth / 700;
                
                canvas.style.width = maxWidth + 'px';
                canvas.style.height = (700 * scale) + 'px';
            }
        }

        // Aplicar responsive al cargar y redimensionar
        window.addEventListener('load', makeCanvasResponsive);
        window.addEventListener('resize', makeCanvasResponsive);

        // Mejorar la interactividad del canvas
        const canvas = document.querySelector('.plane-visualization canvas');
        if (canvas) {
            canvas.addEventListener('mouseenter', function() {
                this.style.cursor = 'crosshair';
            });
            
            canvas.addEventListener('mouseleave', function() {
                this.style.cursor = 'default';
            });
        }
    </script>
</body>
</html>