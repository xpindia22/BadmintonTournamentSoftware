<?php
require_once 'conn.php';

// Fetch all players
$sql = "SELECT player_id, player_name FROM players";
$result = $conn->query($sql);
$players = [];
while ($row = $result->fetch_assoc()) {
    $players[] = $row;
}

// Shuffle players to randomize the fixtures
shuffle($players);

$num_players = count($players);
$pools = [[], []];
$fixtures = [];

// Function to create matches
function create_matches($players, $round) {
    $fixtures = [];
    $num_players = count($players);
    for ($i = 0; $i < $num_players; $i += 2) {
        if (isset($players[$i + 1])) {
            $fixtures[] = [
                'player1' => $players[$i],
                'player2' => $players[$i + 1],
                'round' => $round
            ];
        }
    }
    return $fixtures;
}

// Create fixtures based on number of players
if ($num_players == 2) {
    $fixtures = create_matches($players, 'Finals');
} elseif ($num_players == 4) {
    $fixtures = create_matches($players, 'Semi-finals');
} elseif ($num_players == 8) {
    $pools[0] = array_slice($players, 0, 4);
    $pools[1] = array_slice($players, 4, 4);
    foreach ($pools as $index => $pool) {
        $pool_fixtures = create_matches($pool, 'Quarter-finals Pool ' . ($index + 1));
        $fixtures = array_merge($fixtures, $pool_fixtures);
    }
} elseif ($num_players > 8) {
    $pools[0] = array_slice($players, 0, ceil($num_players / 2));
    $pools[1] = array_slice($players, ceil($num_players / 2));
    foreach ($pools as $index => $pool) {
        $pool_fixtures = create_matches($pool, 'Pool ' . ($index + 1));
        $fixtures = array_merge($fixtures, $pool_fixtures);
    }
}

// Insert fixtures into the database
$stmt = $conn->prepare("INSERT INTO matches (round, player1_id, player2_id) VALUES (?, ?, ?)");
foreach ($fixtures as $fixture) {
    $stmt->bind_param('sii', $fixture['round'], $fixture['player1']['player_id'], $fixture['player2']['player_id']);
    $stmt->execute();
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fixtures Created</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .container {
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Fixtures Created Successfully</h2>
        <table>
            <thead>
                <tr>
                    <th>Round</th>
                    <th>Player 1</th>
                    <th>Player 2</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fixtures as $fixture): ?>
                    <tr>
                        <td><?= htmlspecialchars($fixture['round']) ?></td>
                        <td><?= htmlspecialchars($fixture['player1']['player_name']) ?></td>
                        <td><?= htmlspecialchars($fixture['player2']['player_name']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
