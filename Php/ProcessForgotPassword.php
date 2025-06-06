<?php
session_start();

require_once 'db_connection.php'; 

$database = new Database();
$conn = $database->getConnection();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '../vendor/autoload.php';

function sendResetEmail($email, $token) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                       
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'nevetsespaldon@gmail.com';       
        $mail->Password   = 'crjjijhpdsqpdzjs';                
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
        $mail->Port       = 587;                                    

        $mail->setFrom('nevetsespaldon@gmail.com', 'Student Success Hub'); 
        $mail->addAddress($email);                                          
        $mail->Subject = 'Password Reset Request';
        $resetLink = "http://localhost/student_success_hub/ResetPassword.php?token=" . urlencode($token);
        $mail->Body = "Hi $email, <br><br>We received your request for a password change. To reset your password, kindly click the link within five minutes: <a href='$resetLink'>Reset Password Link</a><br><br>If you didn't request this link, someone else might have typed your email address by mistake.<br><br>Please note that this reset link expires in 5 minutes.<br><br>Thanks.";
        $mail->isHTML(true);                                                   

        $mail->SMTPDebug = 2;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "<script>
            alert('Mailer Error: " . addslashes($mail->ErrorInfo) . "');
            window.location.href = 'WelcomePage4ForgotPassword.php';
        </script>";
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("CALL GetStudentByEmail(:email)");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $token = bin2hex(random_bytes(16));
            $timestamp = date('Y-m-d H:i:s');

            $updateStmt = $conn->prepare("CALL UpdateResetToken(:email, :token, :timestamp)");
            $updateStmt->bindParam(':email', $email);
            $updateStmt->bindParam(':token', $token);
            $updateStmt->bindParam(':timestamp', $timestamp);

            if ($updateStmt->execute()) {
                if (sendResetEmail($email, $token)) {
                    header("Location: ForgotPasswordHandlerStudents.php");
                    exit();
                } else {
                    echo "<script>
                        alert('Message could not be sent. Please try again later.');
                        window.location.href = 'WelcomePage4ForgotPassword.php';
                    </script>";
                }
            } else {
                echo "<script>
                    alert('Database error: Could not update reset token.');
                    window.location.href = 'WelcomePage4ForgotPassword.php';
                </script>";
            }
        } else {
            echo "<script>
                alert('No account found with that email address.');
                window.location.href = 'WelcomePage4ForgotPassword.php';
            </script>";
        }
        $stmt->closeCursor();
    } else {
        echo "<script>
            alert('Invalid email format.');
            window.location.href = 'WelcomePage4ForgotPassword.php';
        </script>";
    }
}

$conn = null; 
?>