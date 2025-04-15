<?php
session_start();
include 'db_connection.php';

$message = "";

if (isset($_GET['token'])) {
    $token = htmlspecialchars($_GET['token'], ENT_QUOTES, 'UTF-8'); 

    // call the stored procedure 
    $stmt = $conn->prepare("CALL GetTokenDetails(:token)");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $token_timestamp = strtotime($row['token_timestamp']);
        $current_time = time();
        $time_difference = $current_time - $token_timestamp;

        // check if the token has expired
        if ($time_difference > 300) {
            $stmt = $conn->prepare("CALL UpdateStudentPassword(:email, NULL)");
            $stmt->bindParam(':email', $row['student_email']);
            $stmt->execute();

            echo "<script>
                alert('Password reset link has expired. Please request a new one.');
                window.location.href = 'WelcomePage4ForgotPassword.php';
            </script>";
            exit();
        }
        $user_email = $row['student_email'];
    } else {
        echo "<script>
            alert('Invalid reset link.');
            window.location.href = 'WelcomePage4ForgotPassword.php';
        </script>";
        exit();
    }
} else {
    echo "<script>
        alert('No token provided.');
        window.location.href = 'WelcomePage4ForgotPassword.php';
    </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $token = filter_var($token, FILTER_SANITIZE_STRING);

    // call the stored procedure 
    $stmt = $conn->prepare("CALL GetTokenDetails(:token)");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_email = $row['student_email'];

        if (strlen($new_password) < 8 || strlen($new_password) > 255) {
            $message = 'Password must be between 8 and 255 characters long.';
        } else if ($new_password !== $confirm_password) {
            $message = 'Passwords do not match.';
        } else {
            // call the stored procedure 
            $updateStmt = $conn->prepare("CALL UpdateStudentPassword(:email, :new_password)");
            $updateStmt->bindParam(':email', $user_email);
            $updateStmt->bindParam(':new_password', $new_password);

            if ($updateStmt->execute()) {
                $message = 'Password has been reset successfully.';
                header("Location: WelcomePage1.php");
                exit();
            } else {
                $message = 'Failed to reset password. Please try again.';
            }
        }
    } else {
        $message = 'Invalid token.';
    }
}

$conn = null; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Css/styles11.css"> 
    <title>Reset Password</title>
</head>
<body>
    <div class="container">
        <h2>Reset Your Password</h2>

        <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <div class="password-requirement">
                Password must be at least 8 characters long
            </div>

            <div class="password-container">
                <input type="password" 
                    name="new_password" 
                    id="newPassword"
                    placeholder="Enter new password" 
                    minlength="8" 
                    maxlength="255" 
                    required>
                <span id="toggleNewPassword" class="password-toggle">👁️‍🗨️</span>
            </div>

            <div class="password-container">
                <input type="password" 
                    name="confirm_password" 
                    id="confirmPassword"
                    placeholder="Confirm new password" 
                    minlength="8" 
                    maxlength="255" 
                    required>
                <span id="toggleConfirmPassword" class="password-toggle">👁️‍🗨️</span>
            </div>

            <div id="password-match-message" style="color: red; display: none;">
                Passwords do not match!
            </div>

            <input type="submit" value="Reset Password" id="submit-btn">
        </form>
    </div>

    <script src="../js/ResetPassword.js"></script>
</body>
</html>