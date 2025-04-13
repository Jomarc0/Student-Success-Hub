<?php
session_start();
include 'db_connection.php';

if (isset($_GET['name'])) {
    $student_name = $_GET['name'];
    
    
    $update_query = "UPDATE form SET status = 'done' WHERE student_name = ? AND status = 'archived'";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("s", $student_name);
    
    if ($stmt->execute()) {
        header("Location: MarkedAsDone.php");
        exit();
    }
    
    $stmt->close();
}

$conn->close();
header("Location: Archive.php");
exit();
?> 