<?php

session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: home.php");
    exit;
}

require_once 'data_base.php';

$email = $password = "";
$login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = sanitize_input($_POST["email"], $link);
    $password = $_POST["password"]; 
    
    if (empty($email) || empty($password)) {
        $login_err = "Por favor ingrese correo y contraseña.";
    } else {
        $sql = "SELECT id_user, nombre, email, contraseña, avatar_url, biografia, fecha_registro FROM users WHERE email = ?";
        
        if ($stmt = $link->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;
            
            if ($stmt->execute()) {
                $stmt->store_result();
                
                if ($stmt->num_rows == 1) {
                    // Vincular el resultado a variables (¡EN ORDEN!)
                    $stmt->bind_result($id, $name, $db_email, $db_password, $avatar_url, $bio, $reg_date);
                    if ($stmt->fetch()) {
                        
                        // Comparación de contraseña en texto plano (columna: contraseña)
                        if ($password === $db_password) { 
                            
                            // Iniciar sesión y guardar datos (usando nombres amigables en la sesión)
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id; 
                            $_SESSION["name"] = $name;
                            $_SESSION["email"] = $db_email;
                            $_SESSION["avatar"] = $avatar_url; // Foto para el navbar
                            $_SESSION["bio"] = $bio;
                            $_SESSION["reg_date"] = $reg_date;
                            
                            header("location: home.php");
                            exit;
                        } else {
                            $login_err = "Contraseña incorrecta.";
                        }
                    }
                } else {
                    $login_err = "No se encontró cuenta con ese correo.";
                }
            } else {
                echo "¡Ups! Algo salió mal. Inténtelo de nuevo más tarde.";
            }

            $stmt->close();
        }
    }
    $link->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>[ thefacebook-clone ]</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <span class="logo-text">[ thefacebook-clone ]</span>
            <div class="top-nav">
                <a href="index.php">Inicio</a>
                <a href="registrar.php">registrar</a>
                <a href="about.php">Acerca de...</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="left-sidebar">
            
            <div class="login-form-box">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    
                    <label for="email_login">Email:</label>
                    <input type="text" name="email" id="email_login" required>
                    
                    <label for="password_login">Password:</label>
                    <input type="password" name="password" id="password_login" required>
                    
                    <?php if (!empty($login_err)): ?>
                        <div class="error-message" style="color: red; margin-top: 5px;"><?php echo $login_err; ?></div>
                    <?php endif; ?>

                    <button type="submit" class="btn-primary">login</button>
                    <a href="registrar.php" class="btn-secondary" style="text-decoration: none;">registrar</a>
                </form>
            </div>
        </div>

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