<?php
require_once 'config.php';

// Function to update match results
function updateMatchResults($conn, $matchId, $player1Scores, $player2Scores) {
    $totalPlayer1 = array_sum($player1Scores);
    $totalPlayer2 = array_sum($player2Scores);
    $winnerId = $totalPlayer1 > $totalPlayer2 ? 1 : 2;

    $query = 'UPDATE matches SET player1_set1 = ?, player1_set2 = ?, player1_set3 = ?, player2_set1 = ?, player2_set2 = ?, player2_set3 = ?, winner_id = ? WHERE match_id = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiiiiiii', $player1Scores[0], $player1Scores[1], $player1Scores[2], $player2Scores[0], $player2Scores[1], $player2Scores[2], $winnerId, $matchId);
    $stmt->execute();
    $stmt->close();
}

// Function to fetch players
function getPlayers($conn) {
    $query = 'SELECT player_id, player_name FROM players';
    $result = $conn->query($query);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Function to fetch matches without a winner
function getMatches($conn) {
    $query = 'SELECT m.match_id, p1.player_name AS player1, p2.player_name AS player2
              FROM matches m
              LEFT JOIN players p1 ON m.player1_id = p1.player_id
              LEFT JOIN players p2 ON m.player2_id = p2.player_id
              WHERE m.winner_id IS NULL';
    $result = $conn->query($query);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Function to create a match
function createMatch($conn, $player1Id, $player2Id) {
    $query = 'INSERT INTO matches (player1_id, player2_id) VALUES (?, ?)';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $player1Id, $player2Id);
    $stmt->execute();
    $stmt->close();
}

// Function to auto-generate fixtures
function autoGenerateFixtures($conn) {
    $players = getPlayers($conn);
    $numPlayers = count($players);
    
    if ($numPlayers < 2) {
        return "Not enough players to generate fixtures.";
    } else {
        for ($i = 0; $i < $numPlayers; $i += 2) {
            if ($i + 1 < $numPlayers) {
                createMatch($conn, $players[$i]['player_id'], $players[$i + 1]['player_id']);
            }
        }
        return "Fixtures generated successfully!";
    }
}
?>
