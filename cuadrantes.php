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
            canvas.width = 300;
            canvas.height = 300;
            const ctx = canvas.getContext('2d');
            
            // Configurar el plano
            ctx.translate(150, 150);
            ctx.scale(1, -1);
            
            // Dibujar ejes
            ctx.beginPath();
            ctx.moveTo(-150, 0);
            ctx.lineTo(150, 0);
            ctx.moveTo(0, -150);
            ctx.lineTo(0, 150);
            ctx.strokeStyle = '#000';
            ctx.stroke();
            
            // Dibujar etiquetas de los cuadrantes
            ctx.scale(1, -1); // Revertir la escala para el texto
            ctx.font = '12px Arial';
            ctx.fillStyle = '#666';
            ctx.fillText('I', 60, -60);
            ctx.fillText('II', -60, -60);
            ctx.fillText('III', -60, 60);
            ctx.fillText('IV', 60, 60);
            ctx.fillText('X', 140, -5);
            ctx.fillText('Y', 5, -140);
            ctx.scale(1, -1); // Volver a la escala original
            
            // Dibujar punto si hay coordenadas
            const x = document.getElementById('x').value;
            const y = document.getElementById('y').value;
            if (x && y) {
                ctx.beginPath();
                ctx.arc(x * 30, y * 30, 5, 0, 2 * Math.PI);
                ctx.fillStyle = 'red';
                ctx.fill();
            }
            
            // Agregar el canvas al DOM
            const container = document.getElementById('planeVisualization');
            container.innerHTML = '';
            container.appendChild(canvas);
        }

        // Dibujar cuando se envía el formulario
        if (document.querySelector('.result')) {
            drawPlane();
        }
    </script>
</body>
</html>