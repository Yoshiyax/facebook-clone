<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>[ thefacebook-clone ] - about</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <header class="header">
        <div class="header-content">
            <span class="logo-text">[ thefacebook-clone ]</span>
            <div class="top-nav">
                <?php if (isset($_SESSION["avatar"])): ?>
                    <img src="<?php echo htmlspecialchars($_SESSION["avatar"]); ?>" alt="Avatar" width="20" height="20" style="vertical-align: middle; border: 1px solid white;">
                <?php endif; ?>
                <a href="home.php">Inicio</a>
                <a href="contacto.php">contacto</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </header>

            <div class="main-content">
            <div class="welcome-box">
                <h1>[ Bienvenido a Thefacebook-clone ]</h1>
                <p>Thefacebook es un directorio en línea que conecta personas a través de redes sociales en universidades.</p>
                <p>Hemos abierto Thefacebook para el consumo popular en **Harvard University**.</p>
                <p>Puedes usar Thefacebook para:</p>
                <ul>
                    <li>Buscar personas en tu escuela</li>
                    <li>Averiguar quién está en tus clases</li>
                    <li>Buscar a los amigos de tus amigos</li>
                    <li>Ver una visualización de tu red social</li>
                </ul>
                <p>Para empezar, haz clic abajo para registrarte. Si ya te has registrado, puedes iniciar sesión.</p>
                
                <a href="registrar.php" class="btn-primary" style="padding: 5px 10px; display: inline-block;">registrar</a>
                <a href="index.php" class="btn-secondary" style="padding: 5px 10px; display: inline-block;">Iniciar sesión</a>
                
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <a href="about.php">Acerca de...</a>
        <a href="contacto.php">contacto</a>
        <p>a JoshYax production</p>
        <p>Thefacebook © 2004</p>
    </footer>
</body>
</html>