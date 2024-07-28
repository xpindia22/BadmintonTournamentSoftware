<?php
require_once 'conn.php';

$matches_result = $conn->query("SELECT matches.match_id, matches.round, p1.player_name AS player1, p2.player_name AS player2, 
    ms.player1_set1, ms.player1_set2, ms.player1_set3, ms.player2_set1, ms.player2_set2, ms.player2_set3, matches.match_date 
    FROM matches 
    LEFT JOIN players p1 ON matches.player1_id = p1.player_id 
    LEFT JOIN players p2 ON matches.player2_id = p2.player_id 
    LEFT JOIN match_scores ms ON matches.match_id = ms.match_id 
    ORDER BY FIELD(matches.round, 'Quarter-final', 'Semi-final', 'Final')");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tournament Fixtures</title>
</head>
<body
