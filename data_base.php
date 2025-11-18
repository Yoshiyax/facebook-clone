<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('DB_SERVER', 'sql103.infinityfree.com');
define('DB_USERNAME', 'ifo_40443882');
define('DB_PASSWORD', 'NfaTkyzm6yE5p');
define('DB_NAME', 'ifo_40443882_thefacebook_clone');

$link = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if($link === false){
    die("ERROR: No se pudo conectar. " . $link->connect_error);
}


function sanitize_input($data, $link) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($link, $data);
}
?>