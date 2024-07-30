<?php
// Ensure you have the database connection
require_once 'config.php';

function getMatches($conn) {
    $query = 'SELECT m.match_id, m.round, p1.player_name AS player1, p2.player_name AS player2
              FROM matches m
              LEFT JOIN players p1 ON m.player1_id = p1.player_id
              LEFT JOIN players p2 ON m.player2_id = p2.player_id
              WHERE m.winner_id IS NULL'; // Fetch only matches without a winner
    $result = $conn->query($query);
    if (!$result) {
        die('Query failed: ' . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

function updateMatchResults($conn, $matchId, $player1Scores, $player2Scores) {
    $totalPlayer1 = array_sum($player1Scores);
    $totalPlayer2 = array_sum($player2Scores);
    $winnerId = $totalPlayer1 > $totalPlayer2 ? 1 : 2;

    $query = 'UPDATE matches SET player1_set1 = ?, player1_set2 = ?, player1_set3 = ?, player2_set1 = ?, player2_set2 = ?, player2_set3 = ?, winner_id = ? WHERE match_id = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiiiiiii', $player1Scores[0], $player1Scores[1], $player1Scores[2], $player2Scores[0], $player2Scores[1], $player2Scores[2], $winnerId, $matchId);
    $stmt->execute();
}

function getPlayers($conn) {
    $query = 'SELECT player_id, player_name FROM players';
    $result = $conn->query($query);
    if (!$result) {
        die('Query failed: ' . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getPlayerProgressions($conn) {
    $query = 'SELECT m.round, p1.player_name AS player1, p2.player_name AS player2
              FROM matches m
              LEFT JOIN players p1 ON m.player1_id = p1.player_id
              LEFT JOIN players p2 ON m.player2_id = p2.player_id
              WHERE m.winner_id IS NOT NULL';
    $result = $conn->query($query);
    if (!$result) {
        die('Query failed: ' . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getPlayerLosers($conn) {
    $query = 'SELECT p.player_name
              FROM matches m
              LEFT JOIN players p ON (m.player1_id = p.player_id OR m.player2_id = p.player_id)
              WHERE m.winner_id IS NOT NULL AND (m.player1_id <> (SELECT winner_id FROM matches WHERE match_id = m.match_id) OR m.player2_id <> (SELECT winner_id FROM matches WHERE match_id = m.match_id))';
    $result = $conn->query($query);
    if (!$result) {
        die('Query failed: ' . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getMatchesByRound($conn, $round) {
    $query = 'SELECT m.match_id, m.round, p1.player_name AS player1, p2.player_name AS player2,
                     m.player1_set1, m.player1_set2, m.player1_set3, 
                     m.player2_set1, m.player2_set2, m.player2_set3
              FROM matches m
              LEFT JOIN players p1 ON m.player1_id = p1.player_id
              LEFT JOIN players p2 ON m.player2_id = p2.player_id
              WHERE m.round = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $round);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        die('Query failed: ' . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>
