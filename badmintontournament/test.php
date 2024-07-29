<?php
// Assuming you have already established a database connection

// Function to add a new player
function addPlayer($name, $round)
{
    // Insert player data into the 'players' table
    $sql = "INSERT INTO players (name, round) VALUES ('$name', '$round')";
    // Execute the query (you'll need to handle database connection and error handling)
    // Example: $result = mysqli_query($conn, $sql);
}

// Add players to Pool A (Round 1)
$players = [
    'Player 1', 'Player 2', /* ... up to Player 16 */
];

foreach ($players as $player) {
    addPlayer($player, 'Round 1');
}

// ... Continue adding players to subsequent rounds (pre-quarterfinals, quarterfinals, etc.)

// Display the dashboard
echo "<h2>Badminton Tournament Dashboard</h2>";
echo "<ul>";
echo "<li>Round 1 (Pool A): 16 players</li>";
echo "<li>Pre-Quarterfinals: 8 players</li>";
echo "<li>Quarterfinals: 4 players</li>";
echo "<li>Semifinals: 2 players</li>";
echo "<li>Finals: 1 winner</li>";
echo "</ul>";
?>
