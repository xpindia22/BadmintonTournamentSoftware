<?php
require_once 'config.php';

$matches_result = $conn->query("SELECT matches.match_id, matches.round, p1.player_name AS player1, p2.player_name AS player2, 
    ms.player1_set1, ms.player1_set2, ms.player1_set3, ms.player2_set1, ms.player2_set2, ms.player2_set3, matches.match_date 
    FROM matches 
    LEFT JOIN players p1 ON matches.player1_id = p1.player_id 
    LEFT JOIN players p2 ON matches.player2_id = p2.player_id 
    LEFT JOIN match_scores ms ON matches.match_id = ms.match_id 
    ORDER BY FIELD(matches.round, 'Quarter-finals', 'Semi-finals', 'Finals')");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tournament Fixtures</title>
</head>
<body>
    <h2>Tournament Fixtures</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Round</th>
                <th>Match</th>
                <th>Scores</th>
                <th>Match Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $matches_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['round']; ?></td>
                    <td><?php echo $row['player1'] . " vs " . $row['player2']; ?></td>
                    <td>
                        <?php echo $row['player1_set1'] . "-" . $row['player2_set1'] . ", "; ?>
                        <?php echo $row['player1_set2'] . "-" . $row['player2_set2'] . ", "; ?>
                        <?php echo $row['player1_set3'] . "-" . $row['player2_set3']; ?>
                    </td>
                    <td><?php echo $row['match_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
