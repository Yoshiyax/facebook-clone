<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('DB_SERVER', 'sql103.infinityfree.com');
define('DB_USERNAME', 'if0_40443882');
define('DB_PASSWORD', 'NfaTkyzmGyE5p');
define('DB_NAME', 'if0_40443882_thefacebook_clone');

$link = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if($link === false){
    die("ERROR: No se pudo conectar. " . $link->connect_error);
}

$link->set_charset("utf8");

function sanitize_input($data, $link) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($link, $data);
}
?>