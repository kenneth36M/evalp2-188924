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
    if (empty($_POST['figura']) || 
        (($_POST['figura'] == 'cilindro' || $_POST['figura'] == 'rectangulo') && 
         (empty($_POST['altura']) || empty($_POST['base'])))) {
        $error = 'Todos los campos son requeridos';
    } else {
        $figura = $_POST['figura'];
        
        if ($figura == 'cilindro') {
            $radio = floatval($_POST['base']);
            $altura = floatval($_POST['altura']);
            
            if ($radio <= 0 || $altura <= 0) {
                $error = 'Los valores deben ser mayores que 0';
            } else {
                $area = 2 * pi() * $radio * ($radio + $altura);
                $volumen = pi() * $radio * $radio * $altura;
                $result = array(
                    'area' => number_format($area, 2),
                    'volumen' => number_format($volumen, 2),
                    'perimetro' => 'No aplica'
                );
            }
        } else if ($figura == 'rectangulo') {
            $base = floatval($_POST['base']);
            $altura = floatval($_POST['altura']);
            
            if ($base <= 0 || $altura <= 0) {
                $error = 'Los valores deben ser mayores que 0';
            } else {
                $area = $base * $altura;
                $perimetro = 2 * ($base + $altura);
                $result = array(
                    'area' => number_format($area, 2),
                    'perimetro' => number_format($perimetro, 2),
                    'volumen' => 'No aplica'
                );
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
    <title>Cálculo de Áreas y Volúmenes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="calculator-container">
        <h2>Cálculo de Áreas y Volúmenes</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="figura">Seleccione la figura:</label>
                <select name="figura" id="figura" required>
                    <option value="">Seleccione una figura</option>
                    <option value="cilindro">Cilindro</option>
                    <option value="rectangulo">Rectángulo</option>
                </select>
            </div>

            <div class="form-group">
                <label for="base" id="labelBase">Base/Radio:</label>
                <input type="number" step="0.01" id="base" name="base" placeholder="Ingrese el valor">
            </div>

            <div class="form-group">
                <label for="altura">Altura:</label>
                <input type="number" step="0.01" id="altura" name="altura" placeholder="Ingrese la altura">
            </div>

            <button type="submit">Calcular</button>
        </form>

        <?php if ($result): ?>
        <div class="result">
            <h3>Resultados:</h3>
            <p>Área: <?php echo $result['area']; ?> unidades cuadradas</p>
            <?php if ($result['perimetro'] !== 'No aplica'): ?>
            <p>Perímetro: <?php echo $result['perimetro']; ?> unidades</p>
            <?php endif; ?>
            <?php if ($result['volumen'] !== 'No aplica'): ?>
            <p>Volumen: <?php echo $result['volumen']; ?> unidades cúbicas</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <a href="dashboard.php" class="back-btn">Volver al Dashboard</a>
        <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
    </div>

    <script>
        document.getElementById('figura').addEventListener('change', function() {
            const labelBase = document.getElementById('labelBase');
            if (this.value === 'cilindro') {
                labelBase.textContent = 'Radio:';
            } else {
                labelBase.textContent = 'Base:';
            }
        });
    </script>
</body>
</html>