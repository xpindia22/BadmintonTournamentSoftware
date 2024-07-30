<?php
require_once 'config.php';

// Fetch matches that do not have scores entered yet
$query = 'SELECT m.match_id, p1.player_name AS player1, p2.player_name AS player2, m.round
          FROM matches m
          LEFT JOIN players p1 ON m.player1_id = p1.player_id
          LEFT JOIN players p2 ON m.player2_id = p2.player_id
          WHERE m.winner_id IS NULL';
$result = $conn->query($query);
if (!$result) {
    die('Query failed: ' . $conn->error);
}

$matches = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $match_id = $_POST['match_id'];
    $player1_set1 = $_POST['player1_set1'];
    $player1_set2 = $_POST['player1_set2'];
    $player1_set3 = $_POST['player1_set3'];
    $player2_set1 = $_POST['player2_set1'];
    $player2_set2 = $_POST['player2_set2'];
    $player2_set3 = $_POST['player2_set3'];
    $player1_id = $_POST['player1_id'];
    $player2_id = $_POST['player2_id'];

    $player1_score = $player1_set1 + $player1_set2 + $player1_set3;
    $player2_score = $player2_set1 + $player2_set2 + $player2_set3;

    $winner_id = ($player1_score > $player2_score) ? $player1_id : $player2_id;

    $query = 'UPDATE matches 
              SET player1_set1 = ?, player1_set2 = ?, player1_set3 = ?, 
                  player2_set1 = ?, player2_set2 = ?, player2_set3 = ?, 
                  winner_id = ? 
              WHERE match_id = ?';
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param('iiiiiiii', $player1_set1, $player1_set2, $player1_set3, $player2_set1, $player2_set2, $player2_set3, $winner_id, $match_id);
    $stmt->execute();

    echo "Scores updated successfully!";
}
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

    <?php if (count($matches) > 0) : ?>
        <form method="POST" action="">
            <label for="match_id">Select Match:</label>
            <select name="match_id" id="match_id">
                <?php foreach ($matches as $match) : ?>
                    <option value="<?php echo $match['match_id']; ?>">
                        <?php echo htmlspecialchars($match['player1']) . ' vs ' . htmlspecialchars($match['player2']); ?> - <?php echo $match['round']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <h3>Enter Scores</h3>
            <label for="player1_set1">Player 1 Set 1:</label>
            <input type="number" name="player1_set1" id="player1_set1" required><br>

            <label for="player1_set2">Player 1 Set 2:</label>
            <input type="number" name="player1_set2" id="player1_set2" required><br>

            <label for="player1_set3">Player 1 Set 3:</label>
            <input type="number" name="player1_set3" id="player1_set3"><br>

            <label for="player2_set1">Player 2 Set 1:</label>
            <input type="number" name="player2_set1" id="player2_set1" required><br>

            <label for="player2_set2">Player 2 Set 2:</label>
            <input type="number" name="player2_set2" id="player2_set2" required><br>

            <label for="player2_set3">Player 2 Set 3:</label>
            <input type="number" name="player2_set3" id="player2_set3"><br>

            <input type="hidden" name="player1_id" value="<?php echo $matches[0]['player1_id']; ?>">
            <input type="hidden" name="player2_id" value="<?php echo $matches[0]['player2_id']; ?>">
            
            <button type="submit">Submit Scores</button>
        </form>
    <?php else : ?>
        <p>No matches available for score entry.</p>
    <?php endif; ?>
</body>
</html>
