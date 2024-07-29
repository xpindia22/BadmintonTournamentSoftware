<?php
require_once 'config.php';

function createFixtures($players) {
    $total_players = count($players);
    shuffle($players);

    $rounds = [];
    
    if ($total_players > 8) {
        $preliminary_matches = [];
        while (count($players) > 8) {
            $preliminary_matches[] = [array_pop($players), array_pop($players)];
        }
        $rounds['Preliminary Round'] = $preliminary_matches;
    }

    if ($total_players >= 8) {
        $rounds['Quarter-finals'] = array_chunk($players, 2);
    }

    if ($total_players >= 4) {
        $semi_final_players = [];
        foreach ($rounds['Quarter-finals'] as $match) {
            $semi_final_players = array_merge($semi_final_players, $match);
        }
        $rounds['Semi-finals'] = array_chunk($semi_final_players, 2);
    }

    if ($total_players >= 2) {
        $final_players = [];
        foreach ($rounds['Semi-finals'] as $match) {
            $final_players = array_merge($final_players, $match);
        }
        $rounds['Finals'] = [$final_players];
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
