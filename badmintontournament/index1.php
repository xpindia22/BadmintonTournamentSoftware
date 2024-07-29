<?php
require_once 'config.php';

// Fetching fixtures from the database
function getFixtures($conn) {
    $query = 'SELECT m.match_id, m.round, p1.player_name AS player1, p2.player_name AS player2, 
              m.player1_set1 AS player1_set1, m.player1_set2 AS player1_set2, m.player1_set3 AS player1_set3, 
              m.player2_set1 AS player2_set1, m.player2_set2 AS player2_set2, m.player2_set3 AS player2_set3, 
              m.match_date 
              FROM matches m 
              LEFT JOIN players p1 ON m.player1_id = p1.player_id 
              LEFT JOIN players p2 ON m.player2_id = p2.player_id 
              ORDER BY FIELD(m.round, \'Pre-Quarter-finals\', \'Quarter-finals\', \'Semi-finals\', \'Finals\')';
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
    <title>Badminton Tournament</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        header, section {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            margin: 0 0 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Badminton Tournament</h1>
    </header>

    <section class="fixtures">
        <h2>Fixtures</h2>
        <?php if (empty($fixtures)) : ?>
            <p>No fixtures available.</p>
        <?php else : ?>
            <?php foreach ($fixtures as $fixture) : ?>
                <p>
                    <strong><?php echo htmlspecialchars($fixture['round']); ?>:</strong> 
                    <?php echo htmlspecialchars($fixture['player1']); ?> 
                    <?php echo htmlspecialchars($fixture['player1_set1']); ?>-<?php echo htmlspecialchars($fixture['player2_set1']); ?> 
                    <?php echo htmlspecialchars($fixture['player1_set2']); ?>-<?php echo htmlspecialchars($fixture['player2_set2']); ?> 
                    <?php echo htmlspecialchars($fixture['player1_set3']); ?>-<?php echo htmlspecialchars($fixture['player2_set3']); ?> 
                    <?php echo htmlspecialchars($fixture['player2']); ?> - 
                    <?php echo htmlspecialchars($fixture['match_date']); ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <footer>
        <p>© 2024 Badminton Tournament</p>
    </footer>
</body>
</html>
