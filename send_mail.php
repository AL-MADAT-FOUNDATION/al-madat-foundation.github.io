<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input fields
    $name = htmlspecialchars(strip_tags($_POST["name"]));
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(strip_tags($_POST["subject"]));
    $message = htmlspecialchars(strip_tags($_POST["message"]));

    $referer_url = $_SERVER['HTTP_REFERER'];
    $parsed_url = parse_url($referer_url);
    $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
    $file_name = pathinfo($path, PATHINFO_FILENAME);

    try {
        $mail = new PHPMailer(true);

        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'patelmusa23@gmail.com';
        $mail->Password = 'mxot zlvm sljd mbso';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('almadatcharity@gmail.com', 'Al-Madat');
        $mail->addAddress('almadatcharity@gmail.com', 'Al-Madat');
        $mail->isHTML(true);

        // Email Content
        $mail->Subject = "$file_name - Contact Form Al-Madat";
        $mail->Body = "
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong> $message</p>
        ";

        $mail->send();
        header("Location: thank-you.html");

    } catch (Exception $e) {
        $url = $_SERVER['HTTP_REFERER'];
        header("Location: $url?mail-sent=false");
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>
