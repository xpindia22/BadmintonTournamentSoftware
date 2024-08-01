<?php
require_once 'config.php';

// Fetch fixtures from the database
function getFixtures($conn) {
    $query = 'SELECT m.match_id, m.round, m.pool, p1.player_name AS player1, p2.player_name AS player2, 
              m.player1_set1, m.player1_set2, m.player1_set3, m.player2_set1, m.player2_set2, m.player2_set3, 
              m.winner_id, m.match_date 
              FROM matches m 
              LEFT JOIN players p1 ON m.player1_id = p1.player_id 
              LEFT JOIN players p2 ON m.player2_id = p2.player_id 
              ORDER BY FIELD(m.round, "Pre-Quarter-finals", "Quarter-finals", "Semi-finals", "Finals"), m.match_date';
    $result = $conn->query($query);
    if (!$result) {
        die('Query failed: ' . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

$fixtures = getFixtures($conn);

// Create fixtures for the next round
function createNextRoundFixtures($conn, $currentRound, $nextRound) {
    $query = 'SELECT winner_id FROM matches WHERE round = ? AND winner_id IS NOT NULL';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $currentRound);
    $stmt->execute();
    $result = $stmt->get_result();
    $winners = $result->fetch_all(MYSQLI_ASSOC);

    $winners = array_column($winners, 'winner_id');
    for ($i = 0; $i < count($winners); $i += 2) {
        if (isset($winners[$i + 1])) {
            $query = 'INSERT INTO matches (round, pool, player1_id, player2_id) VALUES (?, ?, ?, ?)';
            $stmt = $conn->prepare($query);
            $pool = ($i % 2 == 0) ? 'A' : 'B'; // Alternate pools for next round
            $stmt->bind_param('ssii', $nextRound, $pool, $winners[$i], $winners[$i + 1]);
            $stmt->execute();
        }
    }
}

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

    foreach ([$poolA, $poolB] as $index => $pool) {
        $poolName = $pools[$index];
        for ($i = 0; $i < count($pool); $i += 2) {
            if (isset($pool[$i + 1])) {
                $query = 'INSERT INTO matches (round, pool, player1_id, player2_id) VALUES (?, ?, ?, ?)';
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssii', $round, $poolName, $pool[$i], $pool[$i + 1]);
                $stmt->execute();
            }
        }
    }

    // Update the settings table to indicate fixtures have been created
    $query = 'UPDATE settings SET value = "yes" WHERE key_name = "fixtures_created"';
    $conn->query($query);

    echo "Players assigned to pools and fixtures created successfully!";
}

function fixturesCreated($conn) {
    $query = 'SELECT value FROM settings WHERE key_name = "fixtures_created"';
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['value'] === 'yes';
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !fixturesCreated($conn)) {
    assignPlayersToPools($conn);
    $rounds = ['Pre-Quarter-finals' => 'Quarter-finals', 'Quarter-finals' => 'Semi-finals', 'Semi-finals' => 'Finals'];
    foreach ($rounds as $currentRound => $nextRound) {
        createNextRoundFixtures($conn, $currentRound, $nextRound);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Dashboard</title>
    <style>
        .column {
            float: left;
            width: 20%;
            margin-right: 10px;
        }
        .row::after {
            content: "";
            display: table;
            clear: both;
        }
        .winner {
            background-color: green;
            color: white;
            padding: 5px;
            margin-bottom: 5px;
        }
        .loser {
            background-color: lightblue;
            padding: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Tournament Dashboard</h1>
    </header>

    <form method="POST" action="">
        <?php if (!fixturesCreated($conn)) : ?>
            <button type="submit">Create Fixtures</button>
        <?php else : ?>
            <p>Fixtures have already been created.</p>
        <?php endif; ?>
    </form>

    <div class="row">
        <?php 
        $rounds = ["Pre-Quarter-finals", "Quarter-finals", "Semi-finals", "Finals"];
        foreach ($rounds as $round) : ?>
            <div class="column">
                <h2><?php echo $round; ?></h2>
                <?php foreach ($fixtures as $fixture) : 
                    if ($fixture['round'] == $round) : 
                        $player1_class = ($fixture['winner_id'] == $fixture['player1_id']) ? 'winner' : 'loser';
                        $player2_class = ($fixture['winner_id'] == $fixture['player2_id']) ? 'winner' : 'loser';
                ?>
                    <div class="<?php echo $player1_class; ?>">
                        <?php echo htmlspecialchars($fixture['player1']); ?>
                    </div>
                    <div class="<?php echo $player2_class; ?>">
                        <?php echo htmlspecialchars($fixture['player2']); ?>
                    </div>
                <?php endif; endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
