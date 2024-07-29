<?php
require_once 'config.php';

function getFixtures($conn) {
    $query = 'SELECT m.match_id, m.round, p1.player_name AS player1, p2.player_name AS player2, 
              m.player1_set1, m.player1_set2, m.player1_set3, m.player2_set1, m.player2_set2, m.player2_set3, m.winner_id, m.match_date 
              FROM matches m 
              LEFT JOIN players p1 ON m.player1_id = p1.player_id 
              LEFT JOIN players p2 ON m.player2_id = p2.player_id 
              ORDER BY FIELD(m.round, "Initial", "Pre-Quarter-finals", "Quarter-finals", "Semi-finals", "Finals")';
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

$fixtures = getFixtures($conn);
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
        }
        .row::after {
            content: "";
            display: table;
            clear: both;
        }
        .winner {
            color: green;
        }
        .loser {
            color: blue;
        }
    </style>
</head>
<body>
    <header>
        <h1>Tournament Dashboard</h1>
    </header>

    <div class="row">
        <?php 
        $rounds = ["Initial", "Pre-Quarter-finals", "Quarter-finals", "Semi-finals", "Finals"];
        foreach ($rounds as $round) : ?>
            <div class="column">
                <h2><?php echo $round; ?></h2>
                <?php foreach ($fixtures as $fixture) : ?>
                    <?php if ($fixture['round'] === $round) : ?>
                        <p class="<?php echo ($fixture['winner_id'] == $fixture['player1_id']) ? 'winner' : 'loser'; ?>">
                            <?php echo htmlspecialchars($fixture['player1']); ?>
                        </p>
                        <p class="<?php echo ($fixture['winner_id'] == $fixture['player2_id']) ? 'winner' : 'loser'; ?>">
                            <?php echo htmlspecialchars($fixture['player2']); ?>
                        </p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
