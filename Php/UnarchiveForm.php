<?php
session_start();
include 'db_connection.php'; 

if (isset($_GET['name'])) {
    $student_name = htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8'); 

    $stmt = $conn->prepare("CALL UpdateFormStatus(:student_name)");
    $stmt->bindParam(':student_name', $student_name);

    if ($stmt->execute()) {
        header("Location: MarkedAsDone.php");
        exit();
    } else {
        //error 
        echo "<script>
            alert('Failed to update status. Please try again.');
            window.location.href = 'Archive.php';
        </script>";
        exit();
    }
} else {
    // Redirect 
    header("Location: Archive.php");
    exit();
}

$conn = null; 
?>