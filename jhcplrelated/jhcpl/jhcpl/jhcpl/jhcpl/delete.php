<?php
$servername = "localhost";
$username = "jhcpl";
$password = "jhcpl";
$dbname = "jhcpl";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id']; // Assuming you pass the ID via URL parameter

$sql = "DELETE FROM user_info WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully!";
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
