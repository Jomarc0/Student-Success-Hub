<?php

require_once 'db_connection.php';
require_once 'VerifyStudent.php';

$database = new Database();
$conn = $database->getConnection();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['student_email'])) {
    echo "Missing required parameters or not logged in";
    exit;
}

$formData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $stmt = $conn->prepare("CALL InsertStudentForm(
            :student_name,
            :student_email,
            :student_sr_code,
            :student_age,
            :date,
            :student_sex,
            :student_program_year,
            :student_mobile_number,
            :student_educational_program_reason,
            :student_easiest_subject,
            :most_difficult_subject,
            :lowest_grades_subject,
            :highest_grades_subject,
            :father_deceased,
            :father_name,
            :father_present_address,
            :father_permanent_address,
            :father_home_phone_number,
            :father_mobile_phone_number,
            :father_email,
            :father_educational_attainment,
            :father_occupation,
            :father_business_address,
            :father_business_phone,
            :father_annual_income,
            :father_languages_spoken,
            :father_religion,
            :mother_deceased,
            :mother_name,
            :mother_present_address,
            :mother_permanent_address,
            :mother_home_phone_number,
            :mother_mobile_phone_number,
            :mother_email,
            :mother_educational_attainment,
            :mother_occupation,
            :mother_business_address,
            :mother_business_phone,
            :annual_income,
            :mother_languages_spoken,
            :mother_religion,
            :parent_status,
            :guardian_name,
            :guardian_relationship,
            :guardian_address,
            :guardian_landline,
            :guardian_mobile,
            :guardian_emergency_contact_name,
            :guardian_emergency_contact_number,
            :student_curricular_program,
            :student_influential_person_name,
            :student_reason,
            :influence_relationship,
            :student_friends_in_university,
            :student_friends_outside_university,
            :student_special_interests,
            :student_special_skills,
            :student_hobbies_recreational_activities,
            :student_ambitions_goals,
            :student_guiding_principle_motto,
            :student_personal_characteristics,
            :student_significant_event_in_life,
            :student_present_concerns_problems,
            :student_present_fears,
            :student_future_expectations,
            :student_future_vision,
            :student_dreams_aspirations,
            :course_selection,
            :student_consulted_psychiatrist,
            :student_psychiatrist_sessions_count,
            :student_psychiatrist_reason,
            :student_psychiatrist_when,
            :student_consulted_psychologist,
            :student_psychologist_sessions_count,
            :student_psychologist_reason,
            :student_psychologist_when,
            :student_consulted_counselor,
            :student_counselor_sessions_count,
            :student_counselor_reason,
            :student_counselor_when,
            :student_counselor_name,
            :counselor_location,
            :tests_taken,
            :test_details,
            :medications,
            :medication_details,
            :medication_start_date,
            :medication_frequency
        )");

        // Bind parameters
        foreach ($formData as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        if ($stmt->execute()) {
            echo "<script>
                alert('Form submitted successfully! The Office of Guidance and Counseling will contact you.');
                window.location.href = 'HomePageForStudents.php';
            </script>";
        } else {
            echo "<script>
                alert('Database error occurred.');
                window.location.href = 'HomePageForStudents.php?openModal=true&form=B';
            </script>";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "<script>
            alert('An error occurred. Please try again.');
            window.location.href = 'HomePageForStudents.php?openModal=true&form=B';
        </script>";
    }
}

$conn = null; 