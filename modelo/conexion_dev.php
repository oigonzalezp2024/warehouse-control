<?php
function conexion(){
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'empresa';
    $conn = mysqli_connect($host, $user, $password, $database);
    return $conn;
}
$pdo = new PDO('mysql:host=localhost;dbname=empresa', 'root', '');
