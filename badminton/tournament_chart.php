<?php
require_once 'conn.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT m.match_id, m.round, p1.player_name AS player1, p2.player_name AS player2,
        ms.player1_set1, ms.player1_set2, ms.player1_set3,
        ms.player2_set1, ms.player2_set2, ms.player2_set3, m.match_date
        FROM matches m
        LEFT JOIN players p1 ON m.player1_id = p1.player_id
        LEFT JOIN players p2 ON m.player2_id = p2.player_id
        LEFT JOIN match_scores ms ON m.match_id = ms.match_id
        ORDER BY FIELD(m.round, 'Quarter-finals Pool 1', 'Quarter-finals Pool 2', 'Semi-finals Pool 1', 'Semi-finals Pool 2', 'Finals')";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tournament Chart</title>
</head>
<body>
    <h2>Tournament Chart</h2>
    <?php while ($row = $result->fetch_assoc()) : ?>
        <div>
            <h3><?php echo $row['round']; ?></h3>
            <p><?php echo $row['player1']; ?>: <?php echo $row['player1_set1'] . '-' . $row['player2_set1'] . ', ' . $row['player1_set2'] . '-' . $row['player2_set2'] . ', ' . $row['player1_set3'] . '-' . $row
