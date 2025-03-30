<?php

session_start(); //SESSION START

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure Composer installed PHPMailer

if (!$_POST) exit;

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$select_price = $_POST['select_price'];
$select_service = $_POST['select_service'];
$comments = $_POST['comments'];

if (trim($first_name) == '') {
	echo '<div class="error_message">Attention! You must enter your name.</div>';
	exit();
} else if (trim($email) == '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
	echo '<div class="error_message">Attention! Please enter a valid email address.</div>';
	exit();
} else if (trim($comments) == '') {
	echo '<div class="error_message">Attention! Please enter your message.</div>';
	exit();
}

$mail = new PHPMailer(true);

try {
	// SMTP Configuration
	$mail->isSMTP();
	$mail->Host       = 'smtp.gmail.com';  // Your SMTP server (e.g., Gmail, Outlook, Zoho)
	$mail->SMTPAuth   = true;
	$mail->Username   = 'ramanprakash09@gmail.com'; // Your email address
	$mail->Password   = 'ddixkegcxujvcrve'; // Your email password or App Password
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS Encryption
	$mail->Port       = 587; // Port for TLS
	$mail->SMTPDebug  = 2; // Enable detailed error debugging
	$mail->CharSet    = 'UTF-8'; // Ensure proper encoding


	// Sender & Recipient
	$mail->setFrom('ramanprakash09@gmail.com', 'Your Name');
	$mail->addAddress('ramanprakash09@gmail.com', 'Receiver Name'); // Change to your recipient

	// Email Content
	$mail->isHTML(true);
	$mail->Subject = 'Contact Form Submission from ' . $first_name;
	$mail->Body    = "<p>You have been contacted by <strong>$first_name</strong>. </p>
                      <p>Selected service: <strong>$select_service</strong></p>
                      <p>Budget: <strong>$select_price</strong></p>
                      <p>Message: $comments</p>
                      <p>Contact Details:<br>Email: <strong>$email</strong><br>Phone: <strong>$phone</strong></p>";

	// Send Email
	$mail->send();
	echo "<fieldset>";
	echo "<div id='success_page'>";
	echo "<h1>Email Sent Successfully.</h1>";
	echo "<p>Thank you <strong>$first_name</strong>, your message has been submitted to us.</p>";
	echo "</div>";
	echo "</fieldset>";
} catch (Exception $e) {
	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
