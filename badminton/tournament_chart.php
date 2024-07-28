<?php
// Database connection configuration
$host = 'localhost'; // Your database host
$dbname = 'badminton'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch match scores
$sql = "SELECT m.round, p1.player_name AS player1, p2.player_name AS player2,
        ms.player1_set1, ms.player1_set2, ms.player1_set3,
        ms.player2_set1, ms.player2_set2, ms.player2_set3
        FROM match_scores ms
        JOIN matches m ON ms.match_id = m.match_id
        JOIN players p1 ON m.player1_id = p1.player_id
        JOIN players p2 ON m.player2_id = p2.player_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<h3>Match Round: " . $row['round'] . "</h3>";
        echo "<p>" . $row['player1'] . ":<br>";
        echo $row['player1_set1'] . "-" . $row['player2_set1'] . "<br>";
        echo $row['player1_set2'] . "-" . $row['player2_set2'] . "<br>";
        echo $row['player1_set3'] . "-" . $row['player2_set3'] . "</p>";
        echo "<p>" . $row['player2'] . "</p>";
    }
} else {
    echo "No matches found.";
}

// Close connection
$conn->close();
?>
