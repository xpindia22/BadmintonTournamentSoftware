<?php
require_once 'config.php';

$sample_players = ['Alice', 'Bob', 'Charlie', 'David', 'Eve', 'Frank', 'Grace', 'Hannah'];

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

foreach ($sample_players as $player) {
    $stmt = $conn->prepare("INSERT INTO players (player_name) VALUES (?)");
    $stmt->bind_param("s", $player);
    $stmt->execute();
}

echo "Sample data inserted successfully.";
$conn->close();
?>
