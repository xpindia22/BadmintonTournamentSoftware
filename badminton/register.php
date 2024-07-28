<?php
require_once 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $player_name = $_POST['player_name'];

    $stmt = $conn->prepare("INSERT INTO players (player_name) VALUES (?)");
    $stmt->bind_param("s", $player_name);
    if ($stmt->execute()) {
        echo "Player added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Player</title>
</head>
<body>
    <h2>Add Player</h2>
    <form method="post" action="">
        <label for="player_name">Player Name:</label>
        <input type="text" id="player_name" name="player_name" required>
        <input type="submit" value="Add Player">
    </form>
</body>
</html>
