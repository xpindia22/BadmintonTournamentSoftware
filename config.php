<?php
$host = 'localhost';
$user = 'root';
$pwd = '';
$db = 'badminton';

$conn = new mysqli($host, $user, $pwd, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
