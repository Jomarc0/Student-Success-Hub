<?php
session_start();
require_once 'db_connection.php'; 
require_once 'AdminClass.php'; 

$message = "";
$database = new Database();
$conn = $database->getConnection();

$admin = new AdminClass($conn);

if (isset($_GET['token'])) {
    $token = htmlspecialchars($_GET['token'], ENT_QUOTES, 'UTF-8'); 

    // Check if the token is expired
    if ($admin->isTokenExpired($token)) {
        // Handle the expired token
        if ($admin->handleExpiredToken($token)) {
            echo "<script>
                alert('Password reset link has expired. Please request a new one.');
                window.location.href = 'WelcomePage4ForgotPasswordAdmin.php';
            </script>";
            exit();
        }
    }
} else {
    echo "<script>
        alert('No token provided.');
        window.location.href = 'WelcomePage4ForgotPasswordAdmin.php';
    </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $token = filter_var($token, FILTER_SANITIZE_STRING);

    // Call the resetPassword method
    $message = $admin->resetPassword($token, $new_password, $confirm_password);

    if ($message === 'Password has been reset successfully.') {
        header("Location: WelcomePage1.php");
        exit();
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

    <script src="../js/ResetPasswordAdmin.js"></script>
</body>
</html>