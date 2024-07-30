<?php
require_once 'config.php';

// Fetch all players
$query = 'SELECT player_id FROM players';
$result = $conn->query($query);
if (!$result) {
    die('Query failed: ' . $conn->error);
}

$players = $result->fetch_all(MYSQLI_ASSOC);

// Assign players to pools 'A' and 'B'
foreach ($players as $index => $player) {
    $pool = ($index % 2 == 0) ? 'A' : 'B'; // Alternating assignment to pools 'A' and 'B'
    $query = 'UPDATE players SET pool = ? WHERE player_id = ?';
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param('si', $pool, $player['player_id']);
    $stmt->execute();
}

echo "Players assigned to pools successfully!";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Players to Pools</title>
</head>
<body>
    <header>
        <h1>Assign Players to Pools</h1>
    </header>

    <form method="POST" action="">
        <button type="submit">Assign Players</button>
    </form>
</body>
</html>
