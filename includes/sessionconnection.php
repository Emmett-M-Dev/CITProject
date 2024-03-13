<?php
// Include session check and user information retrieval if needed
session_start();

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "emurray46";
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// check if the user is logged in 
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
   
} else {
   
    echo $username;
    exit();
}


?>