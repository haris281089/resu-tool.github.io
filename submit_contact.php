<?php
// PHP Script to handle form submission and save data to a file.
// NOTE: This file must be placed on a web server running PHP at the same level 
// as contact.html for the 'action="submit_contact.php"' to work.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Sanitize and retrieve form data
    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST['message']);

    // 2. Validate data
    if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Handle invalid or incomplete data
        http_response_code(400);
        echo "Error: Please complete all fields with valid information.";
        exit;
    }

    // 3. Format the data for storage
    $submission_time = date('Y-m-d H:i:s');
    $log_entry = "Time: {$submission_time}\n";
    $log_entry .= "Name: {$name}\n";
    $log_entry .= "Email: {$email}\n";
    $log_entry .= "Message: {$message}\n";
    $log_entry .= "--------------------------------------\n";

    // 4. Define the log file path
    // NOTE: Ensure the server has write permissions to this file/directory.
    $file_path = 'contact_submissions.txt';

    // 5. Save the data
    if (file_put_contents($file_path, $log_entry, FILE_APPEND | LOCK_EX) !== false) {
        // Success
        http_response_code(200);
        echo "Thank you for your message! We will be in touch shortly.";
        // Optionally redirect: header('Location: thank_you.html');
    } else {
        // Error handling for file write failure
        http_response_code(500);
        echo "Error: Could not save your message due to a server issue. Please try again later.";
    }

} else {
    // If the request method is not POST, redirect or show an error
    http_response_code(405);
    echo "Method Not Allowed.";
}
?>
