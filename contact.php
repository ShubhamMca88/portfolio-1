<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set up logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data
        $name = strip_tags(trim($_POST["name"]));
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message = strip_tags(trim($_POST["message"]));

        // Validate inputs
        if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please complete all fields with valid information.");
        }

        // Log the attempt
        error_log("Attempting to send email from: $email");

        // Set up email parameters
        $to = "byshubham6@gmail.com";
        $subject = "New Contact Form Message from $name";
        
        // Create email headers
        $headers = array(
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $name . ' <' . $email . '>',
            'Reply-To: ' . $email,
            'X-Mailer: PHP/' . phpversion(),
            'Return-Path: ' . $email
        );

        // Create HTML message
        $htmlMessage = "
        <html>
        <head>
            <title>New Contact Form Message</title>
        </head>
        <body>
            <h2>New Contact Form Message</h2>
            <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
            <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br(htmlspecialchars($message)) . "</p>
            <p><small>Sent from: " . $_SERVER['REMOTE_ADDR'] . "</small></p>
        </body>
        </html>";

        // Attempt to send email
        $mailResult = mail($to, $subject, $htmlMessage, implode("\r\n", $headers));

        if ($mailResult) {
            // Log success
            error_log("Email sent successfully to $to from $email");
            
            // Return success response
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Thank you! Your message has been sent successfully.'
            ]);
        } else {
            // Log the mail() function failure
            error_log("mail() function failed. Headers: " . print_r($headers, true));
            throw new Exception("Failed to send email through mail() function");
        }

    } catch (Exception $e) {
        // Log the error
        error_log("Error in contact form: " . $e->getMessage());
        error_log("Additional debug info: " . print_r($_POST, true));
        
        // Return error response
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Sorry, there was a problem sending your message. Please try again later.'
        ]);
    }
} else {
    // Return method not allowed response
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?> 