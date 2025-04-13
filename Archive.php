<?php
session_start();
include 'db_connection.php';

$archived_query = "SELECT COUNT(*) as archived_count FROM form WHERE status = ?";
$archived_stmt = $conn->prepare($archived_query);
$status = 'archived';
$archived_stmt->bind_param("s", $status);
$archived_stmt->execute();
$archived_result = $archived_stmt->get_result();
$archived_count = $archived_result->fetch_assoc()['archived_count'];
$archived_stmt->close(); 

// Query to select archived forms
$query = "SELECT student_name, student_sr_code, date, status 
          FROM form
          WHERE status = ?
          ORDER BY date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $status);
$stmt->execute();
$result = $stmt->get_result();

// Fetching results
$archived_forms = [];
while ($row = $result->fetch_assoc()) {
    $archived_forms[] = $row; // Store each row in an array
}

$stmt->close(); 
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Records - Student Success Hub</title>
    <link rel="stylesheet" href="styles12.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="image/bsulogo.png" alt="BSU Logo" class="bsu-logo">
            <img src="image/logo.png" alt="Student Success Hub Logo">
            <span>Student Success Hub</span>
        </div>
        <nav class="nav">
            <a href="HomePageForAdmin.php" class="logout-btn">Home</a>
            <a href="LogOut.php" class="logout-btn">Log Out</a>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2 class="section-header">Archived Records</h2>

            <div class="search-container">
                <input type="text" id="search-input" class="search-input" placeholder="Type SR Code, Name, or Date">
                <label for="search-input" class="search-label">Search</label>
            </div>

            <div class="counts-container">
                <div class="total-done-logs">Total Archived Logs: <?php echo $archived_count; ?></div>
            </div>

            <div class="form-row">
                <?php
                if (!$result) {
                    echo "Error executing query: " . $conn->error;
                } else {
                    if (count($archived_forms) > 0) {
                        echo "<div class='info-container'>";
                        echo "<div class='info-item header'><span>Student Name</span><span>SR - Code</span><span>Submission Date</span></div>";
                        foreach ($archived_forms as $row) {
                            echo "<div class='info-item' data-name='" . htmlspecialchars($row['student_name']) . "' data-date='" . htmlspecialchars($row['date']) . "' data-sr-code='" . htmlspecialchars($row['student_sr_code']) . "'>";
                            echo "<a href='ViewSpecificLog.php?name=" . htmlspecialchars($row['student_name']) . "' class='name-link'>" . htmlspecialchars($row['student_name']) . "</a>";
                            echo "<span class='sr-code'>" . htmlspecialchars($row['student_sr_code']) . "</span>";
                            echo "<span class='submission-date'>" . htmlspecialchars($row['date']) . "</span>";
                            echo "</div><hr class='separator'>";
                        }
                        echo "</div>";
                    } else {
                        echo "<div class='no-records'>";
                        echo "<p>No archived records yet.</p>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
            <div class="button-container">
                <a href="MarkedAsDone.php" class="proceed-btn">GO BACK</a>
            </div>
        </div>

        <footer>
            <p>&copy; 2024 Student Success Hub. All rights reserved.</p>
            <a href="https://www.facebook.com/guidanceandcounselinglipa">Office of Guidance and Counseling - Batstateu Lipa (Ogc Lipa) Facebook Page<br></a>
            <p>Email: ogc.lipa@g.batstate-u.edu.ph</p>
        </footer>

    </main>

    <script src="js/archive.js"></script>
</body>

</html>
