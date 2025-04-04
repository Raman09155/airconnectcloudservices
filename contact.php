<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$first_name = trim($_POST['first_name'] ?? '');
	$last_name = trim($_POST['last_name'] ?? '');
	$email = trim($_POST['email'] ?? '');
	$phone = trim($_POST['phone'] ?? '');
	$select_service = trim($_POST['select_service'] ?? '');
	$select_price = trim($_POST['select_price'] ?? '');
	$comments = trim($_POST['comments'] ?? '');

	$errors = [];

	if (empty($first_name)) $errors['first_name'] = "First name is required.";
	if (empty($last_name)) $errors['last_name'] = "Last name is required.";
	if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Valid email is required.";
	if (empty($phone) || !preg_match("/^[0-9]{10,15}$/", $phone)) $errors['phone'] = "Valid phone number is required.";
	if (empty($select_service) || $select_service === "12") $errors['select_service'] = "Please select a service.";
	if (empty($select_price)) $errors['select_price'] = "Please select a country.";
	if (empty($comments)) $errors['comments'] = "Message field is required.";

	if (!empty($errors)) {
		echo json_encode(['status' => 'error', 'errors' => $errors]);
		exit();
	}

	$mail = new PHPMailer(true);

	try {
		$mail->isSMTP();
		$mail->Host       = $_ENV['SMTP_HOST'];
		$mail->SMTPAuth   = true;
		$mail->Username   = $_ENV['SMTP_USERNAME'];
		$mail->Password   = $_ENV['SMTP_PASSWORD'];
		$mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
		$mail->Port       = $_ENV['SMTP_PORT'];

		$mail->setFrom($email, "$first_name $last_name");
		$mail->addAddress('ramanprakash09@gmail.com', 'Airconnect Cloud Services');

		$mail->isHTML(true);
		$mail->Subject = "New Contact Form Submission from $first_name";
		$mail->Body    = "
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> $first_name $last_name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone:</strong> $phone</p>
            <p><strong>Selected Service:</strong> $select_service</p>
            <p><strong>Country:</strong> $select_price</p>
            <p><strong>Message:</strong> $comments</p>
        ";

		if ($mail->send()) {
			echo json_encode(['status' => 'success', 'message' => "Thank you, $first_name. We will contact you soon."]);
		} else {
			echo json_encode(['status' => 'error', 'message' => "Oops! Something went wrong. Please try again."]);
		}
	} catch (Exception $e) {
		echo json_encode(['status' => 'error', 'message' => "Mailer Error: {$mail->ErrorInfo}"]);
	}
}
