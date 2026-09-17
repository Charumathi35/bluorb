<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$name    = htmlspecialchars(trim($_POST['name']    ?? ''));
$email   = htmlspecialchars(trim($_POST['email']   ?? ''));
$phone   = htmlspecialchars(trim($_POST['phone']   ?? ''));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

if (!$name || !$email || !$phone || !$message) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

$subject = "New Contact Enquiry from $name - Bluorb";

$body = "Name    : $name\n"
      . "Email   : $email\n"
      . "Phone   : $phone\n"
      . "Message : $message";

$mail = new PHPMailer(true);

try {
    // Uncomment and configure these if you want to use SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.office365.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@bluorb.in';
    $mail->Password   = '#Helpline@098';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;
    $mail->Timeout    = 10;

    // Use the requested SMTP configuration
    // $mail->isSMTP();
    // $mail->Host       = '10.90.10.103';
    // $mail->SMTPAuth   = false;
    // $mail->SMTPAutoTLS = false; 
    // $mail->Port       = 25;
    // $mail->Timeout    = 10;

    // Use the specified sender address
    $mail->setFrom('info@bluorb.in');
    $mail->addAddress('charumathi.saravanakumar@pricol.com');
    // $mail->addReplyTo($email, $name);

    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();
    echo json_encode(['success' => true,  'message' => 'Your message has been sent successfully!']);
} catch (Exception $e) {
    // Show detailed error message to help debug SMTP issues
    echo json_encode(['success' => false, 'message' => 'Failed to send. Error: ' . $mail->ErrorInfo]);
}
?>
