<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ correct file
require "mail/send_mail.php";

// ✅ your email (receiver)
$to = "hamziya1.abduljabbar@gmail.com";

// ✅ test send
$result = sendMail(
    $to,
    "Test Email from OPMS",
    "<h3>This is a test email from OPMS system</h3>"
);

// ✅ result
if ($result) {
    echo "✅ Email sent successfully!";
} else {
    echo "❌ Email failed!";
}
?>