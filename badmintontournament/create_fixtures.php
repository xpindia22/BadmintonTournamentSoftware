<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['auto_generate'])) {
    $players = getPlayers($conn);
    $numPlayers = count($players);
    
    if ($numPlayers < 2) {
        echo "Not enough players to generate fixtures.";
    } else {
        for ($i = 0; $i < $numPlayers; $i += 2) {
            if ($i + 1 < $numPlayers) {
                $player1Id = $players[$i]['player_id'];
                $player2Id = $players[$i + 1]['player_id'];
                $query = 'INSERT INTO matches (player1_id, player2_id) VALUES (?, ?)';
                $stmt = $conn->prepare($query);
                if ($stmt === false) {
                    die('Prepare failed: ' . $conn->error);
                }
                $stmt->bind_param('ii', $player1Id, $player2Id);
                if (!$stmt->execute()) {
                    die('Execute failed: ' . $stmt->error);
                }
                $stmt->close();
            }
        }
        echo "Fixtures generated successfully!";
    }
}
?>