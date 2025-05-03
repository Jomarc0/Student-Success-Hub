<?php
session_start();
require_once 'db_connection.php';

$database = new Database();
$conn = $database->getConnection();

if (!isset($_SESSION['student_email'])) {
    echo "error_not_logged_in";
    exit;
}

$logged_in_email = $_SESSION['student_email'];

// echo "<!-- Checking form for email: " . $logged_in_email . " -->";
try {
    // stored procedure to check form submission
    $stmt = $pdo->prepare("CALL CheckStudentFormSubmission(:student_email)");
    $stmt->execute(['student_email' => $logged_in_email]);
    $form_count = $stmt->fetchColumn(); // fetch submitted forms
    $stmt->closeCursor(); 

    if ($form_count > 0) {
        echo "error_already_submitted";
        exit;
    }

} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<div class="stepper">
    <div class="step active">A</div>
    <div class="bridge"></div>
    <div class="step">B</div>
</div>

<div class="form-content">
    <div class="welcome-header">
        <h1>Welcome to Student Success Hub!</h1>
    </div>

    <div class="profile-img">
        <img src="../image/profile.png" alt="Profile Icon">
    </div>

    <div class="some-text">
        <p>Ms. Maria Lourdes G. Balita, MPSyc, RPm, LPT OGC OIC-Head</p>
    </div>
</div>

<div class="button-container">
    <button type="button" class="next-btn" onclick="loadFormContent('FillingOutB.php')">NEXT</button>
</div>

<script src="../js/fillOutA.js"></script>