<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>[ thefacebook-clone ] - Home</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <header class="header">
        <div class="header-content">
            <span class="logo-text">[ thefacebook-clone ]</span>
            <div class="top-nav">
                <img src="<?php echo htmlspecialchars($_SESSION["avatar"]); ?>" alt="Avatar" width="20" height="20" style="vertical-align: middle; border: 1px solid white;">
                <a href="home.php">Inicio</a>
                <a href="contacto.php">contacto</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="left-sidebar">
            <div class="login-form-box" style="padding: 10px;">
                <img src="<?php echo htmlspecialchars($_SESSION["avatar"]); ?>" alt="Mi Foto" width="100%" style="display: block; margin-bottom: 10px; border: 1px solid #ccc;">
                <p><strong><?php echo htmlspecialchars($_SESSION["name"]); ?></strong></p>
                <p>Miembro desde: <?php echo date("Y", strtotime($_SESSION["reg_date"])); ?></p>
            </div>
        </div>

        <div class="main-content">
            <div class="welcome-box">
                <h1>[ Mi Perfil y Muro ]</h1>
                
                <h3>Acerca de Mí</h3>
                <p><strong>Correo:</strong> <?php echo htmlspecialchars($_SESSION["email"]); ?></p>
                <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($_SESSION["bio"])); ?></p>
                
                <hr style="border-top: 1px solid #ccc;">
                
                <h3>Novedades</h3>
                <p>Noticias</p>
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