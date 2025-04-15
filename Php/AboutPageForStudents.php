<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Student Success Hub</title>
    <link rel="stylesheet" href="../Css/styles9.css">
</head>

<body>

    <header>
        <div class="logo">
            <img src="../image/bsulogo.png" alt="BSU Logo" class="bsu-logo">
            <img src="../image/logo.png" alt="Student Success Hub Logo" style="height: 65px;">
            Student Success Hub
        </div>
        <nav class="nav">
            <a href="HomePageForStudents.php">Home</a>
            <a href="AboutPageForStudents.php" class="active">About</a>

            <div class="dropdown">
                <a href="#" class="menu-item">Options</a>
                <div class="dropdown-content">
                    <a href="MyAccountPage.php">My Account</a>
                    <a href="LogOut.php">Log Out</a>
                </div>
            </div>

        </nav>
    </header>

    <div class="faq-container">
        <div class="faq-item" onclick="toggleFAQ(this)">
            <div class="faq-number faq-number-inactive">01</div>
            <h2>What can I expect during my first counseling session?</h2>
            <p>Initial counseling sessions begin with an assessment to understand the student’s needs and goals.</p>
            <div class="toggle-icon">▼</div>
        </div>

        <div class="faq-item" onclick="toggleFAQ(this)">
            <div class="faq-number faq-number-inactive">02</div>
            <h2>What services are available in the Office of Guidance and Counselling?</h2>
            <p>The office provides support with academic, personal, and emotional challenges.</p>
            <div class="toggle-icon">▼</div>
        </div>

        <div class="faq-item" onclick="toggleFAQ(this)">
            <div class="faq-number faq-number-inactive">03</div>
            <h2>Does the counseling center provide medication management?</h2>
            <p>The center offers guidance but does not provide medication management.</p>
            <div class="toggle-icon">▼</div>
        </div>

        <div class="faq-item" onclick="toggleFAQ(this)">
            <div class="faq-number faq-number-inactive">04</div>
            <h2>What if I'm not comfortable talking to a counselor?</h2>
            <p>We encourage students to try counseling, but they are not obligated to continue if uncomfortable.</p>
            <div class="toggle-icon">▼</div>
        </div>

        <div class="faq-item" onclick="toggleFAQ(this)">
            <div class="faq-number faq-number-inactive">05</div>
            <h2>Can a student see a counselor even if they’re not struggling mentally?</h2>
            <p>Yes, counseling is available for academic support and general well-being.</p>
            <div class="toggle-icon">▼</div>
        </div>

        <div class="faq-item" onclick="toggleFAQ(this)">
            <div class="faq-number faq-number-inactive">06</div>
            <h2>Are there any fees/charges for counseling sessions?</h2>
            <p>No, all counseling sessions are free for students.</p>
            <div class="toggle-icon">▼</div>
        </div>

        <div class="faq-item" onclick="toggleFAQ(this)">
            <div class="faq-number faq-number-inactive">07</div>
            <h2>Are counseling sessions confidential?</h2>
            <p>Yes, all sessions are confidential unless the student poses a threat to themselves or others.</p>
            <div class="toggle-icon">▼</div>
        </div>
    </div>

    <script src="../js/aboutPage.js"></script>

    <footer>
        <p>&copy; 2024 Student Success Hub. All rights reserved.</p>
        <a href="https://www.facebook.com/guidanceandcounselinglipa">Office of Guidance and Counseling - Batstateu Lipa (Ogc Lipa)</a>
    </footer>
    
</body>

</html>