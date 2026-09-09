<?php
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

$headers = "From: noreply@bluorb.in\r\n";

$sent1 = mail('info@bluorb.in',       $subject, $body, $headers);
$sent2 = mail('enquiries@bluorb.in',  $subject, $body, $headers);

if ($sent1 && $sent2) {
    echo json_encode(['success' => true,  'message' => 'Your message has been sent successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send. Please try again.']);
}
?>
