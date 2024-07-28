<?php
require_once 'conn.php';

function createFixtures($players) {
    $total_players = count($players);
    shuffle($players);

    $rounds = [];

    if ($total_players > 8) {
        $initial_round_matches = array_chunk($players, 2);
        $rounds['Initial Round'] = $initial_round_matches;
        $remaining_players = $total_players - (count($initial_round_matches) * 2);
        if ($remaining_players < 8) {
            $players = array_merge(...$initial_round_matches);
        } else {
            $players = $remaining_players;
        }
    }

    if ($total_players <= 2) {
        $round = "Final";
        $rounds[$round] = [[$players[0], $players[1]]];
    } elseif ($total_players <= 4) {
        $round = "Semi-final";
        $rounds[$round] = array_chunk($players, 2);
    } else {
        $round = "Quarter-final";
        $rounds[$round] = array_chunk($players, 2);
    }

    return $rounds;
}

$players_result = $conn->query("SELECT player_id FROM players");
$players = [];
while ($row = $players_result->fetch_assoc()) {
    $players[] = $row['player_id'];
}

$fixtures = createFixtures($players);

foreach ($fixtures as $round => $matches) {
    foreach ($matches as $match) {
        $stmt = $conn->prepare("INSERT INTO matches (round, player1_id, player2_id, match_date) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sii", $round, $match[0], $match[1]);
        $stmt->execute();
    }
}

echo "Fixtures created successfully!";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Fixtures</title>
</head>
<body>
    <h2>Create Fixtures</h2>
    <form method="post" action="">
        <input type="submit" value="Create Fixtures">
    </form>
</body>
</html>
