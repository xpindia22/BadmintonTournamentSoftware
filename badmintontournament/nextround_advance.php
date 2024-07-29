<?php
require_once 'config.php';

function createNextRoundFixtures($conn, $currentRound, $nextRound) {
    // Fetch winners from the current round
    $query = 'SELECT winner_id FROM matches WHERE round = ? AND winner_id IS NOT NULL';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $currentRound);
    $stmt->execute();
    $result = $stmt->get_result();
    $winners = $result->fetch_all(MYSQLI_ASSOC);

    // Ensure winners are paired correctly
    $winners = array_column($winners, 'winner_id');
    for ($i = 0; $i < count($winners); $i += 2) {
        if (isset($winners[$i + 1])) {
            $query = 'INSERT INTO matches (round, player1_id, player2_id) VALUES (?, ?, ?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sii', $nextRound, $winners[$i], $winners[$i + 1]);
            $stmt->execute();
        }
    }
}

// Define the rounds and their progression
$rounds = [
    'Initial' => 'Pre-Quarter-finals',
    'Pre-Quarter-finals' => 'Quarter-finals',
    'Quarter-finals' => 'Semi-finals',
    'Semi-finals' => 'Finals'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($rounds as $currentRound => $nextRound) {
        createNextRoundFixtures($conn, $currentRound, $nextRound);
    }
    echo "Fixtures created for the next rounds!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Next Round Fixtures</title>
</head>
<body>
    <header>
        <h1>Create Next Round Fixtures</h1>
    </header>

    <form method="POST" action="">
        <button type="submit">Create Next Round Fixtures</button>
    </form>
</body>
</html>
