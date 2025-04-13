<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Student Success Hub</title>
    <link rel="stylesheet" href="styles5.css">
</head>

<body>

    <header>
        <div class="logo">
            <img src="image/bsulogo.png" alt="BSU Logo" class="bsu-logo">
            <img src="image/logo.png" alt="Student Success Hub Logo" style="height: 65px;">
            Student Success Hub
        </div>
        <nav class="nav">
            <a href="HomePageForStudents.php">Home</a>
            <a href="AboutPageForStudents.php">About</a>

            <div class="dropdown">
                <a href="#" class="menu-item">Options</a>
                <div class="dropdown-content">
                    <a href="MyAccountPage.php">My Account</a>
                    <a href="LogOut.php">Log Out</a>
                </div>
            </div>
        </nav>
    </header>


    <div class="account-section">
        <div class="profile-img">
            <img src="image/profile.png" alt="Profile Icon">
        </div>

        <h1>My Account</h1>

        <div class="form-container">

            <?php
            session_start();

            include 'db_connection.php';

            if (isset($_SESSION['student_email'])) {

                $email = $_SESSION['student_email'];

                $stmt = $conn->prepare("SELECT sr_code FROM student_credentials WHERE student_email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $student = $result->fetch_assoc();

                    echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
                    echo "<p><strong>SR-Code:</strong> " . htmlspecialchars($student['sr_code']) . "</p>";
                } else {
                    echo "<p>No account details found.</p>";
                }

                $stmt->close();
            } else {
                echo "<p>Please log in to view your account details.</p>";
            }
            $conn->close();
            ?>

        </div>
    </div>

    <footer>
        <p>&copy; 2024 Student Success Hub. All rights reserved.</p>
        <a href="https://www.facebook.com/guidanceandcounselinglipa">Office of Guidance and Counseling - Batstateu Lipa (Ogc Lipa)</a>
    </footer>

</body>

</html>