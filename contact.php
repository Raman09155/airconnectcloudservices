<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
	exit("Direct access not allowed.");
}

// Email address verification function
function isEmail($email)
{
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Define recipient email
$recipient_email = "ramanprakash09@gmail.com";

// Sanitize input
$first_name = htmlspecialchars(strip_tags(trim($_POST['first_name'] ?? '')));
$last_name = htmlspecialchars(strip_tags(trim($_POST['last_name'] ?? '')));
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$phone = htmlspecialchars(strip_tags(trim($_POST['phone'] ?? '')));
$select_service = htmlspecialchars(strip_tags(trim($_POST['select_service'] ?? '')));
$select_price = htmlspecialchars(strip_tags(trim($_POST['select_price'] ?? '')));
$comments = htmlspecialchars(strip_tags(trim($_POST['comments'] ?? '')));

// Validation
if (empty($first_name)) {
	exit('<div class="error_message">Please enter your first name.</div>');
}
if (empty($email) || !isEmail($email)) {
	exit('<div class="error_message">Please enter a valid email address.</div>');
}
if (empty($comments)) {
	exit('<div class="error_message">Please enter your message.</div>');
}

// Email Subject
$subject = "Contact Form Submission from $first_name";

// Email Body
$message = "You have been contacted by $first_name $last_name.\n\n";
$message .= "Service Selected: $select_service\n";
$message .= "Budget: $select_price\n\n";
$message .= "Message:\n$comments\n\n";
$message .= "Contact Info:\n";
$message .= "Email: $email\n";
$message .= "Phone: $phone\n\n";
$message .= "Best Regards,\n$first_name";

// Email Headers
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send Email
if (mail($recipient_email, $subject, $message, $headers)) {
	echo '<div class="success_message">Thank you! Your message has been sent successfully.</div>';
} else {
	echo '<div class="error_message">Oops! Something went wrong. Please try again later.</div>';
}
