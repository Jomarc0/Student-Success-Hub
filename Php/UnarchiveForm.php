<?php
session_start();

require_once 'db_connection.php';
require_once 'AdminClass.php'; 

$database = new Database();
$conn = $database->getConnection();
$admin = new AdminClass($conn);

if (isset($_GET['name'])) {
    $student_name = $_GET['name']; 

    if ($admin->updateFormStatus($student_name)) {
        header("Location: MarkedAsDone.php");
        exit();
    } else {
        echo "<script>
            alert('Failed to update status. Please try again.');
            window.location.href = 'Archive.php';
        </script>";
        exit();
    }
} else {
    header("Location: Archive.php");
    exit();
}
?>