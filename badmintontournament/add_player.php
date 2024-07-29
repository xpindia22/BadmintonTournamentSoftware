 
<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerName = $_POST['player_name'];
    $stmt = $conn->prepare('INSERT INTO players (player_name) VALUES (?)');
    $stmt->bind_param('s', $playerName);
    $stmt->execute();
}

$players = $conn->query('SELECT * FROM players')->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Registration</title>
</head>
<body>
    <h1>Register Player</h1>
    <form method="POST">
        <input type="text" name="player_name" required>
        <button type="submit">Register</button>
    </form>
    
    <h2>Registered Players</h2>
    <ul>
        <?php foreach ($players as $player): ?>
            <li><?php echo htmlspecialchars($player['player_name']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
