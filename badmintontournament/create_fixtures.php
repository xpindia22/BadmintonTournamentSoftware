<?php
require_once 'config.php';

function createInitialFixtures($conn) {
    $query = 'SELECT player_id FROM players';
    $result = $conn->query($query);
    $players = $result->fetch_all(MYSQLI_ASSOC);

    if (count($players) < 16) {
        echo "Not enough players to create fixtures.";
        return;
    }

    shuffle($players);

    // Create fixtures for the initial round (16 players)
    for ($i = 0; $i < 16; $i += 2) {
        $query = 'INSERT INTO matches (round, player1_id, player2_id) VALUES ("Initial", ?, ?)';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $players[$i]['player_id'], $players[$i + 1]['player_id']);
        $stmt->execute();
    }

    echo "Fixtures created successfully!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    createInitialFixtures($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Fixtures</title>
</head>
<body>
    <header>
        <h1>Create Fixtures</h1>
    </header>

    <form method="POST" action="">
        <button type="submit">Create Initial Fixtures</button>
    </form>
</body>
</html>
