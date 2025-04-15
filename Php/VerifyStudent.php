<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function verifyStudentCredentials($conn, $form_email, $form_sr_code)
{
    if (!isset($_SESSION['student_email'])) {
        return "not_logged_in";
    }

    $stmt = $conn->prepare("CALL VerifyStudentCredentials(?, ?, @status)");
    $stmt->bindParam(1, $form_email);
    $stmt->bindParam(2, $form_sr_code);
    $stmt->execute();

    $result = $conn->query("SELECT @status AS status");
    $row = $result->fetch(PDO::FETCH_ASSOC);

    return $row['status'];
}