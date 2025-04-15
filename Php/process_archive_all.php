<?php
session_start();
include 'db_connection.php'; 

try {
    // call the stored procedure 
    $stmt = $conn->prepare("CALL ArchiveDoneRecords()");
    
    if ($stmt->execute()) {
        header("Location: MarkedAsDone.php");
        exit();
    } else {
        throw new Exception("Error executing stored procedure.");
    }

} catch (Exception $e) {
    header("Location: MarkedAsDone.php");
    exit();
}

if (isset($_GET['redirect']) && $_GET['redirect'] === 'archive') {
    header("Location: Archive.php");
    exit();
}

$conn = null; 
?>