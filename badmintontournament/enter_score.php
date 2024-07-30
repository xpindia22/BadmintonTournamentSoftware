<?php
require_once 'functions.php';

$message = '';

// Handle form submission for scores
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_scores'])) {
    $matchId = $_POST['match_id'];
    $player1Scores = [$_POST['player1_set1'], $_POST['player1_set2'], $_POST['player1_set3']];
    $player2Scores = [$_POST['player2_set1'], $_POST['player2_set2'], $_POST['player2_set3']];
    updateMatchResults($conn, $matchId, $player1Scores, $player2Scores);
    $message = "Scores updated successfully!";
}

// Handle form submission for creating a match
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_match'])) {
    $player1Id = $_POST['player1_id'];
    $player2Id = $_POST['player2_id'];
    createMatch($conn, $player1Id, $player2Id);
    $message = "Match created successfully!";
}

// Handle auto-generation of fixtures
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['auto_generate'])) {
    $message = autoGenerateFixtures($conn);
}

$players = getPlayers($conn);
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

    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST" action="enter_score.php">
        <select name="match_id" required>
            <?php if (count($matches) > 0): ?>
                <?php foreach ($matches as $match): ?>
                    <option value="<?php echo htmlspecialchars($match['match_id']); ?>">
                        <?php echo htmlspecialchars($match['player1']) . ' vs. ' . htmlspecialchars($match['player2']); ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">No matches available</option>
            <?php endif; ?>
        </select>

        <h3>Player 1 Scores</h3>
        <input type="number" name="player1_set1" placeholder="Set 1" required>
        <input type="number" name="player1_set2" placeholder="Set 2" required>
        <input type="number" name="player1_set3" placeholder="Set 3" required>

        <h3>Player 2 Scores</h3>
        <input type="number" name="player2_set1" placeholder="Set 1" required>
        <input type="number" name="player2_set2" placeholder="Set 2" required>
        <input type="number" name="player2_set3" placeholder="Set 3" required>

        <button type="submit" name="update_scores">Submit Scores</button>
    </form>

    <h2>Create Match</h2>
    <form method="POST" action="enter_score.php">
        <label for="player1_id">Player 1:</label>
        <select name="player1_id" required>
            <?php foreach ($players as $player): ?>
                <option value="<?php echo htmlspecialchars($player['player_id']); ?>">
                    <?php echo htmlspecialchars($player['player_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="player2_id">Player 2:</label>
        <select name="player2_id" required>
            <?php foreach ($players as $player): ?>
                <option value="<?php echo htmlspecialchars($player['player_id']); ?>">
                    <?php echo htmlspecialchars($player['player_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="create_match">Create Match</button>
    </form>

    <h2>Auto-generate Fixtures</h2>
    <form method="POST" action="enter_score.php">
        <button type="submit" name="auto_generate">Auto-generate Fixtures</button>
    </form>
</body>
</html>
