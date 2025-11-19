<?php
session_start();

// verificar existencia del usuario
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

require_once "data_base.php";

$name = $_SESSION["name"];
$bio = $_SESSION["bio"];
$avatar_url = $_SESSION["avatar"];
$name_err = $bio_err = $update_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(empty(trim($_POST["name"]))){
        $name_err = "Por favor ingresa un nombre.";
    } else {
        $name = sanitize_input($_POST["name"], $link);
    }
    
    $bio = sanitize_input($_POST["bio"], $link);
    
    $uploadOk = 1;
    $new_avatar_url = $_SESSION["avatar"]; 

    if (empty($name_err) && isset($_FILES["avatarFile"]) && $_FILES["avatarFile"]["error"] !== UPLOAD_ERR_NO_FILE) {
        
        $target_dir = "avatars/";
        $file_extension = strtolower(pathinfo($_FILES["avatarFile"]["name"], PATHINFO_EXTENSION));
        $file_name = uniqid('avatar-', true) . '.' . $file_extension;
        $target_file = $target_dir . $file_name;
        
        $check = getimagesize($_FILES["avatarFile"]["tmp_name"]);
        if($check === false) {
            $name_err = "El archivo seleccionado no es una imagen válida.";
            $uploadOk = 0;
        }
        
        if ($_FILES["avatarFile"]["size"] > 5000000) {
            $name_err = "El archivo es demasiado grande (Máx 5MB).";
            $uploadOk = 0;
        }

        if($file_extension != "jpg" && $file_extension != "png" && $file_extension != "jpeg") {
            $name_err = "Solo se permiten JPG, JPEG y PNG.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["avatarFile"]["tmp_name"], $target_file)) {
                $new_avatar_url = $target_file;
                $update_msg .= "Avatar actualizado correctamente. ";
            } else {
                $name_err = "Hubo un error al mover el archivo. Intenta de nuevo.";
                $uploadOk = 0;
            }
        }
    }
    
    if (empty($name_err) && $uploadOk == 1) {
        
        $sql_update = "UPDATE users SET nombre = ?, biografia = ?, avatar_url = ? WHERE id_user = ?";
        
        if ($stmt = $link->prepare($sql_update)) {
            
            $stmt->bind_param("sssi", $param_name, $param_bio, $param_avatar, $param_id);
            
            $param_name = $name;
            $param_bio = $bio;
            $param_avatar = $new_avatar_url;
            $param_id = $_SESSION["id"];
            
            if ($stmt->execute()) {
                $_SESSION["name"] = $name;
                $_SESSION["bio"] = $bio;
                $_SESSION["avatar"] = $new_avatar_url;
                
                $update_msg .= "¡Perfil actualizado exitosamente!";

                header("location: home.php?update=success");
                exit; 
            } else {
                $update_msg = "ERROR: No se pudo actualizar el perfil. Por favor, inténtalo de nuevo.";
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
    <title>Editar Perfil - TheFacebook Clone</title>
    <link rel="stylesheet" href="style.css"> <style>
        .wrapper { width: 350px; padding: 20px; margin: 50px auto; border: 1px solid #ccc; background-color: #f7f7f7; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input[type="text"], .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .form-group textarea { resize: vertical; height: 80px; }
        .btn-submit { background-color: #3b5998; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        .alert-success { color: green; font-weight: bold; }
        .alert-danger { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Editar Perfil</h2>
        <p>Actualiza tu nombre, biografía y foto de perfil.</p>

        <?php 
        if(!empty($update_msg)){
            $class = (strpos($update_msg, 'ERROR') !== false) ? 'alert-danger' : 'alert-success';
            echo '<div class="' . $class . '">' . $update_msg . '</div>';
        }
        if(!empty($name_err)){
            echo '<div class="alert-danger">' . $name_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
            </div>

            <div class="form-group">
                <label>Biografía</label>
                <textarea name="bio"><?php echo htmlspecialchars($bio); ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Avatar Actual</label>
                <img src="<?php echo htmlspecialchars($avatar_url); ?>" alt="Avatar Actual" width="100" height="100" style="object-fit: cover; border: 1px solid #ccc;">
            </div>

            <div class="form-group">
                <label>Cambiar Avatar (Opcional)</label>
                <input type="file" name="avatarFile" accept="image/jpeg,image/png">
                <small>Max 5MB. Formatos permitidos: JPG, PNG.</small>
            </div>

            <div class="form-group">
                <input type="submit" class="btn-submit" value="Guardar Cambios">
            </div>
        </form>
    </div>
</body>
</html>