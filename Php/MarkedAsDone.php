<?php
session_start();
require_once 'db_connection.php'; 
require_once 'AdminClass.php'; 

$database = new Database();
$conn = $database->getConnection();
$studentManager = new AdminClass($conn);

if (isset($_GET['action']) && isset($_GET['name'])) {
    $student_name = $_GET['name'];
    $status = ($_GET['action'] == 'mark_done') ? 'done' : 'pending';

    if ($studentManager->updateStudentStatus($student_name, $status)) {
        echo "<script>alert('Student record updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating record.');</script>";
    }

    if (isset($_GET['redirect']) && $_GET['redirect'] === 'viewlogs') {
        header("Location: ViewLogs.php");
        exit();
    }
}

// Count student records
$counts = $studentManager->countStudentRecords();
$pending_count = $counts['pending_count'];
$done_count = $counts['done_count'];

// Get done students
$doneStudents = $studentManager->getDoneStudents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marked as Done Records - Student Success Hub</title>
    <link rel="stylesheet" href="../Css/styles12.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../image/bsulogo.png" alt="BSU Logo" class="bsu-logo">
            <img src="../image/logo.png" alt="Student Success Hub Logo">
            <span>Student Success Hub</span>
        </div>
        <nav class="nav">
            <a href="HomePageForAdmin.php" class="logout-btn">Home</a>
            <a href="LogOut.php" class="logout-btn">Log Out</a>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <h2 class="section-header">Marked as Done Records</h2>

            <div class="search-container">
                <input type="text" id="search-input" class="search-input" placeholder="Type SR Code , Name, or Date">
                <label for="search-input" class="search-label">Search</label>
            </div>

            <div class="counts-container">
                <div class="total-done-logs">Total Done Logs: <?php echo $done_count; ?></div>
                <button onclick="confirmArchiveAll()" class="archive-all-btn">ARCHIVE ALL</button>
            </div>

            <div class="form-row">
                <?php
                if (empty($doneStudents)) {
                    echo "<div class='no-records'>";
                    echo "<p>No records marked as done yet.</p>";
                    echo "</div>";
                } else {
                    echo "<div class='info-container'>";
                    echo "<div class='info-item header'><span>Student Name</span><span>SR - Code</span><span>Submission Date</span></div>";
                    foreach ($doneStudents as $row) {
                        echo "<div class='info-item' data-name='" . htmlspecialchars($row['student_name']) . "' data-date='" . htmlspecialchars($row['date']) . "' data-sr-code='" . htmlspecialchars($row['student_sr_code']) . "'>";
                        echo "<a href='ViewSpecificLog.php?name=" . htmlspecialchars($row['student_name']) . "' class='name-link'>" . htmlspecialchars($row['student_name']) . "</a>";
                        echo "<span class='sr-code'>" . htmlspecialchars($row['student_sr_code']) . "</span>";
                        echo "<span class='submission-date'>" . htmlspecialchars($row['date']) . "</span>";
                        echo "</div><hr class='separator'>";
                    }
                    echo "</div>";
                }
                ?>
            </div>

            <div class="button-container">
                <a href="Archive.php" class="proceed-btn">ARCHIVE</a>
                <a href="ViewLogs.php" class="proceed-btn">GO BACK</a>
            </div>

        </div>

        <footer>
            <p>&copy; 2024 Student Success Hub. All rights reserved.</p>
            <a href="https://www.facebook.com/guidanceandcounselinglipa">Office of Guidance and Counseling - Batstateu Lipa (Ogc Lipa) Facebook Page<br></a>
            <p>Email: ogc.lipa@g.batstate-u.edu.ph</p>
        </footer>

    </main>

    <script src="../js/MakedAsDone.js"></script>
</body>
</html>