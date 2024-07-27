<?php
require_once 'conn.php';

// Fetch all matches
$sql = "SELECT m.match_id, m.round, p1.player_name AS player1, p2.player_name AS player2, 
        m.player1_set1, m.player1_set2, m.player1_set3, 
        m.player2_set1, m.player2_set2, m.player2_set3, m.match_date
        FROM matches m
        LEFT JOIN players p1 ON m.player1_id = p1.player_id
        LEFT JOIN players p2 ON m.player2_id = p2.player_id
        ORDER BY FIELD(m.round, 'Quarter-finals Pool 1', 'Quarter-finals Pool 2', 'Semi-finals', 'Finals')";
$result = $conn->query($sql);
$matches = [];
while ($row = $result->fetch_assoc()) {
    $matches[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tournament Chart</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Tournament Chart</h2>
        <table>
            <thead>
                <tr>
                    <th>Round</th>
                    <th>Player 1</th>
                    <th>Player 2</th>
                    <th>1st Set (Player 1)</th>
                    <th>2nd Set (Player 1)</th>
                    <th>3rd Set (Player 1)</th>
                    <th>1st Set (Player 2)</th>
                    <th>2nd Set (Player 2)</th>
                    <th>3rd Set (Player 2)</th>
                    <th>Match Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matches as $match): ?>
                    <tr>
                        <td><?= htmlspecialchars($match['round']) ?></td>
                        <td><?= htmlspecialchars($match['player1']) ?></td>
                        <td><?= htmlspecialchars($match['player2'] ?? 'Bye') ?></td>
                        <td><?= htmlspecialchars($match['player1_set1']) ?></td>
                        <td><?= htmlspecialchars($match['player1_set2']) ?></td>
                        <td><?= htmlspecialchars($match['player1_set3']) ?></td>
                        <td><?= htmlspecialchars($match['player2_set1']) ?></td>
                        <td><?= htmlspecialchars($match['player2_set2']) ?></td>
                        <td><?= htmlspecialchars($match['player2_set3']) ?></td>
                        <td><?= htmlspecialchars($match['match_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body
