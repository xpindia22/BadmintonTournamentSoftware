<?php
require_once 'conn.php';

function push_winner_to_next_round($conn, $winner_id, $current_round) {
    // Determine the next round
    $round_map = [
        'Quarter-finals Pool 1' => 'Semi-finals Pool 1',
        'Quarter-finals Pool 2' => 'Semi-finals Pool 2',
        'Semi-finals Pool 1' => 'Finals',
        'Semi-finals Pool 2' => 'Finals'
    ];

    if (!isset($round_map[$current_round])) {
        return;
    }

    $next_round = $round_map[$current_round];

    // Find the existing match for the next round
    $sql = "SELECT match_id, player1_id, player2_id FROM matches WHERE round = ? AND (player1_id IS NULL OR player2_id IS NULL) LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $next_round);
    $stmt->execute();
    $stmt->bind_result($match_id, $player1_id, $player2_id);
    $stmt->fetch();
    $stmt->close();

    if ($match_id) {
        // Update the match with the winner
        if ($player1_id === null) {
            $sql = "UPDATE matches SET player1_id = ? WHERE match_id = ?";
        } else {
            $sql = "UPDATE matches SET player2_id = ? WHERE match_id = ?";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $winner_id, $match_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Create a new match for the next round
        $sql = "INSERT INTO matches (round, player1_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $next_round, $winner_id);
        $stmt->execute();
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $match_id = $_POST['match_id'];
    $player1_set1 = $_POST['player1_set1'];
    $player1_set2 = $_POST['player1_set2'];
    $player1_set3 = $_POST['player1_set3'];
    $player2_set1 = $_POST['player2_set1'];
    $player2_set2 = $_POST['player2_set2'];
    $player2_set3 = $_POST['player2_set3'];

    $player1_total = $player1_set1 + $player1_set2 + $player1_set3;
    $player2_total = $player2_set1 + $player2_set2 + $player2_set3;

    $winner_id = null;
    if ($player1_total > $player2_total) {
        $sql = "SELECT player1_id AS winner_id, round FROM matches WHERE match_id = ?";
    } else {
        $sql = "SELECT player2_id AS winner_id, round FROM matches WHERE match_id = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $match_id);
    $stmt->execute();
    $stmt->bind_result($winner_id, $current_round);
    $stmt->fetch();
    $stmt->close();

    $sql = "UPDATE matches SET 
        player1_set1 = ?, player1_set2 = ?, player1_set3 = ?, 
        player2_set1 = ?, player2_set2 = ?, player2_set3 = ?, 
        winner_id = ? WHERE match_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiiiiiii', 
        $player1_set1, $player1_set2, $player1_set3, 
        $player2_set1, $player2_set2, $player2_set3, 
        $winner_id, $match_id);
    $stmt->execute();
    $stmt->close();

    // Push the winner to the next round
    push_winner_to_next_round($conn, $winner_id, $current_round);
}

// Fetch all matches
$sql = "SELECT match_id, round, p1.player_name AS player1, p2.player_name AS player2, 
        player1_set1, player1_set2, player1_set3, 
        player2_set1, player2_set2, player2_set3, match_date 
        FROM matches
        LEFT JOIN players p1 ON matches.player1_id = p1.player_id
        LEFT JOIN players p2 ON matches.player2_id = p2.player_id
        ORDER BY FIELD(round, 'Quarter-finals Pool 1', 'Quarter-finals Pool 2', 'Semi-finals Pool 1', 'Semi-finals Pool 2', 'Finals')";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Scores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .container {
            text-align: center;
            margin: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }
        label {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enter Scores</h2>
        <form method="POST">
            <label>Match ID: <input type="text" name="match_id" required></label>
            <label>Player 1 Set 1: <input type="number" name="player1_set1" required></label>
            <label>Player 1 Set 2: <input type="number" name="player1_set2" required></label>
            <label>Player 1 Set 3: <input type="number" name="player1_set3" required></label>
            <label>Player 2 Set 1: <input type="number" name="player2_set1" required></label>
            <label>Player 2 Set 2: <input type="number" name="player2_set2" required></label>
            <label>Player 2 Set 3: <input type="number" name="player2_set3" required></label>
            <button type="submit">Submit Scores</button>
        </form>
        <h3>All Matches</h3>
        <table>
            <thead>
                <tr>
                    <th>Round</th>
                    <th>Player 1</th>
                    <th>Player 2</th>
                    <th>1st Set</th>
                    <th>2nd Set</th>
                    <th>3rd Set</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['round']) ?></td>
                        <td><?= htmlspecialchars($row['player1']) ?></td>
                        <td><?= htmlspecialchars($row['player2']) ?></td>
                        <td><?= htmlspecialchars($row['player1_set1'] . ' - ' . $row['player2_set1']) ?></td>
                        <td><?= htmlspecialchars($row['player1_set2'] . ' - ' . $row['player2_set2']) ?></td>
                        <td><?= htmlspecialchars($row['player1_set3'] . ' - ' . $row['player2_set3']) ?></td>
                        <td><?= htmlspecialchars($row['match_date']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
