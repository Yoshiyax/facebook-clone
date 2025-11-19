<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SERVER["REQUEST_METHOD"] != "POST"){
    header("location: home.php");
    exit;
}

require_once "data_base.php";

$contenido = trim($_POST["post_content"] ?? '');


if (empty($contenido)) {
    header("location: home.php?post_error=empty");
    exit;
}

// Consulta insert para postear
$sql = "INSERT INTO posts (id_user, contenido) VALUES (?, ?)";

if ($stmt = $link->prepare($sql)) {
    
    $param_id_user = $_SESSION["id"];
    $param_contenido = sanitize_input($contenido, $link);
    
    $stmt->bind_param("is", $param_id_user, $param_contenido);
    
    if ($stmt->execute()) {
        header("location: home.php");
        exit;
    } else {
        header("location: home.php?post_error=db_fail");
        exit;
    }
    
    $stmt->close();
}else {
    // Si la preparación de la consulta falla (Error de sintaxis SQL)
    header("location: index.php?post_error=sql_syntax"); 
    exit;
}

$link->close();
?>