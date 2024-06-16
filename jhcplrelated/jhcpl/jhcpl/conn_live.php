<?php
//Database credentials for live server
    $dbname_live = 'xxx';
    $username_live = 'xxx';
    $password_live = 'c&)WLGHiecg{';

    // Create connection to live server
    //$livehost = 'xxx0';
    $livehost = 'xxx';
    // $conn_live = new mysqli($livehost, $dbname_live, $username_live, $password_live);
   
    $conn_live = new PDO("mysql:host=$livehost;dbname=$dbname_live", $username_live, $password_live); 
    // Check connections
    if (!$conn_live) {
        die("Connection failed: " . mysqli_connect_error());
    }
    ?>
