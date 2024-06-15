<?php
$servername = "localhost";
$username = "jhcpl";
$password = "jhcpl";
$dbname = "jhcpl";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];
$name = $_POST['name'];
// Other fields (age, sex, unit_no, diagnosis, date)

$sql = "UPDATE user_info SET name='$name' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully!";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
