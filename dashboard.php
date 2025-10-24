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
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <div class="menu-options">
            <a href="calculos.php" class="menu-btn">Calcular Áreas y Volúmenes</a>
            <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>