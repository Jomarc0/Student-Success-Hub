<?php

class AdminClass {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function login($email, $password, $captchaResponse) {
        $loginMessage = '';
        $redirectToLoader = false;

        // recaptcha verification
        if (!empty($captchaResponse)) {
            $secretKey = "6LdmmQ0rAAAAAA-eJQulDbdjXnKQoOUUrxbR7mK7";
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $captchaResponse);
            $response = json_decode($verifyResponse);

            // check if captcha was successful
            if ($response->success) {
                try {
                    $stmt = $this->conn->prepare("CALL GetAdmin(:email)");
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($admin) {
                        if ($password === $admin['admin_password']) {
                            $_SESSION['admin_email'] = $admin['admin_email'];
                            $redirectToLoader = true;
                        } else {
                            $loginMessage = "Invalid password";
                            $_SESSION['last_email'] = $email;
                        }
                    } else {
                        $loginMessage = "No user found with that email address";
                        unset($_SESSION['last_email']);
                    }
                } catch (PDOException $e) {
                    $loginMessage = "Error fetching user: " . htmlspecialchars($e->getMessage());
                }
            } else {
                $loginMessage = "Captcha verification failed. Please try again.";
            }
        } else {
            $loginMessage = "Please complete the Captcha.";
        }

        return [$loginMessage, $redirectToLoader];
    }

    public function getPendingLogs() {
        try {
            $stmt = $this->conn->prepare("CALL GetPendingLogs()");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error executing query: " . $e->getMessage());
        }
    }
    
    public function updateFormStatus($student_name) {
        $student_name = htmlspecialchars($student_name, ENT_QUOTES, 'UTF-8');

        try {
            $stmt = $this->conn->prepare("CALL UpdateFormStatus(:student_name)");
            $stmt->bindParam(':student_name', $student_name);

            if ($stmt->execute()) {
                return true; 
            } else {
                return false; 
            }
        } catch (PDOException $e) {

            echo "<script>
                alert('Failed to update status: " . htmlspecialchars($e->getMessage()) . "');
                window.location.href = 'Archive.php';
            </script>";
            exit();
        }
    }

    public function getArchivedForms($status = 'archived') {
        try {
            $count = $this->conn->prepare("CALL CountArchivedForms(:status)");
            $count->execute(['status' => $status]);
            $archived_count = $count->fetchColumn();
            $count->closeCursor();

            $stmt = $this->conn->prepare("CALL GetArchivedForms(:status)");
            $stmt->execute(['status' => $status]);

            $archived_forms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return [$archived_count, $archived_forms];
        } catch (PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    }

    public function updateStudentStatus($student_name, $status) {
        $update_stmt = $this->conn->prepare("CALL UpdateStudentStatus(:student_name, :status)");
        $update_stmt->bindParam(':student_name', $student_name);
        $update_stmt->bindParam(':status', $status);

        if ($update_stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function countStudentRecords() {
        $count_stmt = $this->conn->prepare("CALL CountStudentRecords(@pending_count, @done_count)");
        $count_stmt->execute();
        $pending_count = $this->conn->query("SELECT @pending_count AS pending_count")->fetch(PDO::FETCH_ASSOC)['pending_count'];
        $done_count = $this->conn->query("SELECT @done_count AS done_count")->fetch(PDO::FETCH_ASSOC)['done_count'];

        return ['pending_count' => $pending_count, 'done_count' => $done_count];
    }

    public function getDoneStudents() {
        $stmt = $this->conn->prepare("CALL GetDoneStudents()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdminTokenDetails($token) {
        $stmt = $this->conn->prepare("CALL GetAdminTokenDetails(:token)");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function resetPassword($token, $new_password, $confirm_password) {
        // password length
        if (strlen($new_password) < 8 || strlen($new_password) > 255) {
            return 'Password must be between 8 and 255 characters long.';
        }

        if ($new_password !== $confirm_password) {
            return 'Passwords do not match.';
        }

        //admin email from token
        $email = $this->getEmailByToken($token);
        if ($email) {
            $stmt = $this->conn->prepare("CALL UpdateAdminPassword(:email, :new_password)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':new_password', $new_password);

            if ($stmt->execute()) {
                return 'Password has been reset successfully.';
            } else {
                return 'Failed to reset password. Please try again.';
            }
        } else {
            return 'Invalid token.';
        }
    }

    private function getEmailByToken($token) {
        $stmt = $this->conn->prepare("CALL GetAdminTokenDetails(:token)");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['admin_email'] ?? null;
    }

    public function isTokenExpired($token) {
        $row = $this->getAdminTokenDetails($token);
        if ($row) {
            $token_timestamp = strtotime($row['token_timestamp']);
            $current_time = time();
            return ($current_time - $token_timestamp) > 300; // 300 seconds = 5 minutes
        }
        return true; 
    }

    public function handleExpiredToken($token) {
        $email = $this->getEmailByToken($token);
        if ($email) {
            $stmt = $this->conn->prepare("CALL UpdateAdminPassword(:email, NULL)");
            $stmt->bindParam(':email', $email);
            return $stmt->execute();
        }
        return false; 
    }

    public function archiveDoneRecords() {
        try {
            $stmt = $this->conn->prepare("CALL ArchiveDoneRecords()");
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error executing stored procedure: " . $e->getMessage());
        }
    }

    public function getStudentDetails($student_name) {
        try {
            $stmt = $this->conn->prepare("CALL GetStudentName(:student_name)");
            $stmt->bindParam(':student_name', $student_name);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching student details: " . htmlspecialchars($e->getMessage());
            exit();
        }
    }
    
    public function deleteStudentRecord($student_name) {
        try {
            $delete_stmt = $this->conn->prepare("CALL DeleteStudentRecord(:student_name)");
            $delete_stmt->bindParam(':student_name', $student_name);
            return $delete_stmt->execute();
        } catch (PDOException $e) {
            echo "Error deleting record: " . htmlspecialchars($e->getMessage());
            exit();
        }
    }

    public function __destruct() {
        $this->conn = null;
    }

}
?>