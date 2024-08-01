<?php
require_once 'config.php';

// Assign players to pools and create initial fixtures
function assignPlayersToPools($conn) {
    $pools = ['A', 'B'];
    $players = [];

    // Fetch players from the database
    $query = 'SELECT player_id FROM players';
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $players[] = $row['player_id'];
    }

    // Shuffle and split players into pools
    shuffle($players);
    $poolA = array_slice($players, 0, 8);
    $poolB = array_slice($players, 8, 8);

    // Insert fixtures for Pre-Quarter-finals
    $round = 'Pre-Quarter-finals';

    foreach ([$poolA, $poolB] as $pool) {
        for ($i = 0; $i < count($pool); $i += 2) {
            if (isset($pool[$i + 1])) {
                $query = 'INSERT INTO matches (round, player1_id, player2_id) VALUES (?, ?, ?)';
                $stmt = $conn->prepare($query);
                $stmt->bind_param('sii', $round, $pool[$i], $pool[$i + 1]);
                $stmt->execute();
            }
        }
    }

    echo "Players assigned to pools and fixtures created successfully!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    assignPlayersToPools($conn);
}
?>
