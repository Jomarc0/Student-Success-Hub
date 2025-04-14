<?php
session_start();
include 'db_connection.php'; // Ensure this file uses PDO for connection

// Make sure we have a valid connection and student is logged in
if (!isset($_SESSION['student_email'])) {
    header("Location: login.php");
    exit;
}

// Store the form check result in a variable we can use multiple times
$has_submitted_form = false;

if ($conn) {
    try {
        // Prepare the stored procedure call
        $stmt = $conn->prepare("CALL CheckFormSubmission(:student_email)");
        $stmt->bindParam(':student_email', $_SESSION['student_email']);
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $form_count = $result['count'];
        $has_submitted_form = ($form_count > 0);
        
        $stmt->closeCursor(); // Close the cursor
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page - Student Success Hub</title>
    <link rel="stylesheet" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="homepage">
    <header>
        <div class="logo">
            <img src="image/bsulogo.png" alt="BSU Logo" class="bsu-logo">
            <img src="image/logo.png" alt="Student Success Hub Logo">
            <span>Student Success Hub</span>
        </div>
        <nav class="nav">
            <a href="LogOut.php" class="logout-btn">Log Out</a>
        </nav>
    </header>

    <main>
        <div class="hero-card">
            <div class="profile-section">
                <div class="profile-content">
                    <div class="profile-icon">
                        <img src="image/profile.png" alt="Profile Icon">
                    </div>
                    <div class="profile-info">
                        <h1>Welcome Red Spartan!</h1>
                        <?php
                        if (isset($_SESSION['student_email'])) {
                            $email = $_SESSION['student_email'];

                            if ($conn) {
                                $stmt = $conn->prepare("SELECT sr_code FROM student_credentials WHERE student_email = :email");
                                $stmt->bindParam(':email', $email);
                                $stmt->execute();
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($result) {
                                    echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
                                    echo "<p><strong>SR-Code:</strong> " . htmlspecialchars($result['sr_code']) . "</p>";
                                } else {
                                    echo "<p>No account details found.</p>";
                                }
                                $stmt->closeCursor();
                            }
                        } else {
                            echo "<p>Please log in to view your account details.</p>";
                        }
                        ?>
                        <?php if ($has_submitted_form): ?>
                            <button onclick="openViewModal()" class="view-form-btn">View My Form</button>
                        <?php else: ?>
                            <button onclick="openModal()" class="fill-form-btn">Fill Out Form</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h2>This is Student Success Hub</h2>
            <p>A dedicated website to cater to students' support needs with the Office of Guidance and Counselling. Our service focuses exclusively on facilitating individual interview forms, streamlining the process to ensure every student receives timely and personalized support . By simplifying access to interview forms, we aim to create a seamless experience for students while fostering a supportive environment for their academic and emotional well-being.</p>

            <h2>Frequently Asked Questions</h2>
            <div class="faq-container">
                <!-- FAQ Item 1 -->
                <div class="faq-item">
                    <div class="faq-number">01</div>
                    <div class="faq-content">
                        <h2>What can I expect during my first counseling session?</h2>
                        <p>Initial counseling sessions begin with an assessment to understand the student's needs and goals.</p>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="faq-item">
                    <div class="faq-number">02</div>
                    <div class="faq-content">
                        <h2>What services are available in the Office of Guidance and Counselling?</h2>
                        <p>The office provides support with academic, personal, and emotional challenges.</p>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="faq-item">
                    <div class="faq-number">03</div>
                    <div class="faq-content">
                        <h2>Does the counseling center provide medication management?</h2>
                        <p>The center offers guidance but does not provide medication management.</p>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="faq-item">
                    <div class="faq-number">04</div>
                    <div class="faq-content">
                        <h2>What if I'm not comfortable talking to a counselor?</h2>
                        <p>We encourage students to try counseling, but they are not obligated to continue if uncomfortable.</p>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="faq-item">
                    <div class="faq-number">05</div>
                    <div class="faq-content">
                        <h2>Can a student see a counselor even if they're not struggling mentally?</h2>
                        <p>Yes, counseling is available for academic support and general well-being.</p>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>

                <!-- FAQ Item 6 -->
                <div class="faq-item">
                    <div class="faq-number">06</div>
                    <div class="faq-content">
                        <h2>Are there any fees/charges for counseling sessions?</h2>
                        <p>No, all counseling sessions are free for students.</p>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>

                <!-- FAQ Item 7 -->
                <div class="faq-item">
                    <div class="faq-number">07</div>
                    <div class="faq-content">
                        <h2>Are counseling sessions confidential?</h2>
                        <p>Yes, all sessions are confidential unless the student poses a threat to themselves or others.</p>
                    </div>
                    <div class="toggle-icon">▼</div>
                </div>
            </div>
        </div>

        <div class="about-section">
            <h2>About Us</h2>
            <div class="about-content">
                <div class="about-text">
                    <p>The Office of Guidance and Counseling (OGC) at Batangas State University is dedicated to supporting students throughout their academic journey. Our mission is to provide comprehensive guidance services that promote personal growth, academic success, and emotional well-being.</p>
                    <p>Our team of professional counselors works tirelessly to create a safe, welcoming environment where students can freely discuss their concerns and receive the support they need to thrive in their university life.</p>
                </div>
                <div class="about-services">
                    <h3>Our Services Include:</h3>
                    <ul>
                        <li>Individual Counseling</li>
                        <li>Academic Support</li>
                        <li>Career Guidance</li>
                        <li>Personal Development Programs</li>
                        <li>Crisis Intervention</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="developers-section">
            <h2>Meet Our Developers</h2>
            <div class="developers-content">
                <div class="developer-card">
                    <div class="developer-image">
                        <img src="image/catapang.jpg" alt="Developer 1">
                    </div>
                    <div class="developer-info">
                        <h3>Ray Jomar Catapang</h3>
                        <p class="developer-role">Backend Developer</p>
                        <p class="developer-description">BSIT Student at Batangas State University</p>
                    </div>
                </div>

                <div class="developer-card">
                    <div class="developer-image">
                        <img src="image/prado.jpg" alt="Developer 2">
                    </div>
                    <div class="developer-info">
                        <h3>Angel Mae Prado</h3>
                        <p class="developer-role">Frontend Developer</p>
                        <p class="developer-description">BSIT Student at Batangas State University</p>
                    </div>
                </div>

                <div class="developer-card">
                    <div class="developer-image">
                        <img src="image/espaldon.png" alt="Developer 3">
                    </div>
                    <div class="developer-info">
                        <h3>Steven Lenard Espaldon</h3>
                        <p class="developer-role">Backend Developer</p>
                        <p class="developer-description">BSIT Student at Batangas State University</p>
                    </div>
                </div>

                <div class="developer-card">
                    <div class="developer-image">
                        <img src="image/sandoval.jpg" alt="Developer 4">
                    </div>
                    <div class="developer-info">
                        <h3>Manuel Sandoval</h3>
                        <p class="developer-role">UI/UX Designer</p>
                        <p class="developer-description">BSIT Student at Batangas State University</p>
                    </div>
                </div>

                <div class="developer-card">
                    <div class="developer-image">
                        <img src="image/andor.png" alt="Developer 5">
                    </div>
                    <div class="developer-info">
                        <h3>Cedrick Andor</h3>
                        <p class="developer-role">Database Developer</p>
                        <p class="developer-description">BSIT Student at Batangas State University</p>
                    </div>
                </div>

                <div class="developer-card">
                    <div class="developer-image">
                        <img src="image/fajilan.png" alt="Developer 6">
                    </div>
                    <div class="developer-info">
                        <h3>Mark Justin Fajilan</h3>
                        <p class="developer-role">Security Specialist</p>
                        <p class="developer-description">BSIT Student at Batangas State University</p>
                    </div>
                </div>

                <div class="developer-card">
                    <div class="developer-image">
                        <img src="image/cuevas.jpg" alt="Developer 7">
                    </div>
                    <div class="developer-info">
                        <h3>Rovic Cuevas</h3>
                        <p class="developer-role">Testing Engineer</p>
                        <p class="developer-description">BSIT Student at Batangas State University</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update the modal structure -->
        <div id="formModal" class="modal">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <div id="modalContent"></div>
            </div>
        </div>

        <!-- Add new view form modal -->
        <div id="viewFormModal" class="modal">
            <div class="modal-content">
                <span class="close-modal" onclick="closeViewModal()">&times;</span>
                <div id="viewModalContent"></div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Student Success Hub. All rights reserved.</p>
        <a href="https://www.facebook.com/guidanceandcounselinglipa">Office of Guidance and Counseling - Batstateu Lipa (Ogc Lipa) Facebook Page<br></a>
        <p>Email: ogc.lipa@g.batstate-u.edu.ph</p>
    </footer>

    <script src="js/homepageforStudent.js"></script>
</body>

</html>