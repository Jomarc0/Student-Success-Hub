<?php
session_start();
include 'db_connection.php'; // Ensure this file uses PDO for connection

try {
    // Call the stored procedure to archive done records
    $stmt = $conn->prepare("CALL ArchiveDoneRecords()");
    
    if ($stmt->execute()) {
        header("Location: MarkedAsDone.php");
        exit();
    } else {
        throw new Exception("Error executing stored procedure.");
    }

} catch (Exception $e) {
    // Optionally log the error message for debugging
    // error_log($e->getMessage());
    header("Location: MarkedAsDone.php");
    exit();
}

// Check for redirect parameter
if (isset($_GET['redirect']) && $_GET['redirect'] === 'archive') {
    header("Location: Archive.php");
    exit();
}

// Close the connection
$conn = null; // Close the PDO connection
?>