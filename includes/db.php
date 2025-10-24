<?php
$host = 'localhost';
$dbname = 'login_db';
$username = 'root';
$password = '';

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>