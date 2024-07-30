<?php
require_once 'config.php';

function createFixtures($conn, $pool) {
    $query = 'SELECT player_id FROM players WHERE pool = ?';
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param('s', $pool);
    $stmt->execute();
    $result = $stmt->get_result();
    $players = $result->fetch_all(MYSQLI_ASSOC);
    
    if (count($players) % 2 != 0) {
        // Give a bye to the last player if the number of players is odd
        array_push($players, ['player_id' => NULL]);
    }
    
    for ($i = 0; $i < count($players); $i += 2) {
        if (isset($players[$i + 1])) {
            $query = 'INSERT INTO matches (round, player1_id, player2_id, pool) VALUES ("Qualifying", ?, ?, ?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iis', $players[$i]['player_id'], $players[$i + 1]['player_id'], $pool);
            $stmt->execute();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    createFixtures($conn, 'A');
    createFixtures($conn, 'B');
    echo "Fixtures created for both pools!";
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
        <button type="submit">Create Fixtures</button>
    </form>
</body>
</html>
