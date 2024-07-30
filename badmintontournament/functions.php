<?php
require_once 'config.php';

/**
 * Fetch matches by round
 * 
 * @param mysqli $conn Database connection
 * @param int $round Round number (1, 3, 4, 5, etc.)
 * @return array Array of matches
 */
function getMatchesByRound($conn, $round) {
    $query = 'SELECT m.match_id, m.round, p1.player_name AS player1, p2.player_name AS player2,
                     m.player1_set1, m.player1_set2, m.player1_set3,
                     m.player2_set1, m.player2_set2, m.player2_set3,
                     m.winner_id
              FROM matches m
              LEFT JOIN players p1 ON m.player1_id = p1.player_id
              LEFT JOIN players p2 ON m.player2_id = p2.player_id
              WHERE m.round = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $round);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Fetch player progressions (winners who progressed to next rounds)
 * 
 * @param mysqli $conn Database connection
 * @return array Array of player names
 */
function getPlayerProgressions($conn) {
    $query = 'SELECT p.player_name, m.round
              FROM players p
              JOIN matches m ON p.player_id = m.winner_id
              WHERE m.round > 1'; // Assuming round 1 is not included for progressions
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Fetch player losers (players who lost in the previous rounds)
 * 
 * @param mysqli $conn Database connection
 * @return array Array of player names
 */
function getPlayerLosers($conn) {
    $query = 'SELECT DISTINCT p.player_name
              FROM players p
              JOIN matches m ON p.player_id IN (m.player1_id, m.player2_id)
              WHERE m.winner_id IS NOT NULL AND m.winner_id <> p.player_id';
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get all players
 * 
 * @param mysqli $conn Database connection
 * @return array Array of players
 */
function getPlayers($conn) {
    $query = 'SELECT player_id, player_name FROM players';
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Update match results
 * 
 * @param mysqli $conn Database connection
 * @param int $matchId Match ID
 * @param array $player1Scores Scores for Player 1
 * @param array $player2Scores Scores for Player 2
 * @return void
 */
function updateMatchResults($conn, $matchId, $player1Scores, $player2Scores) {
    $totalPlayer1 = array_sum($player1Scores);
    $totalPlayer2 = array_sum($player2Scores);
    $winnerId = $totalPlayer1 > $totalPlayer2 ? 1 : 2;

    $query = 'UPDATE matches SET player1_set1 = ?, player1_set2 = ?, player1_set3 = ?, player2_set1 = ?, player2_set2 = ?, player2_set3 = ?, winner_id = ? WHERE match_id = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiiiiiii', $player1Scores[0], $player1Scores[1], $player1Scores[2], $player2Scores[0], $player2Scores[1], $player2Scores[2], $winnerId, $matchId);
    $stmt->execute();
}

/**
 * Get all matches
 * 
 * @param mysqli $conn Database connection
 * @return array Array of matches
 */
function getAllMatches($conn) {
    $query = 'SELECT m.match_id, m.round, p1.player_name AS player1, p2.player_name AS player2,
                     m.player1_set1, m.player1_set2, m.player1_set3,
                     m.player2_set1, m.player2_set2, m.player2_set3,
                     m.winner_id
              FROM matches m
              LEFT JOIN players p1 ON m.player1_id = p1.player_id
              LEFT JOIN players p2 ON m.player2_id = p2.player_id';
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>
