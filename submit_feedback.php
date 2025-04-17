<?php
// submit_feedback.php

// Database configuration - UPDATE THESE WITH YOUR ACTUAL CREDENTIALS
$host = '127.0.0.1';
$dbname = 'feedbackform';
$username = 'root';
$password = ''; // Your actual password here

// First, set headers to prevent any accidental output
header('Content-Type: application/json');

// Start output buffering to catch any errors
ob_start();

try {
    // Create PDO connection with error handling
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // Check request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST requests are accepted', 405);
    }

    // Get raw POST data
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    // Validate required fields
    $required = ['username', 'regno', 'section', 'text'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            throw new Exception("Field $field is required", 400);
        }
    }

    // Sanitize and validate inputs
    $username = substr(filter_var($input['username'], FILTER_SANITIZE_STRING), 0, 20);
    $regno = filter_var($input['regno'], FILTER_VALIDATE_INT);
    $section = substr(filter_var($input['section'], FILTER_SANITIZE_STRING), 0, 30);
    $text = substr(filter_var($input['text'], FILTER_SANITIZE_STRING), 0, 100);

    if ($regno === false) {
        throw new Exception("Registration number must be numeric", 400);
    }

    // Prepare and execute SQL
    $stmt = $pdo->prepare("INSERT INTO feedbackform (username, RegNo, section, text) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $regno, $section, $text]);
 


    // Success response
    // echo json_encode(['success' => 'Feedback submitted successfully!']);
    
} catch (PDOException $e) {
    // Database-specific errors
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    // Other errors
    http_response_code($e->getCode() ?: 400);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    // Clear the output buffer
    ob_end_flush();
}
header("Location: index.html");
exit;


?>