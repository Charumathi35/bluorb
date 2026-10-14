<?php
require __DIR__ . '/vendor/autoload.php';

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

header('Content-Type: application/json');
date_default_timezone_set('Asia/Kolkata');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$rawName    = trim($_POST['name']    ?? '');
$rawEmail   = trim($_POST['email']   ?? '');
$rawPhone   = trim($_POST['phone']   ?? '');
$rawMessage = trim($_POST['message'] ?? '');

if (!$rawName || !$rawEmail || !$rawPhone || !$rawMessage) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($rawEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// Clean subject to avoid header injection
$cleanSubjectName = str_replace(["\r", "\n"], '', $rawName);
$subject = "New Contact Enquiry from $cleanSubjectName - Bluorb";

// Plain text fallback
$dateTime = date('d M Y, h:i A');
$textBody = "New Contact Enquiry - Bluorb\n"
          . "==============================\n\n"
          . "Full Name : $rawName\n"
          . "Email     : $rawEmail\n"
          . "Phone     : $rawPhone\n"
          . "Received  : $dateTime IST\n\n"
          . "Message:\n"
          . "$rawMessage\n\n"
          . "------------------------------\n"
          . "Sent automatically from Bluorb Contact Form.";

// HTML Escaping to prevent email client XSS/HTML injection
$safeName    = htmlspecialchars($rawName, ENT_QUOTES, 'UTF-8');
$safeEmail   = htmlspecialchars($rawEmail, ENT_QUOTES, 'UTF-8');
$safePhone   = htmlspecialchars($rawPhone, ENT_QUOTES, 'UTF-8');
$safeMessage = nl2br(htmlspecialchars($rawMessage, ENT_QUOTES, 'UTF-8'));

// Responsive, styled HTML Email Template matching Bluorb branding
$htmlBody = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Contact Enquiry - Bluorb</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f0f4f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #2d3748;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f0f4f8; padding: 30px 15px;">
    <tr>
      <td align="center">
        <!-- Main Card Container -->
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e2e8f0;">
          
          <!-- Header Banner -->
          <tr>
            <td style="background-color: #0c1e36; padding: 30px 40px; text-align: center; border-bottom: 3px solid #c5a880;">
              <h1 style="margin: 0; color: #ffffff; font-size: 26px; font-weight: 700; letter-spacing: 4px; text-transform: uppercase;">BLUORB</h1>
              <p style="margin: 6px 0 0; color: #c5a880; font-size: 13px; letter-spacing: 2px; text-transform: uppercase;">Website Contact Enquiry</p>
            </td>
          </tr>

          <!-- Body Content -->
          <tr>
            <td style="padding: 35px 40px;">
              <p style="margin: 0 0 22px; font-size: 15px; line-height: 1.6; color: #4a5568;">
                You have received a new contact enquiry through the <strong>Bluorb</strong> website form.
              </p>

              <!-- Details Table -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: separate; border-spacing: 0; border: 1px solid #edf2f7; border-radius: 8px; overflow: hidden; margin-bottom: 25px;">
                <tr>
                  <td width="32%" style="background-color: #f8fafc; padding: 12px 16px; font-size: 13px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #edf2f7;">Full Name</td>
                  <td style="padding: 12px 16px; font-size: 14px; font-weight: 600; color: #1a202c; border-bottom: 1px solid #edf2f7;">{$safeName}</td>
                </tr>
                <tr>
                  <td style="background-color: #f8fafc; padding: 12px 16px; font-size: 13px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #edf2f7;">Email</td>
                  <td style="padding: 12px 16px; font-size: 14px; border-bottom: 1px solid #edf2f7;">
                    <a href="mailto:{$safeEmail}" style="color: #0c1e36; text-decoration: none; font-weight: 500;">{$safeEmail}</a>
                  </td>
                </tr>
                <tr>
                  <td style="background-color: #f8fafc; padding: 12px 16px; font-size: 13px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #edf2f7;">Phone</td>
                  <td style="padding: 12px 16px; font-size: 14px; border-bottom: 1px solid #edf2f7;">
                    <a href="tel:{$safePhone}" style="color: #0c1e36; text-decoration: none; font-weight: 500;">{$safePhone}</a>
                  </td>
                </tr>
                <tr>
                  <td style="background-color: #f8fafc; padding: 12px 16px; font-size: 13px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">Received At</td>
                  <td style="padding: 12px 16px; font-size: 13px; color: #718096;">{$dateTime} IST</td>
                </tr>
              </table>

              <!-- Message Section -->
              <div style="margin-bottom: 10px;">
                <span style="font-size: 13px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">Message:</span>
              </div>
              <div style="background-color: #f8fafc; border-left: 4px solid #c5a880; border-radius: 0 6px 6px 0; padding: 18px 20px; font-size: 14px; line-height: 1.7; color: #2d3748; margin-bottom: 30px;">
                {$safeMessage}
              </div>

            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color: #f8fafc; padding: 20px 40px; text-align: center; border-top: 1px solid #edf2f7; font-size: 12px; color: #a0aec0; line-height: 1.5;">
              <p style="margin: 0 0 4px;">This email was automatically generated and sent from the <strong>Bluorb</strong> contact form.</p>
              <p style="margin: 0;">&copy; Bluorb. All rights reserved.</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;

$config = require __DIR__ . '/config.php';

$sesClient = new SesClient([
    'version'     => 'latest',
    'region'      => $config['aws_region'],
    'credentials' => [
        'key'    => $config['aws_key'],
        'secret' => $config['aws_secret'],
    ],
]);

try {
    $result = $sesClient->sendEmail([
        'Destination' => [
            'ToAddresses' => [
                'Sasikanth.Manivannan@pricol.com',
                'charumathi.saravanakumar@pricol.com',
            ],
        ],
        'ReplyToAddresses' => [$rawEmail],
        'Source' => 'connect@bluorb.in',
        'Message' => [
            'Body' => [
                'Html' => [
                    'Charset' => 'UTF-8',
                    'Data'    => $htmlBody,
                ],
                'Text' => [
                    'Charset' => 'UTF-8',
                    'Data'    => $textBody,
                ],
            ],
            'Subject' => [
                'Charset' => 'UTF-8',
                'Data'    => $subject,
            ],
        ],
    ]);

    echo json_encode(['success' => true, 'message' => 'Your message has been sent successfully!']);
} catch (AwsException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to send. Error: ' . $e->getAwsErrorMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to send. Error: ' . $e->getMessage()]);
}
?>
