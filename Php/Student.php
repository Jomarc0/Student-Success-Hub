<?php

class Student {
    private $conn;
    private $loginMessage;
    private $redirectToLoader;
    private $studentEmail;
    private $hasSubmittedForm;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
        $this->loginMessage = '';
        $this->redirectToLoader = false;
        $this->studentEmail = isset($_SESSION['student_email']) ? $_SESSION['student_email'] : null;
        $this->hasSubmittedForm = false;
    }

    public function authenticate($email, $password, $recaptchaResponse) {
        // Check reCAPTCHA
        if ($this->verifyRecaptcha($recaptchaResponse)) {
            $this->checkCredentials($email, $password);
        } else {
            $this->loginMessage = "Captcha verification failed. Please try again.";
        }
    }

    private function verifyRecaptcha($response) {
        $secretKey = "6LdmmQ0rAAAAAA-eJQulDbdjXnKQoOUUrxbR7mK7";
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $response);
        $responseData = json_decode($verifyResponse);
        return $responseData->success;
    }

    private function checkCredentials($email, $password) {
        try {
            $stmt = $this->conn->prepare("CALL GetStudent(:email)");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($student) {
                if ($password === $student['student_password']) {
                    $_SESSION['student_email'] = $student['student_email'];
                    $_SESSION['student_sr_code'] = $student['sr_code'];
                    $this->redirectToLoader = true;
                } else {
                    $this->loginMessage = "Invalid password";
                    $_SESSION['last_email'] = $email;
                }
            } else {
                $this->loginMessage = "No user found with that email address";
                unset($_SESSION['last_email']);
            }
        } catch (PDOException $e) {
            $this->loginMessage = "Error fetching user: " . htmlspecialchars($e->getMessage());
        }
    }

    public function checkFormSubmission() {
        if ($this->studentEmail) {
            try {
                $stmt = $this->conn->prepare("CALL CheckFormSubmission(:student_email)");
                $stmt->bindParam(':student_email', $this->studentEmail);
                $stmt->execute();

                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $form_count = $result['count'];
                $this->hasSubmittedForm = ($form_count > 0);
                
                $stmt->closeCursor(); 
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    public function getStudentInfo() {
        if ($this->studentEmail) {
            try {
                $stmt = $this->conn->prepare("CALL GetStudentDetails(:email)");
                $stmt->bindParam(':email', $this->studentEmail);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        return null;
    }

    public function getLoginMessage() {
        return $this->loginMessage;
    }

    public function shouldRedirect() {
        return $this->redirectToLoader;
    }

    public function hasSubmittedForm() {
        return $this->hasSubmittedForm;
    }
}