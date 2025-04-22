<?php

session_start();

require_once 'db_connection.php';
require_once 'Student.php'; 

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logIn'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $login = new Student($conn);
    $login->authenticate($email, $password, $recaptchaResponse);

    if ($login->shouldRedirect()) {
        header("Location: loader2.php?redirect=HomePageForStudents.php");
        exit();
    }

    $loginMessage = $login->getLoginMessage();
    $conn = null; 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - Student Success Hub</title>
    <link rel="stylesheet" href="../Css/styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="../image/bsulogo.png" alt="Connect Spartans Logo" class="logo">
        </div>

        <div class="stepper">
            <div class="step">1</div>
            <div class="bridge"></div>
            <div class="step">2</div>
            <div class="bridge"></div>
            <div class="step active">3</div>
        </div>

        <h1>Student Log in</h1>

        <form method="post" id="loginForm">
            <input type="email"
                name="email"
                placeholder="Enter email address"
                value="<?php echo isset($_SESSION['last_email']) ? htmlspecialchars($_SESSION['last_email']) : ''; ?>"
                required>
            <div class="password-container">
                <input type="password" name="password" id="passwordField" placeholder="Enter password" required>
                <span id="togglePassword" class="password-toggle">👁️‍🗨️</span>
            </div>

            <?php if (isset($loginMessage) && $loginMessage): ?>
                <div class="error-message">
                    <span class="error-icon">⚠️</span>
                    <?php echo $loginMessage; ?>
                </div>
            <?php endif; ?>

            <div class="password-container">
                <div class="g-recaptcha" data-sitekey="6LdmmQ0rAAAAAL5H7ZJsfjoOReF9gPLLzgtWHIkZ"></div>
            </div>

            <div class="proceed-selection">
                <button type="submit" name="logIn" value="First proceed" class="proceed-btn">LOG IN</button>
            </div>

            <a href="WelcomePage4ForgotPassword.php">Forgot Password?</a>
        </form>
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('passwordField');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                this.textContent = '👁️';
            } else {
                passwordField.type = 'password';
                this.textContent = '👁️‍🗨️';
            }
        });
    </script>
</body>
</html>