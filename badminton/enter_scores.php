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

// Function to insert or update match scores
function saveMatchScores($conn, $match_id, $player1_set1, $player1_set2, $player1_set3, $player2_set1, $player2_set2, $player2_set3) {
    // Check if the match score already exists
    $check_sql = "SELECT score_id FROM match_scores WHERE match_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param('i', $match_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update existing match score
        $update_sql = "UPDATE match_scores SET 
            player1_set1 = ?, player1_set2 = ?, player1_set3 = ?, 
            player2_set1 = ?, player2_set2 = ?, player2_set3 = ? 
            WHERE match_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('iiiiiii', $player1_set1, $player1_set2, $player1_set3, $player2_set1, $player2_set2, $player2_set3, $match_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Insert new match score
        $insert_sql = "INSERT INTO match_scores (match_id, player1_set1, player1_set2, player1_set3, player2_set1, player2_set2, player2_set3) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param('iiiiiii', $match_id, $player1_set1, $player1_set2, $player1_set3, $player2_set1, $player2_set2, $player2_set3);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
    $stmt->close();
}

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $match_id = $_POST['match_id'];
    $player1_set1 = $_POST['player1_set1'];
    $player1_set2 = $_POST['player1_set2'];
    $player1_set3 = $_POST['player1_set3'];
    $player2_set1 = $_POST['player2_set1'];
    $player2_set2 = $_POST['player2_set2'];
    $player2_set3 = $_POST['player2_set3'];

    saveMatchScores($conn, $match_id, $player1_set1, $player1_set2, $player1_set3, $player2_set1, $player2_set2, $player2_set3);

    echo "Scores saved successfully!";
}

// Fetch matches
$sql = "SELECT m.match_id, m.round, p1.player_name AS player1, p2.player_name AS player2, 
        ms.player1_set1, ms.player1_set2, ms.player1_set3, 
        ms.player2_set1, ms.player2_set2, ms.player2_set3, m.match_date 
        FROM matches m
        LEFT JOIN players p1 ON m.player1_id = p1.player_id 
        LEFT JOIN players p2 ON m.player2_id = p2.player_id 
        LEFT JOIN match_scores ms ON m.match_id = ms.match_id
        ORDER BY FIELD(m.round, 'Quarter-finals Pool 1', 'Quarter-finals Pool 2', 'Semi-finals Pool 1', 'Semi-finals Pool 2', 'Finals')";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enter Scores</title>
</head>
<body>
    <h1>Enter Scores</h1>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <form method="POST" action="">
                <h3>Match: <?php echo $row['player1']; ?> vs <?php echo $row['player2']; ?> (<?php echo $row['round']; ?>)</h3>
                <p>Date: <?php echo $row['match_date']; ?></p>
                <input type="hidden" name="match_id" value="<?php echo $row['match_id']; ?>">
                <label for="player1_set1"><?php echo $row['player1']; ?> Set 1:</label>
                <input type="number" name="player1_set1" value="<?php echo $row['player1_set1']; ?>">
                <br>
                <label for="player1_set2"><?php echo $row['player1']; ?> Set 2:</label>
                <input type="number" name="player1_set2" value="<?php echo $row['player1_set2']; ?>">
                <br>
                <label for="player1_set3"><?php echo $row['player1']; ?> Set 3:</label>
                <input type="number" name="player1_set3" value="<?php echo $row['player1_set3']; ?>">
                <br>
                <label for="player2_set1"><?php echo $row['player2']; ?> Set 1:</label>
                <input type="number" name="player2_set1" value="<?php echo $row['player2_set1']; ?>">
                <br>
                <label for="player2_set2"><?php echo $row['player2']; ?> Set 2:</label>
                <input type="number" name="player2_set2" value="<?php echo $row['player2_set2']; ?>">
                <br>
                <label for="player2_set3"><?php echo $row['player2']; ?> Set 3:</label>
                <input type="number" name="player2_set3" value="<?php echo $row['player2_set3']; ?>">
                <br>
                <input type="submit" value="Save Scores">
            </form>
            <hr>
            <?php
        }
    } else {
        echo "No matches found.";
    }
    ?>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
