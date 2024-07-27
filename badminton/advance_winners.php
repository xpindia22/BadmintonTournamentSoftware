<?php
require_once 'conn.php';

function advance_winners($round) {
    global $conn;
    $next_round = '';
    switch ($round) {
        case 'Quarter-finals':
            $next_round = 'Semi-finals';
            break;
        case 'Semi-finals':
            $next_round = 'Final';
            break;
        default:
            return;
    }

    $sql = "SELECT match_id, winner_id FROM matches WHERE round = '$round' AND winner_id IS NOT NULL";
    $result = $conn->query($sql);
    $winners = [];
    while ($row = $result->fetch_assoc()) {
        $winners[] = $row['winner_id'];
    }

    $fixtures = [];
    for ($i = 0; $i < count($winners); $i += 2) {
        if (isset($winners[$i+1])) {
            $fixtures[] = [
                'player1' => $winners[$i],
                'player2' => $winners[$i + 1],
                'round' => $next_round
            ];
        } else {
            // Bye case
            $fixtures[] = [
                'player1' => $winners[$i],
                'player2' => null,
                'round' => $next_round,
                'winner' => $winners[$i]
            ];
        }
    }

    foreach ($fixtures as $fixture) {
        $sql = "INSERT INTO matches (round, player1_id, player2_id, winner_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $player1_id = $fixture['player1'];
        $player2_id = $fixture['player2'] ?? null;
        $winner_id = $fixture['winner'] ?? null;
        $stmt->bind_param('siii', $fixture['round'], $player1_id, $player2_id, $winner_id);
        $stmt->execute();
    }
}

// Example usage:
advance_winners('Quarter-finals');
advance_winners('Semi-finals');

$conn->close();
