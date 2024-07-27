<?php
require_once 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $player_name = mysqli_real_escape_string($conn, $_POST['player_name']);
    $sql = "INSERT INTO players (player_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $player_name);
    if ($stmt->execute()) {
        echo "Player registered successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Player</title>
</head>
<body>
    <h2>Register Player</h2>
    <form method="post" action="">
        <label for="player_name">Player Name:</label>
        <input type="text" id="player_name" name="player_name" required>
        <input type="submit" value="Register">
    </form>
</body>
</html>
