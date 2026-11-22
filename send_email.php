<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendEmail($toEmail, $toName, $subject, $message)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP SETTINGS (GMAIL)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hamziya1.abduljabbar@gmail.com'; // 🔴 CHANGE
        $mail->Password   = 'fhknciafuwksgyul';    // 🔴 CHANGE
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // EMAIL
        $mail->setFrom('hamziya1.abduljabbar@gmail.com', 'OPMS System');
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}