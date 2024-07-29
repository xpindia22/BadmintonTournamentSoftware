<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $match_id = $_POST['match_id'];
    $player1_set1 = $_POST['player1_set1'];
    $player1_set2 = $_POST['player1_set2'];
    $player1_set3 = $_POST['player1_set3'];
    $player2_set1 = $_POST['player2_set1'];
    $player2_set2 = $_POST['player2_set2'];
    $player2_set3 = $_POST['player2_set3'];

    $stmt = $conn->prepare("INSERT INTO match_scores (match_id, player1_set1, player1_set2, player1_set3, player2_set1, player2_set2, player2_set3) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiiii", $match_id, $player1_set1, $player1_set2, $player1_set3, $player2_set1, $player2_set2, $player2_set3);
    $stmt->execute();
    $stmt->close();
}

$matches_result = $conn->query("SELECT matches.match_id, matches.round, p1.player_name AS player1, p2.player_name AS player2 FROM matches LEFT JOIN players p1 ON matches.player1_id = p1.player_id LEFT JOIN players p2 ON matches.player2_id = p2.player_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Scores</title>
</head>
<body>
    <h2>Enter Scores</h2>
    <form method="post" action="">
        <label for="match_id">Match:</label>
        <select id="match_id" name="match_id" required>
            <?php while ($row = $matches_result->fetch_assoc()): ?>
                <option value="<?php echo $row['match_id']; ?>"><?php echo $row['round'] . ": " . $row['player1'] . " vs " . $row['player2']; ?></option>
            <?php endwhile; ?>
        </select><br><br>
        
        <label for="player1_set1">Player 1 Set 1:</label>
        <input type="number" id="player1_set1" name="player1_set1" required><br><br>
        <label for="player1_set2">Player 1 Set 2:</label>
        <input type="number" id="player1_set2" name="player1_set2" required><br><br>
        <label for="player1_set3">Player 1 Set 3:</label>
        <input type="number" id="player1_set3" name="player1_set3" required><br><br>

        <label for="player2_set1">Player 2 Set 1:</label>
        <input type="number" id="player2_set1" name="player2_set1" required><br><br>
        <label for="player2_set2">Player 2 Set 2:</label>
        <input type="number" id="player2_set2" name="player2_set2" required><br><br>
        <label for="player2_set3">Player 2 Set 3:</label>
        <input type="number" id="player2_set3" name="player2_set3" required><br><br>

        <input type="submit" value="Submit Scores">
    </form>
</body>
</html>
