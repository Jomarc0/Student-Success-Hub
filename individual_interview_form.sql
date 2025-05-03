-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 04:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `individual_interview_form`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ArchiveDoneRecords` ()   BEGIN
    UPDATE form SET status = 'archived' WHERE status = 'done';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CheckDuplicateEmail` (IN `student_email` VARCHAR(255))   BEGIN
    SELECT student_email 
    FROM student_credentials 
    WHERE student_email = student_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CheckFormSubmission` (IN `p_student_email` VARCHAR(255))   BEGIN
    SELECT COUNT(*) AS count FROM form WHERE student_email = p_student_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CheckStudentFormSubmission` (IN `student_email` VARCHAR(255))   BEGIN
    SELECT COUNT(*) AS count 
    FROM form 
    WHERE student_email = student_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CountArchivedForms` (IN `status` VARCHAR(255))   BEGIN
    SELECT COUNT(*) AS archived_count 
    FROM form 
    WHERE status = status;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CountStudentRecords` (OUT `p_pending_count` INT, OUT `p_done_count` INT)   BEGIN
    SELECT COUNT(*) INTO p_pending_count FROM form WHERE status = 'pending';
    SELECT COUNT(*) INTO p_done_count FROM form WHERE status = 'done';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteStudentRecord` (IN `p_student_name` VARCHAR(255))   BEGIN
    DELETE FROM form WHERE student_name = p_student_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAdmin` (IN `email` VARCHAR(255))   BEGIN
    SELECT * FROM admin_credential WHERE admin_email = email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAdminByEmail` (IN `p_email` VARCHAR(255))   BEGIN
    SELECT * FROM admin_credential WHERE admin_email = p_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAdminTokenDetails` (IN `p_token` VARCHAR(255))   BEGIN
    SELECT token_timestamp, admin_email FROM admin_credential WHERE reset_token = p_token;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetArchivedForms` (IN `status` VARCHAR(255))   BEGIN
    SELECT student_name, student_sr_code, date, status 
    FROM form
    WHERE status = status
    ORDER BY date DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetDoneStudents` ()   BEGIN
    SELECT student_name, student_sr_code, date, status 
    FROM form
    WHERE status = 'done'
    ORDER BY date DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetPendingLogs` ()   BEGIN
    SELECT student_name, student_sr_code, date 
    FROM form
    WHERE status = 'pending'
    ORDER BY date DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetStudent` (IN `email` VARCHAR(255))   BEGIN
    SELECT student_email, sr_code, student_password FROM student_credentials WHERE student_email = email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetStudentByEmail` (IN `p_email` VARCHAR(255))   BEGIN
    SELECT * FROM student_credentials WHERE student_email = p_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetStudentCredentials` ()   BEGIN
    SELECT student_email, student_password, reset_token, sr_code, admin_id, token_timestamp 
    FROM student_credentials;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetStudentDetails` (IN `student_email` VARCHAR(255))   BEGIN
    SELECT sr_code 
    FROM student_credentials 
    WHERE student_email = student_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetStudentFormData` (IN `student_email` VARCHAR(255))   BEGIN
    SELECT * FROM form WHERE student_email = student_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetStudentName` (IN `student_name` VARCHAR(255))   BEGIN
    SELECT * FROM form WHERE student_name = student_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetStudentStatus` (IN `student_name` VARCHAR(255))   BEGIN
    SELECT status FROM form WHERE student_name = student_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetTokenDetails` (IN `p_token` VARCHAR(255))   BEGIN
    SELECT token_timestamp, student_email FROM student_credentials WHERE reset_token = p_token;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertStudentCredentials` (IN `p_student_email` VARCHAR(255), IN `p_student_password` VARCHAR(255), IN `p_reset_token` VARCHAR(255), IN `p_sr_code` VARCHAR(255), IN `p_admin_id` VARCHAR(255), IN `p_token_timestamp` DATETIME)   BEGIN
    INSERT INTO student_credentials (student_email, student_password, reset_token, sr_code, admin_id, token_timestamp)
    VALUES (p_student_email, p_student_password, p_reset_token, p_sr_code, p_admin_id, p_token_timestamp);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertStudentForm` (IN `p_student_name` VARCHAR(255), IN `p_student_email` VARCHAR(255), IN `p_student_sr_code` VARCHAR(255), IN `p_student_age` INT, IN `p_date` DATE, IN `p_student_sex` VARCHAR(10), IN `p_student_program_year` VARCHAR(50), IN `p_student_mobile_number` VARCHAR(20), IN `p_student_educational_program_reason` TEXT, IN `p_student_easiest_subject` VARCHAR(255), IN `p_most_difficult_subject` VARCHAR(255), IN `p_lowest_grades_subject` VARCHAR(255), IN `p_highest_grades_subject` VARCHAR(255), IN `p_father_deceased` TINYINT, IN `p_father_name` VARCHAR(255), IN `p_father_present_address` TEXT, IN `p_father_permanent_address` TEXT, IN `p_father_home_phone_number` VARCHAR(20), IN `p_father_mobile_phone_number` VARCHAR(20), IN `p_father_email` VARCHAR(255), IN `p_father_educational_attainment` VARCHAR(255), IN `p_father_occupation` VARCHAR(255), IN `p_father_business_address` TEXT, IN `p_father_business_phone` VARCHAR(20), IN `p_father_annual_income` DECIMAL(10,2), IN `p_father_languages_spoken` TEXT, IN `p_father_religion` VARCHAR(255), IN `p_mother_deceased` TINYINT, IN `p_mother_name` VARCHAR(255), IN `p_mother_present_address` TEXT, IN `p_mother_permanent_address` TEXT, IN `p_mother_home_phone_number` VARCHAR(20), IN `p_mother_mobile_phone_number` VARCHAR(20), IN `p_mother_email` VARCHAR(255), IN `p_mother_educational_attainment` VARCHAR(255), IN `p_mother_occupation` VARCHAR(255), IN `p_mother_business_address` TEXT, IN `p_mother_business_phone` VARCHAR(20), IN `p_annual_income` DECIMAL(10,2), IN `p_mother_languages_spoken` TEXT, IN `p_mother_religion` VARCHAR(255), IN `p_parent_status` TEXT, IN `p_guardian_name` VARCHAR(255), IN `p_guardian_relationship` VARCHAR(255), IN `p_guardian_address` TEXT, IN `p_guardian_landline` VARCHAR(20), IN `p_guardian_mobile` VARCHAR(20), IN `p_guardian_emergency_contact_name` VARCHAR(255), IN `p_guardian_emergency_contact_number` VARCHAR(20), IN `p_student_curricular_program` VARCHAR(255), IN `p_student_influential_person_name` VARCHAR(255), IN `p_student_reason` TEXT, IN `p_influence_relationship` TEXT, IN `p_student_friends_in_university` TEXT, IN `p_student_friends_outside_university` TEXT, IN `p_student_special_interests` TEXT, IN `p_student_special_skills` TEXT, IN `p_student_hobbies_recreational_activities` TEXT, IN `p_student_ambitions_goals` TEXT, IN `p_student_guiding_principle_motto` TEXT, IN `p_student_personal_characteristics` TEXT, IN `p_student_significant_event_in_life` TEXT, IN `p_student_present_concerns_problems` TEXT, IN `p_student_present_fears` TEXT, IN `p_student_future_expectations` TEXT, IN `p_student_future_vision` TEXT, IN `p_student_dreams_aspirations` TEXT, IN `p_course_selection` TEXT, IN `p_student_consulted_psychiatrist` TINYINT, IN `p_student_psychiatrist_sessions_count` INT, IN `p_student_psychiatrist_reason` TEXT, IN `p_student_psychiatrist_when` DATE, IN `p_student_consulted_psychologist` TINYINT, IN `p_student_psychologist_sessions_count` INT, IN `p_student_psychologist_reason` TEXT, IN `p_student_psychologist_when` DATE, IN `p_student_consulted_counselor` TINYINT, IN `p_student_counselor_sessions_count` INT, IN `p_student_counselor_reason` TEXT, IN `p_student_counselor_when` DATE, IN `p_student_counselor_name` VARCHAR(255), IN `p_counselor_location` VARCHAR(255), IN `p_tests_taken` TEXT, IN `p_test_details` TEXT, IN `p_medications` TEXT, IN `p_medication_details` TEXT, IN `p_medication_start_date` DATE, IN `p_medication_frequency` VARCHAR(50))   BEGIN
    INSERT INTO form (
        student_name,
        student_email,
        student_sr_code,
        student_age,
        date,
        student_sex,
        student_program_year,
        student_mobile_number,
        student_educational_program_reason,
        student_easiest_subject,
        most_difficult_subject,
        lowest_grades_subject,
        highest_grades_subject,
        father_deceased,
        father_name,
        father_present_address,
        father_permanent_address,
        father_home_phone_number,
        father_mobile_phone_number,
        father_email,
        father_educational_attainment,
        father_occupation,
        father_business_address,
        father_business_phone,
        father_annual_income,
        father_languages_spoken,
        father_religion,
        mother_deceased,
        mother_name,
        mother_present_address,
        mother_permanent_address,
        mother_home_phone_number,
        mother_mobile_phone_number,
        mother_email,
        mother_educational_attainment,
        mother_occupation,
        mother_business_address,
        mother_business_phone,
        annual_income,
        mother_languages_spoken,
        mother_religion,
        parent_status,
        guardian_name,
        guardian_relationship,
        guardian_address,
        guardian_landline,
        guardian_mobile,
        guardian_emergency_contact_name,
        guardian_emergency_contact_number,
        student_curricular_program,
        student_influential_person_name,
        student_reason,
        influence_relationship,
        student_friends_in_university,
        student_friends_outside_university,
        student_special_interests,
        student_special_skills,
        student_hobbies_recreational_activities,
        student_ambitions_goals,
        student_guiding_principle_motto,
        student_personal_characteristics,
        student_significant_event_in_life,
        student_present_concerns_problems,
        student_present_fears,
        student_future_expectations,
        student_future_vision,
        student_dreams_aspirations,
        course_selection,
        student_consulted_psychiatrist,
        student_psychiatrist_sessions_count,
        student_psychiatrist_reason,
        student_psychiatrist_when,
        student_consulted_psychologist,
        student_psychologist_sessions_count,
        student_psychologist_reason,
        student_psychologist_when,
        student_consulted_counselor,
        student_counselor_sessions_count,
        student_counselor_reason,
        student_counselor_when,
        student_counselor_name,
        counselor_location,
        tests_taken,
        test_details,
        medications,
        medication_details,
        medication_start_date,
        medication_frequency
    ) VALUES (
        p_student_name,
        p_student_email,
        p_student_sr_code,
        p_student_age,
        p_date,
        p_student_sex,
        p_student_program_year,
        p_student_mobile_number,
        p_student_educational_program_reason,
        p_student_easiest_subject,
        p_most_difficult_subject,
        p_lowest_grades_subject,
        p_highest_grades_subject,
        p_father_deceased,
        p_father_name,
        p_father_present_address,
        p_father_permanent_address,
        p_father_home_phone_number,
        p_father_mobile_phone_number,
        p_father_email,
        p_father_educational_attainment,
        p_father_occupation,
        p_father_business_address,
        p_father_business_phone,
        p_father_annual_income,
        p_father_languages_spoken,
        p_father_religion,
        p_mother_deceased,
        p_mother_name,
        p_mother_present_address,
        p_mother_permanent_address,
        p_mother_home_phone_number,
        p_mother_mobile_phone_number,
        p_mother_email,
        p_mother_educational_attainment,
        p_mother_occupation,
        p_mother_business_address,
        p_mother_business_phone,
        p_annual_income,
        p_mother_languages_spoken,
        p_mother_religion,
        p_parent_status,
        p_guardian_name,
        p_guardian_relationship,
        p_guardian_address,
        p_guardian_landline,
        p_guardian_mobile,
        p_guardian_emergency_contact_name,
        p_guardian_emergency_contact_number,
        p_student_curricular_program,
        p_student_influential_person_name,
        p_student_reason,
        p_influence_relationship,
        p_student_friends_in_university,
        p_student_friends_outside_university,
        p_student_special_interests,
        p_student_special_skills,
        p_student_hobbies_recreational_activities,
        p_student_ambitions_goals,
        p_student_guiding_principle_motto,
        p_student_personal_characteristics,
        p_student_significant_event_in_life,
        p_student_present_concerns_problems,
        p_student_present_fears,
        p_student_future_expectations,
        p_student_future_vision,
        p_student_dreams_aspirations,
        p_course_selection,
        p_student_consulted_psychiatrist,
        p_student_psychiatrist_sessions_count,
        p_student_psychiatrist_reason,
        p_student_psychiatrist_when,
        p_student_consulted_psychologist,
        p_student_psychologist_sessions_count,
        p_student_psychologist_reason,
        p_student_psychologist_when,
        p_student_consulted_counselor,
        p_student_counselor_sessions_count,
        p_student_counselor_reason,
        p_student_counselor_when,
        p_student_counselor_name,
        p_counselor_location,
        p_tests_taken,
        p_test_details,
        p_medications,
        p_medication_details,
        p_medication_start_date,
        p_medication_frequency
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateAdminPassword` (IN `p_email` VARCHAR(255), IN `p_new_password` VARCHAR(255))   BEGIN
    UPDATE admin_credential 
    SET admin_password = p_new_password, reset_token = NULL 
    WHERE admin_email = p_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateAdminResetToken` (IN `p_email` VARCHAR(255), IN `p_token` VARCHAR(32), IN `p_timestamp` DATETIME)   BEGIN
    UPDATE admin_credential 
    SET reset_token = p_token, token_timestamp = p_timestamp 
    WHERE admin_email = p_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateFormStatus` (IN `p_student_name` VARCHAR(255))   BEGIN
    UPDATE form SET status = 'done' WHERE student_name = p_student_name AND status = 'archived';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateResetToken` (IN `p_email` VARCHAR(255), IN `p_token` VARCHAR(32), IN `p_timestamp` DATETIME)   BEGIN
    UPDATE student_credentials 
    SET reset_token = p_token, token_timestamp = p_timestamp 
    WHERE student_email = p_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateStudentPassword` (IN `p_email` VARCHAR(255), IN `p_new_password` VARCHAR(255))   BEGIN
    UPDATE student_credentials 
    SET student_password = p_new_password, reset_token = NULL 
    WHERE student_email = p_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateStudentStatus` (IN `p_student_name` VARCHAR(255), IN `p_status` VARCHAR(20))   BEGIN
    UPDATE form 
    SET status = p_status 
    WHERE student_name = p_student_name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `VerifyStudentCredentials` (IN `email` VARCHAR(255), IN `sr_code` VARCHAR(50), OUT `status` VARCHAR(50))   BEGIN
    DECLARE v_student_email VARCHAR(255);
    DECLARE v_sr_code VARCHAR(50);

    -- Check if the student exists
    SELECT student_email, sr_code INTO v_student_email, v_sr_code
    FROM student_credentials
    WHERE student_email = email;

    IF v_student_email IS NULL THEN
        SET status = 'user_not_found';
    ELSEIF v_sr_code <> sr_code THEN
        SET status = 'sr_code_mismatch';
    ELSE
        SET status = 'success';
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin_credential`
--

CREATE TABLE `admin_credential` (
  `admin_id` int(11) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_password` char(8) NOT NULL,
  `reset_token` varchar(32) DEFAULT NULL,
  `token_timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_credential`
--

INSERT INTO `admin_credential` (`admin_id`, `admin_email`, `admin_password`, `reset_token`, `token_timestamp`) VALUES
(1, 'ourgroup@gmail.com', 'ourgroup', NULL, NULL),
(2, 'nevetsespaldon@gmail.com', 'ourgroup', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `form`
--

CREATE TABLE `form` (
  `student_form_id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `student_email` varchar(255) NOT NULL,
  `student_sr_code` char(8) NOT NULL,
  `student_age` tinyint(3) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `student_sex` enum('M','F','Other') NOT NULL,
  `student_program_year` varchar(100) NOT NULL,
  `student_mobile_number` varchar(15) NOT NULL,
  `student_educational_program_reason` text DEFAULT NULL,
  `student_easiest_subject` text DEFAULT NULL,
  `most_difficult_subject` text DEFAULT NULL,
  `lowest_grades_subject` text DEFAULT NULL,
  `highest_grades_subject` text DEFAULT NULL,
  `father_deceased` tinyint(1) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `father_present_address` text DEFAULT NULL,
  `father_permanent_address` text DEFAULT NULL,
  `father_home_phone_number` varchar(15) DEFAULT NULL,
  `father_mobile_phone_number` varchar(15) DEFAULT NULL,
  `father_email` varchar(255) DEFAULT NULL,
  `father_educational_attainment` varchar(255) DEFAULT NULL,
  `father_occupation` varchar(255) DEFAULT NULL,
  `father_business_address` varchar(255) DEFAULT NULL,
  `father_business_phone` varchar(15) DEFAULT NULL,
  `father_annual_income` decimal(10,2) DEFAULT NULL,
  `father_languages_spoken` varchar(255) DEFAULT NULL,
  `father_religion` varchar(255) DEFAULT NULL,
  `mother_deceased` tinyint(1) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mother_present_address` text DEFAULT NULL,
  `mother_permanent_address` text DEFAULT NULL,
  `mother_home_phone_number` varchar(15) DEFAULT NULL,
  `mother_mobile_phone_number` varchar(15) DEFAULT NULL,
  `mother_email` varchar(255) DEFAULT NULL,
  `mother_educational_attainment` varchar(255) DEFAULT NULL,
  `mother_occupation` varchar(255) DEFAULT NULL,
  `mother_business_address` varchar(255) DEFAULT NULL,
  `mother_business_phone` varchar(15) DEFAULT NULL,
  `annual_income` decimal(10,2) DEFAULT NULL,
  `mother_languages_spoken` varchar(255) DEFAULT NULL,
  `mother_religion` varchar(255) DEFAULT NULL,
  `parent_status` varchar(255) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_relationship` varchar(255) DEFAULT NULL,
  `guardian_address` varchar(255) DEFAULT NULL,
  `guardian_landline` varchar(15) DEFAULT NULL,
  `guardian_mobile` varchar(15) DEFAULT NULL,
  `guardian_emergency_contact_name` varchar(255) DEFAULT NULL,
  `guardian_emergency_contact_number` varchar(15) DEFAULT NULL,
  `student_curricular_program` text DEFAULT NULL,
  `student_influential_person_name` varchar(100) DEFAULT NULL,
  `student_reason` text DEFAULT NULL,
  `influence_relationship` varchar(255) DEFAULT NULL,
  `student_friends_in_university` text DEFAULT NULL,
  `student_friends_outside_university` text DEFAULT NULL,
  `student_special_interests` text DEFAULT NULL,
  `student_special_skills` varchar(255) DEFAULT NULL,
  `student_hobbies_recreational_activities` text DEFAULT NULL,
  `student_ambitions_goals` text DEFAULT NULL,
  `student_guiding_principle_motto` text DEFAULT NULL,
  `student_personal_characteristics` text DEFAULT NULL,
  `student_significant_event_in_life` text DEFAULT NULL,
  `student_present_concerns_problems` text DEFAULT NULL,
  `student_present_fears` text DEFAULT NULL,
  `student_future_expectations` text DEFAULT NULL,
  `student_future_vision` text DEFAULT NULL,
  `student_dreams_aspirations` text DEFAULT NULL,
  `course_selection` text DEFAULT NULL,
  `student_consulted_psychiatrist` tinyint(1) DEFAULT 0,
  `student_psychiatrist_sessions_count` int(11) DEFAULT NULL,
  `student_psychiatrist_reason` text DEFAULT NULL,
  `student_psychiatrist_when` date DEFAULT NULL,
  `student_consulted_psychologist` tinyint(1) DEFAULT 0,
  `student_psychologist_sessions_count` int(11) DEFAULT NULL,
  `student_psychologist_reason` text DEFAULT NULL,
  `student_psychologist_when` date DEFAULT NULL,
  `student_consulted_counselor` tinyint(1) DEFAULT 0,
  `student_counselor_sessions_count` int(11) DEFAULT NULL,
  `student_counselor_reason` text DEFAULT NULL,
  `student_counselor_when` date DEFAULT NULL,
  `student_counselor_name` varchar(100) DEFAULT NULL,
  `counselor_location` varchar(255) DEFAULT NULL,
  `tests_taken` tinyint(1) DEFAULT NULL,
  `test_details` text DEFAULT NULL,
  `medications` tinyint(1) DEFAULT NULL,
  `medication_details` text DEFAULT NULL,
  `medication_start_date` date DEFAULT NULL,
  `medication_frequency` varchar(50) DEFAULT NULL,
  `status` enum('pending','done','archived') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form`
--

INSERT INTO `form` (`student_form_id`, `student_name`, `student_email`, `student_sr_code`, `student_age`, `date`, `student_sex`, `student_program_year`, `student_mobile_number`, `student_educational_program_reason`, `student_easiest_subject`, `most_difficult_subject`, `lowest_grades_subject`, `highest_grades_subject`, `father_deceased`, `father_name`, `father_present_address`, `father_permanent_address`, `father_home_phone_number`, `father_mobile_phone_number`, `father_email`, `father_educational_attainment`, `father_occupation`, `father_business_address`, `father_business_phone`, `father_annual_income`, `father_languages_spoken`, `father_religion`, `mother_deceased`, `mother_name`, `mother_present_address`, `mother_permanent_address`, `mother_home_phone_number`, `mother_mobile_phone_number`, `mother_email`, `mother_educational_attainment`, `mother_occupation`, `mother_business_address`, `mother_business_phone`, `annual_income`, `mother_languages_spoken`, `mother_religion`, `parent_status`, `guardian_name`, `guardian_relationship`, `guardian_address`, `guardian_landline`, `guardian_mobile`, `guardian_emergency_contact_name`, `guardian_emergency_contact_number`, `student_curricular_program`, `student_influential_person_name`, `student_reason`, `influence_relationship`, `student_friends_in_university`, `student_friends_outside_university`, `student_special_interests`, `student_special_skills`, `student_hobbies_recreational_activities`, `student_ambitions_goals`, `student_guiding_principle_motto`, `student_personal_characteristics`, `student_significant_event_in_life`, `student_present_concerns_problems`, `student_present_fears`, `student_future_expectations`, `student_future_vision`, `student_dreams_aspirations`, `course_selection`, `student_consulted_psychiatrist`, `student_psychiatrist_sessions_count`, `student_psychiatrist_reason`, `student_psychiatrist_when`, `student_consulted_psychologist`, `student_psychologist_sessions_count`, `student_psychologist_reason`, `student_psychologist_when`, `student_consulted_counselor`, `student_counselor_sessions_count`, `student_counselor_reason`, `student_counselor_when`, `student_counselor_name`, `counselor_location`, `tests_taken`, `test_details`, `medications`, `medication_details`, `medication_start_date`, `medication_frequency`, `status`) VALUES
(1, 'rayjomar catapang', '23-37409@g.batstate-u.edu.ph', '23-37409', 20, '2004-10-25', 'M', 'BSIT', '09959141394', 'sadsa', 'asdsa', 'asdasd', 'asda', 'sadad', 1, 'asda', 'sada', 'asdsa', '5646546', '2318678451', 'wsdadaw@gmail.com', 'sadasdasdsa', 'asdasgsdg', 'asdasdas', '548326', 144402.00, 'sadasd', 'asdasd', 1, 'asdadsad', 'asdad', 'asdsad', '2656812', '7921798952', 'asdad@gmai.cpm', 'dadasdad', 'sdfasdfsa', 'asdada', '986552', 0.00, 'aasdad', 'asdad', 'Living Together', 'sadadsad', 'married', 'sadadas', '283213', '09123456789', 'ashgdahjsdjhas', '1212334343423', 'sadasdfgsdzfs', 'as', 'sad', 'sadas', 'sad', 'asdsa', 'asd', 'asd', 'sadas', 'asdas', 'asdas', 'asd', 'gsddf', 'asdsa', 'fasds', 'sadas', 'asdas', 'gsdfa', 'My personal interest', 0, 0, 'asdasda', '0000-00-00', 0, 0, '', '0000-00-00', NULL, 0, '', '0000-00-00', '', '', 0, '', NULL, '', '0000-00-00', '', 'archived');

-- --------------------------------------------------------

--
-- Table structure for table `student_credentials`
--

CREATE TABLE `student_credentials` (
  `student_email` varchar(255) NOT NULL,
  `student_password` varchar(255) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `reset_token` varchar(32) DEFAULT NULL,
  `token_timestamp` datetime DEFAULT NULL,
  `sr_code` char(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_credentials`
--

INSERT INTO `student_credentials` (`student_email`, `student_password`, `admin_id`, `reset_token`, `token_timestamp`, `sr_code`) VALUES
('23-34868@g.batstate-u.edu.ph', '23-34868', 1, NULL, NULL, '23-34868'),
('23-37315@g.batstate-u.edu.ph', '23-37315', 1, NULL, NULL, '23-37315'),
('23-37409@g.batstate-u.edu.ph', '23-37409', 1, NULL, NULL, '23-37409'),
('23-39638@g.batstate-u.edu.ph', '23-39638', 1, NULL, NULL, '23-39638'),
('24-35290@g.batstate-u.edu.ph', '24-35290', 1, NULL, NULL, '24-35290'),
('24-38748@g.batstate-u.edu.ph', '24-38748', 1, NULL, NULL, '24-38748');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_credential`
--
ALTER TABLE `admin_credential`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_email` (`admin_email`);

--
-- Indexes for table `form`
--
ALTER TABLE `form`
  ADD PRIMARY KEY (`student_form_id`),
  ADD KEY `student_email` (`student_email`);

--
-- Indexes for table `student_credentials`
--
ALTER TABLE `student_credentials`
  ADD PRIMARY KEY (`student_email`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_credential`
--
ALTER TABLE `admin_credential`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `form`
--
ALTER TABLE `form`
  MODIFY `student_form_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `form`
--
ALTER TABLE `form`
  ADD CONSTRAINT `form_ibfk_1` FOREIGN KEY (`student_email`) REFERENCES `student_credentials` (`student_email`);

--
-- Constraints for table `student_credentials`
--
ALTER TABLE `student_credentials`
  ADD CONSTRAINT `student_credentials_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_credential` (`admin_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
