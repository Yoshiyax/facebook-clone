<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>[ thefacebook-clone ] - Contacto</title>
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

    <div class="container">
        <div class="left-sidebar">
            <div class="login-form-box" style="padding: 10px;">
                <h3>Enlaces Útiles</h3>
                <ul style="list-style-type: none; padding: 0;">
                    <li><a href="home.php" style="color: var(--link-blue);">Página Principal</a></li>
                    <li><a href="registrar.php" style="color: var(--link-blue);">Registro</a></li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="welcome-box">
                <h1>[ Creadores de Thefacebook-Clone ]</h1>
                
                <p>Este proyecto es un clon funcional y simplificado de la versión original de Thefacebook, desarrollado como requisito académico.</p>
                
                <hr style="border-top: 1px solid #ccc; margin: 15px 0;">
                
                <h2>Apartado "Creadores"</h2>

                <div class="member-card" style="display: flex; gap: 20px; border: 1px solid #ccc; padding: 15px; margin-top: 15px;">
                    <div style="flex-shrink: 0;">
                        <img src="avatars/perfil.jpeg" 
                        alt="Avatar del Creador" 
                        width="150" height="150" 
                        style="display: block; border: 1px solid #999; object-fit:cover;">
                    </div>
                    <div>
                        <h3>Josué Hilario Yax García</h3>
                        <p><strong>Rol:</strong> Desarrollador Principal y Estudiante</p>
                        <p><strong>Universidad del Valle de Guatemala-Campus Altiplano UVG</strong></p>
                        <p><strong>Acerca de Mí:</strong></p>
                        <blockquote style="margin: 5px 0 0 10px; padding-left: 10px; border-left: 2px solid var(--header-blue);">
                            Estudiante del cuarto semestre, originario de Totonicapán. DesarrolladorThefacebook-Clone 2004.
                        </blockquote>
                    </div>
                </div>
                
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