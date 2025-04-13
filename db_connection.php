<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "individual_interview_form";

try {
    // Check if MySQL service is running
    $socket = @fsockopen($servername, 3306, $errno, $errstr, 5);
    if (!$socket) {
        throw new Exception("MySQL server is not running. Please start your MySQL server in XAMPP Control Panel.");
    }
    fclose($socket);

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

echo " ";

?>