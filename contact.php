<?php
// // Database connection
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "contact_form";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Check if all fields are filled
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please complete the form and try again.";
        exit;
    }

    // Set the recipient email address
    $recipient = "byshubham6@gmail.com";

    // Set the email subject
    $subject = "New Contact Form Message from $name";

    // Build the email content with HTML formatting
    $email_content = "
    <html>
    <head>
        <title>New Contact Form Message</title>
    </head>
    <body>
        <h2>New Contact Form Message</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Message:</strong></p>
        <p>" . nl2br($message) . "</p>
    </body>
    </html>";

    // Build the email headers
    $headers = array(
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email,
        'X-Mailer: PHP/' . phpversion()
    );

    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Send the email with error handling
    try {
        if (mail($recipient, $subject, $email_content, implode("\r\n", $headers))) {
            http_response_code(200);
            echo "Thank You! Your message has been sent successfully.";
            
            // Log successful sending
            error_log("Email sent successfully from $email to $recipient");
        } else {
            throw new Exception("Mail sending failed");
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo "Oops! Something went wrong, we couldn't send your message.";
        
        // Log the error
        error_log("Failed to send email: " . $e->getMessage());
        error_log("From: $email, To: $recipient, Subject: $subject");
    }
} else {
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?> 