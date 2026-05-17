<?php
/**
 * Reply to Support Ticket
 * Admin sends reply to customer via email
 */

require_once 'db.php';
require_once 'mail_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$ticket_id = $data['ticket_id'] ?? '';
$reply_message = $data['reply_message'] ?? '';
$admin_name = $data['admin_name'] ?? 'QueueBank Admin';

if (!$ticket_id || !$reply_message) {
    echo json_encode(['success' => false, 'error' => 'Missing ticket_id or reply_message']);
    exit;
}

try {
    // Get ticket details
    $stmt = $pdo->prepare("SELECT * FROM support_tickets WHERE ticket_id = ?");
    $stmt->execute([$ticket_id]);
    $ticket = $stmt->fetch();
    
    if (!$ticket) {
        echo json_encode(['success' => false, 'error' => 'Ticket not found']);
        exit;
    }
    
    // Update ticket status to resolved
    $stmt = $pdo->prepare("UPDATE support_tickets SET status = 'Resolved' WHERE ticket_id = ?");
    $stmt->execute([$ticket_id]);
    
    // Prepare email
    $customer_email = $ticket['email'];
    $customer_name = $ticket['customer_name'];
    $subject = "Re: {$ticket['subject']} - QueueBank Support";
    
    $body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f5f5f5; }
            .header { background: linear-gradient(135deg, #7cb987, #5aab9e); color: white; padding: 20px; border-radius: 8px; }
            .content { background: white; padding: 20px; margin-top: 20px; border-radius: 8px; }
            .footer { margin-top: 20px; font-size: 12px; color: #777; }
            .ticket-id { background: #e8f5eb; padding: 10px; border-radius: 5px; margin: 10px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>QueueBank Support Response</h2>
            </div>
            <div class='content'>
                <p>Dear <strong>{$customer_name}</strong>,</p>
                <p>Thank you for contacting QueueBank Support. We have reviewed your request and here is our response:</p>
                
                <div class='ticket-id'>
                    <strong>Ticket ID:</strong> {$ticket_id}<br>
                    <strong>Subject:</strong> {$ticket['subject']}
                </div>
                
                <p><strong>Your Message:</strong></p>
                <p>" . nl2br(htmlspecialchars($ticket['message'])) . "</p>
                
                <p><strong>Our Response:</strong></p>
                <p>" . nl2br(htmlspecialchars($reply_message)) . "</p>
                
                <p>If you have any further questions, please feel free to submit another support ticket.</p>
                
                <p>Best regards,<br><strong>{$admin_name}</strong><br>QueueBank MS Support Team</p>
            </div>
            <div class='footer'>
                <p>This is an automated email. Please do not reply directly to this email.</p>
                <p>&copy; 2026 QueueBank MS. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Send email
    $email_sent = send_email($customer_email, $customer_name, $subject, $body);
    
    if ($email_sent) {
        echo json_encode([
            'success' => true,
            'message' => 'Reply sent to customer',
            'ticket_id' => $ticket_id
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Failed to send email to customer'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
