<?php
require_once 'config.php';

function updateMatchResults($conn, $matchId, $player1Scores, $player2Scores) {
    $totalPlayer1 = array_sum($player1Scores);
    $totalPlayer2 = array_sum($player2Scores);
    $winnerId = $totalPlayer1 > $totalPlayer2 ? 1 : 2;

    $query = 'UPDATE matches SET player1_set1 = ?, player1_set2 = ?, player1_set3 = ?, player2_set1 = ?, player2_set2 = ?, player2_set3 = ?, winner_id = ? WHERE match_id = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiiiiiii', $player1Scores[0], $player1Scores[1], $player1Scores[2], $player2Scores[0], $player2Scores[1], $player2Scores[2], $winnerId, $matchId);
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matchId = $_POST['match_id'];
    $player1Scores = [$_POST['player1_set1'], $_POST['player1_set2'], $_POST['player1_set3']];
    $player2Scores = [$_POST['player2_set1'], $_POST['player2_set2'], $_POST['player2_set3']];
    updateMatchResults($conn, $matchId, $player1Scores, $player2Scores);
    echo "Scores updated successfully!";
}

function getMatches($conn) {
    $query = 'SELECT match_id, round, p1.player_name AS player1, p2.player_name AS player2 FROM matches
              LEFT JOIN players p1 ON matches.player1_id = p1.player_id
              LEFT JOIN players p2 ON matches.player2_id = p2.player_id';
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

$matches = getMatches($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Match Scores</title>
</head>
<body>
    <header>
        <h1>Enter Match Scores</h1>
    </header>

    <form method="POST" action="">
        <select name="match_id">
            <?php foreach ($matches as $match) : ?>
                <option value="<?php echo htmlspecialchars($match['match_id']); ?>">
                    <?php echo htmlspecialchars($match['player1']) . ' vs. ' . htmlspecialchars($match['player2']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <h3>Player 1 Scores</h3>
        <input type="number" name="player1_set1" placeholder="Set 1" required>
        <input type="number" name="player1_set2" placeholder="Set 2" required>
        <input type="number" name="player1_set3" placeholder="Set 3" required>

        <h3>Player 2 Scores</h3>
        <input type="number" name="player2_set1" placeholder="Set 1" required>
        <input type="number" name="player2_set2" placeholder="Set 2" required>
        <input type="number" name="player2_set3" placeholder="Set 3" required>

        <button type="submit">Submit Scores</button>
    </form>
</body>
</html>
