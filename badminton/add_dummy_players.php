<?php
require_once 'conn.php';

$dummy_players = ['Player 1', 'Player 2', 'Player 3', 'Player 4', 'Player 5', 'Player 6', 'Player 7', 'Player 8', 'Player 9', 'Player 10', 'Player 11', 'Player 12', 'Player 13', 'Player 14'];

foreach ($dummy_players as $player_name) {
    $stmt = $conn->prepare("INSERT INTO players (player_name) VALUES (?)");
    $stmt->bind_param('s', $player_name);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

echo "Dummy players added successfully.";
?>
