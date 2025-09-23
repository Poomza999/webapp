<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'shop';

$conn = new mysqli($hostname, $username, $password, $database) or trigger_error(mysql_error(),E_USER_ERROR);

$conn= mysqli_connect($hostname, $username, $password, $database) or die("Error: " . mysqli_error($conn));

if ($conn->connect_error) {
// die("Connection failed: " . $conn->connect_error);
}

mysqli_query($conn, "SET NAMES 'utf8' ");
?>
