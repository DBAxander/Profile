<?php
// Start session at the beginning
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Default XAMPP username
define('DB_PASS', ''); // Default XAMPP password is empty
define('DB_NAME', 'student_db');

// Connect to database
try {
    $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);
    $studentId = filter_input(INPUT_POST, 'studentId', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $course = filter_input(INPUT_POST, 'course', FILTER_SANITIZE_STRING);
    $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_STRING);
    $interests = isset($_POST['interests']) ? $_POST['interests'] : [];

    // Additional validation
    $errors = [];

    if (empty($fullName)) {
        $errors[] = "Full name is required";
    }

    if (empty($studentId)) {
        $errors[] = "Student ID is required";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }

    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }

    if (empty($dob)) {
        $errors[] = "Date of birth is required";
    }

    if (empty($address)) {
        $errors[] = "Address is required";
    }

    if (empty($course)) {
        $errors[] = "Course is required";
    }

    if (empty($year)) {
        $errors[] = "Year of study is required";
    }

    // If no errors, process the data
    if (empty($errors)) {
        try {
            // Prepare SQL statement
            $stmt = $db->prepare("INSERT INTO students 
                                (full_name, student_id, email, phone, dob, gender, address, course, year, interests) 
                                VALUES (:fullName, :studentId, :email, :phone, :dob, :gender, :address, :course, :year, :interests)");
            
            // Convert interests array to string
            $interestsString = implode(', ', $interests);
            
            // Bind parameters
            $stmt->bindParam(':fullName', $fullName);
            $stmt->bindParam(':studentId', $studentId);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':dob', $dob);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':course', $course);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':interests', $interestsString);
            
            // Execute query
            $stmt->execute();
            
            // Store in session for success page
            $_SESSION['form_data'] = [
                'fullName' => $fullName,
                'studentId' => $studentId,
                'email' => $email,
                'phone' => $phone,
                'dob' => $dob,
                'gender' => $gender,
                'address' => $address,
                'course' => $course,
                'year' => $year,
                'interests' => $interests
            ];
            
            // Redirect to success page
            header('Location: success.html');
            exit;
            
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// Error handling
if (!empty($errors)) {
    echo "<h2>Errors:</h2>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
    echo "<a href='index.html'>Go back to form</a>";
    exit;
}
?>