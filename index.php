<?php
session_start();
require_once 'includes/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (empty($username) || empty($password)) {
        $error = "Ambos campos son requeridos";
    } else {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            // Debugging
            error_log("Usuario encontrado: " . $row['username']);
            error_log("Hash almacenado: " . $row['password']);
            error_log("Contraseña ingresada: " . $password);
            
            $verify = password_verify($password, $row['password']);
            error_log("Resultado de verificación: " . ($verify ? "true" : "false"));
            
            if ($verify) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['last_login'] = date('Y-m-d H:i:s');
                header("Location: dashboard.php");
                exit();
            }
        }
        
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Cálculos Matemáticos</title>
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

    <div class="login-container">
        <div class="login-header">
            <div class="login-icon">🧮</div>
            <h2>Iniciar Sesión</h2>
            <p class="login-subtitle">Accede a tu cuenta para continuar</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="error">
                <span class="error-icon">⚠️</span>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="login-form">
            <div class="form-group">
                <label for="username">
                    <span class="label-icon">👤</span>
                    Usuario
                </label>
                <input type="text" id="username" name="username" required placeholder="Ingresa tu usuario">
            </div>
            
            <div class="form-group">
                <label for="password">
                    <span class="label-icon">🔒</span>
                    Contraseña
                </label>
                <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">
            </div>
            
            <button type="submit" class="login-btn">
                <span class="btn-text">Iniciar Sesión</span>
                <span class="btn-icon">→</span>
            </button>
        </form>
        
        <div class="form-footer">
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
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

        // Animación de entrada para los campos
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
    </script>
</body>
</html>