<?php
session_start();
require_once 'db_connection.php'; 
require_once 'AdminClass.php'; 

$database = new Database();
$conn = $database->getConnection();
$admin = new AdminClass($conn);

try {
    // Call the method to archive done records
    if ($admin->archiveDoneRecords()) {
        header("Location: MarkedAsDone.php");
        exit();
    } else {
        throw new Exception("Error executing stored procedure.");
    }
} catch (Exception $e) {
    // Handle the error (optional: log the error message)
    header("Location: MarkedAsDone.php");
    exit();
}

if (isset($_GET['redirect']) && $_GET['redirect'] === 'archive') {
    header("Location: Archive.php");
    exit();
}

$conn = null; 
?>