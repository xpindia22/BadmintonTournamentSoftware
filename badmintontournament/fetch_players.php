<?php
require_once 'config.php';

function getPlayers($conn) {
    $query = 'SELECT player_id, player_name, pool FROM players';
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

$players = getPlayers($conn);
echo json_encode($players);
?>
