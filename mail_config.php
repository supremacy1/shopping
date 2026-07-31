<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// This file is created by `composer install`
require_once __DIR__ . '/vendor/autoload.php';

function send_email($to, $subject, $body, $from_name = 'Desamall') {
    $mail = new PHPMailer(true);

    try {
        // Server settings from .env
        $mail->isSMTP();
        $mail->Host = 'delightskcompanyltd.com'; // Ensure this is your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'info@delightskcompanyltd.com'; // Use your domain email
        $mail->Password = 'andybestdigita@1'; // Use your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('info@delightskcompanyltd.com', $from_name);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // In a real app, you'd log this error. For now, we can just know it failed.
        // error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}