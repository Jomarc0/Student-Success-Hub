<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "individual_interview_form";

try {
    $socket = @fsockopen($servername, 3306, $errno, $errstr, 5);
    if (!$socket) {
        throw new Exception("MySQL server is not running. Please start your MySQL server in XAMPP Control Panel.");
    }
    fclose($socket);

    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    
    $conn = new PDO($dsn, $username, $password, $options);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

?>