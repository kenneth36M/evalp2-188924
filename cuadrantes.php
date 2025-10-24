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
    <title>Identificación de Cuadrantes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="calculator-container">
        <h2>Identificación de Cuadrantes</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="plane-info">
            <p><strong>Reglas de los cuadrantes:</strong></p>
            <ul>
                <li>Cuadrante I: X > 0, Y > 0</li>
                <li>Cuadrante II: X < 0, Y > 0</li>
                <li>Cuadrante III: X < 0, Y < 0</li>
                <li>Cuadrante IV: X > 0, Y < 0</li>
            </ul>
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
        // Visualización del plano cartesiano
        function drawPlane() {
            const canvas = document.createElement('canvas');
            canvas.width = 400;
            canvas.height = 400;
            const ctx = canvas.getContext('2d');
            const scale = 40; // Escala para las coordenadas (40 píxeles = 1 unidad)
            
            // Configurar el plano
            ctx.translate(canvas.width/2, canvas.height/2);
            ctx.scale(1, -1);

            // Dibujar cuadrícula
            ctx.strokeStyle = '#ddd';
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
            
            // Dibujar ejes principales
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(-canvas.width/2, 0);
            ctx.lineTo(canvas.width/2, 0);
            ctx.moveTo(0, -canvas.height/2);
            ctx.lineTo(0, canvas.height/2);
            ctx.stroke();

            // Dibujar marcas en los ejes
            ctx.scale(1, -1); // Invertir para el texto
            ctx.font = '12px Arial';
            ctx.fillStyle = '#333';
            for(let i = -4; i <= 4; i++) {
                if(i !== 0) {
                    // Marcas en X
                    ctx.fillText(i.toString(), i * scale - 5, 20);
                    // Marcas en Y
                    ctx.fillText(i.toString(), 10, -i * scale + 5);
                }
            }

            // Dibujar etiquetas de los cuadrantes
            ctx.font = '14px Arial';
            ctx.fillStyle = '#666';
            ctx.fillText('I', 80, -80);
            ctx.fillText('II', -80, -80);
            ctx.fillText('III', -80, 80);
            ctx.fillText('IV', 80, 80);
            ctx.fillText('X', canvas.width/2 - 20, 20);
            ctx.fillText('Y', 20, -canvas.height/2 + 20);
            
            // Dibujar punto si hay coordenadas
            const x = parseFloat(document.getElementById('x').value);
            const y = parseFloat(document.getElementById('y').value);
            if (!isNaN(x) && !isNaN(y)) {
                ctx.scale(1, -1); // Volver a la escala original para dibujar el punto
                
                // Dibujar círculo de fondo blanco
                ctx.beginPath();
                ctx.arc(x * scale, y * scale, 6, 0, 2 * Math.PI);
                ctx.fillStyle = 'white';
                ctx.fill();
                
                // Dibujar punto
                ctx.beginPath();
                ctx.arc(x * scale, y * scale, 5, 0, 2 * Math.PI);
                ctx.fillStyle = 'red';
                ctx.fill();
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Dibujar líneas punteadas hasta los ejes
                ctx.setLineDash([5, 3]);
                ctx.strokeStyle = '#666';
                ctx.beginPath();
                ctx.moveTo(x * scale, 0);
                ctx.lineTo(x * scale, y * scale);
                ctx.moveTo(0, y * scale);
                ctx.lineTo(x * scale, y * scale);
                ctx.stroke();
                ctx.setLineDash([]);
                
                // Mostrar coordenadas junto al punto
                ctx.scale(1, -1); // Invertir para escribir texto
                ctx.font = '12px Arial';
                ctx.fillStyle = '#000';
                ctx.fillText(`(${x}, ${y})`, x * scale + 10, -y * scale - 10);
            }
            
            // Agregar el canvas al DOM
            const container = document.getElementById('planeVisualization');
            container.innerHTML = '';
            container.appendChild(canvas);
        }

        // Dibujar cuando se envía el formulario y cuando se cambian los valores
        if (document.querySelector('.result')) {
            drawPlane();
            document.getElementById('x').addEventListener('input', drawPlane);
            document.getElementById('y').addEventListener('input', drawPlane);
        }
    </script>
</body>
</html>