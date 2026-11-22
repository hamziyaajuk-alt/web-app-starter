<?php

require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($to, $subject, $message) {

    // ✅ Load config
    $config = require __DIR__ . '/../config/mail.php';

    $mail = new PHPMailer(true);

    try {
        // SMTP SETTINGS
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        $mail->Username   = $config['username'];
        $mail->Password   = $config['password'];

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // SENDER & RECEIVER
        $mail->setFrom($config['username'], 'OPMS System');
        $mail->addAddress($to);

        // EMAIL CONTENT
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        // ❌ REMOVE DEBUG IN FINAL
        $mail->SMTPDebug = 0;

        $mail->send();
        return true;

    } catch (Exception $e) {
        echo "<pre>";
        echo "Mailer Error: " . $mail->ErrorInfo;
        echo "</pre>";
        return false;
    }
}