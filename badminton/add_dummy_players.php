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

// Insert sample players
$players = [
    'Player 1',
    'Player 2',
    'Player 3',
    'Player 4',
    'Player 5',
    'Player 6',
    'Player 7',
    'Player 8'
];

foreach ($players as $player_name) {
    $sql = "INSERT INTO players (player_name) VALUES ('$player_name')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully for $player_name<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
    }
}

// Insert sample matches
$matches = [
    ['Quarter-finals Pool 1', 1, 2, '2024-07-27 10:00:00'],
    ['Quarter-finals Pool 1', 3, 4, '2024-07-27 11:00:00'],
    ['Quarter-finals Pool 2', 5, 6, '2024-07-27 12:00:00'],
    ['Quarter-finals Pool 2', 7, 8, '2024-07-27 13:00:00'],
    ['Semi-finals Pool 1', 1, 3, '2024-07-28 10:00:00'],
    ['Semi-finals Pool 2', 5, 7, '2024-07-28 11:00:00'],
    ['Finals', 1, 5, '2024-07-29 10:00:00']
];

foreach ($matches as $match) {
    $round = $match[0];
    $player1_id = $match[1];
    $player2_id = $match[2];
    $match_date = $match[3];

    $sql = "INSERT INTO matches (round, player1_id, player2_id, match_date) 
            VALUES ('$round', $player1_id, $player2_id, '$match_date')";

    if ($conn->query($sql) === TRUE) {
        $match_id = $conn->insert_id;
        echo "New match record created successfully for $round: Player $player1_id vs Player $player2_id<br>";

        // Insert sample scores
        $scores = [
            [$match_id, 21, 15, 21, 15, 21, 18],
            [$match_id, 19, 21, 18, 21, 21, 21]
        ];

        foreach ($scores as $score) {
            $sql = "INSERT INTO match_scores (match_id, player1_set1, player1_set2, player1_set3, player2_set1, player2_set2, player2_set3) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iiiiiii', $score[0], $score[1], $score[2], $score[3], $score[4], $score[5], $score[6]);
            if ($stmt->execute()) {
                echo "Scores recorded for match $match_id<br>";
            } else {
                echo "Error: " . $stmt->error . "<br>";
            }
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
    }
}

// Close connection
$conn->close();
?>
