<?php

session_start();

require_once 'data_base.php';

$email_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = sanitize_input($_POST["email"], $link);
    $password = $_POST["password"];
    $name = sanitize_input($_POST["name"], $link);
    $bio = "¡Hola! Nuevo miembro de TheFacebook.";

    // --- 1. VALIDACIÓN EN SERVIDOR
    $allowed_domains = ['uvg.edu.gt', 'url.edu.gt', 'umg.edu.gt', 'ufm.edu.gt'];
    $personal_domains = ['gmail.com', 'outlook.com', 'hotmail.com', 'yahoo.com', 'aol.com', 'icloud.com'];
    
    $domain = substr(strrchr($email, "@"), 1);

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Formato de correo inválido.";
    } elseif (in_array($domain, $personal_domains)) {
        $email_err = "No se aceptan correos personales.";
    } elseif (!in_array($domain, $allowed_domains)) {
        $email_err = "El dominio " . $domain . " no pertenece a una universidad permitida.";
    } 
    
    if (empty($email_err)) {
        
        // A. VERIFICAR SI YA EXISTE
        $sql = "SELECT id_user FROM users WHERE email = ?";
        if ($stmt = $link->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;
            if ($stmt->execute()) {
                $stmt->store_result();
                
                if ($stmt->num_rows == 1) {
                    $email_err = "Este correo ya está registrado.";
                } else {
                    
                    // --- B. LÓGICA DE SUBIDA DE ARCHIVO PHP LOCAL ---
                    $target_dir = "avatars/";
                    $uploadOk = 1;
                    $avatar_url_local = "avatars/default.png";
                    
                    // Asegurarse de que el archivo fue subido
                    if (isset($_FILES["avatarFile"]) && $_FILES["avatarFile"]["error"] === UPLOAD_ERR_OK) {
                        
                        $file_extension = pathinfo($_FILES["avatarFile"]["name"], PATHINFO_EXTENSION);
                        $file_name = uniqid('avatar-', true) . '.' . $file_extension;
                        $target_file = $target_dir . $file_name;

                        // Validación básica de tipo (simplificada)
                        $check = getimagesize($_FILES["avatarFile"]["tmp_name"]);
                        if ($check === false) {
                            $email_err = "El archivo no es una imagen válida.";
                            $uploadOk = 0;
                        }
                        
                        // Validación de tamaño (ej: máximo 5MB)
                        if ($_FILES["avatarFile"]["size"] > 5000000) {
                            $email_err = "El avatar es demasiado grande (Máx 5MB).";
                            $uploadOk = 0;
                        }
                        
                        // Intentar mover el archivo solo si todas las validaciones pasaron
                        if ($uploadOk == 1) {
                            if (move_uploaded_file($_FILES["avatarFile"]["tmp_name"], $target_file)) {
                                $avatar_url_local = $target_file; // Éxito: Guardamos la RUTA RELATIVA
                            } else {
                                // Fallo de subida (generalmente permisos). Usamos el default.
                                $email_err = "Error al mover el archivo al servidor. Se usará un avatar por defecto.";
                                // $avatar_url_local ya es 'avatars/default.png'
                            }
                        }
                        
                    } else {
                         $email_err = "Debes seleccionar un archivo para el avatar.";
                         $uploadOk = 0;
                    }

                    // C. INSERCIÓN PASADA LA VERIFICACIÓN
                    if ($uploadOk == 1 || $avatar_url_local != "avatars/default.png") {
                        
                        $sql_insert = "INSERT INTO users (email, contraseña, nombre, biografia, avatar_url, fecha_registro) 
                                       VALUES (?, ?, ?, ?, ?, NOW())";
                        
                        if ($stmt_insert = $link->prepare($sql_insert)) {
                            
                            // Parámetros: 5 's' por: email, contraseña, nombre, biografia, avatar_url
                            $stmt_insert->bind_param("sssss", $param_email_insert, $param_password, $param_name, $param_bio, $param_avatar);

                            $param_email_insert = $email;
                            $param_password = $password;
                            $param_name = $name;
                            $param_bio = $bio;
                            $param_avatar = $avatar_url_local;
                            
                            if ($stmt_insert->execute()) {
                                header("location: index.php?registered=true");
                                exit;
                            } else {
                                echo "ERROR al registrar: " . $stmt_insert->error;
                            }
                            $stmt_insert->close();
                        }
                    }
                }
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
    <title>[ thefacebook-clone ] - Register</title>
    <link rel="stylesheet" href="style.css">
    <script src="validacion.js"></script>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <span class="logo-text">[ thefacebook-clone ]</span>
            <div class="top-nav">
                <a href="index.php">Iniciar Sesion</a>
                <a href="registrar.php">registrar</a>
                <a href="about.php">Acerca de...</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="left-sidebar">
            <div class="login-form-box">
                <h2>Crear Cuenta</h2>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" 
                    onsubmit="return validateRegistrationForm()" 
                    enctype="multipart/form-data">
    
                <label>Nombre Completo:</label>
                <input type="text" name="name" required>
                <label>Correo Universitario:</label>
                <input type="email" name="email" id="email" required>
                <label>Contraseña:</label>
                <input type="password" name="password" required>
                <label>Foto de Perfil (Avatar):</label>
                <input type="file" name="avatarFile" required accept="image/*">
                    <div id="email-error" class="error-message">
                    <?php echo $email_err; ?>
                    </div>
                <button type="submit" class="btn-primary" style="margin-top: 15px;">Registrarme</button>
                </form>
                
                <p style="margin-top: 15px;">¿Ya tienes cuenta? <a href="index.php" style="color: var(--link-blue);">Iniciar Sesión</a></p>
            </div>
        </div>

        <div class="main-content">
            <div class="welcome-box">
                <h1>[ Bienvenido al Registro ]</h1>
                
                <p>Para asegurar que Thefacebook sea exclusivo de universidades, solo aceptamos correos institucionales.</p>
                <p>Por favor, utiliza tu dirección de correo electrónico de la Universidad (ej: `@uvg.edu.gt`) para crear tu cuenta.</p>
                
                <p>El proceso es simple y te permitirá conectarte con otros estudiantes y compañeros.</p>
                
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