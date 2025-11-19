<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
//conexión a la BD
require_once "data_base.php";
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
                <h1>[ Mi Perfil ]</h1>
                <img src="<?php echo htmlspecialchars($_SESSION["avatar"]); ?>" alt="Mi Foto" width="100%" style="display: block; margin-bottom: 10px; border: 1px solid #ccc;">
                <p><strong><?php echo htmlspecialchars($_SESSION["name"]); ?></strong></p>
                <h3>Acerca de Mí</h3>
                <p><strong>Correo:</strong> <?php echo htmlspecialchars($_SESSION["email"]); ?></p>
                <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($_SESSION["bio"])); ?></p>
                <p><a href="editar_perfil.php">Editar Perfil</a></p>
                <hr style="border-top: 1px solid #ccc;">
                <p>Miembro desde: <?php echo date("Y", strtotime($_SESSION["reg_date"])); ?></p>
            </div>
        </div>

        <div class="main-content">
            <div class="welcome-box">
                <div class="post-form-container" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc;">
                <h1>[ Mi Perfil y Muro ]</h1>
                <h3>¿Qué estás pensando?</h3>
                <form action="post.php" method="post">
                <textarea name="post_content" rows="3" placeholder="Escribe tu estado..." style="width: 100%; resize: vertical; margin-bottom: 10px;"></textarea>
                <input type="submit" value="Publicar" style="background-color: #4CAF50; color: white; padding: 8px 15px; border: none; cursor: pointer;">
                </form>
                <?php
                //logica para mostrar los post
                //obtención de los post
                $sql_posts = "SELECT p.contenido, p.fecha_publicacion, u.nombre, u.avatar_url 
                    FROM posts p
                    JOIN users u ON p.id_user = u.id_user
                    ORDER BY p.fecha_publicacion DESC";

                if ($result = $link->query($sql_posts)) {
                echo "<h3>Muro de Noticias</h3>";
    
                while ($row = $result->fetch_assoc()) {
                echo '<div class="post-card" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">';
        
                // Información del Autor
                echo '<div style="display: flex; align-items: center; margin-bottom: 10px;">';
                echo '<img src="' . htmlspecialchars($row['avatar_url']) . '" alt="Avatar" width="40" height="40" style="object-fit: cover; border-radius: 50%; margin-right: 10px;">';
                echo '<strong>' . htmlspecialchars($row['nombre']) . '</strong>';
                echo '</div>';
        
                // Contenido y Fecha
                echo '<p>' . nl2br(htmlspecialchars($row['contenido'])) . '</p>'; // nl2br para mostrar saltos de línea
                echo '<small style="color: #666;">Publicado el ' . date("d M Y H:i", strtotime($row['fecha_publicacion'])) . '</small>';
        
                echo '</div>'; // Cierre post-card
            }
        $result->free();
    } else {
    echo "<p>Aún no hay publicaciones.</p>";
    }

    ?>
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