<?php
$host = "sql203.infinityfree.com";
$user = "if0_41458060";
$password = "Ped36JsZVBGhUrs";
$database = "if0_41458060_webdata";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
