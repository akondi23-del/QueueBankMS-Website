<?php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$data   = json_decode(file_get_contents("php://input"), true);

if ($method === 'POST') {
    // Submit ticket
    $required = ['customer_name','email','subject','message'];
    foreach ($required as $f) {
        if (empty(trim($data[$f] ?? ''))) {
            echo json_encode(["success" => false, "error" => "Missing: $f"]);
            exit;
        }
    }
    // Validate email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "error" => "Invalid email format"]);
        exit;
    }
    // Generate ticket ID
    $ticket_id = 'TK-' . strtoupper(substr(md5(uniqid()), 0, 5));
    $stmt = $pdo->prepare("INSERT INTO support_tickets (ticket_id, customer_name, email, subject, message) VALUES (?,?,?,?,?)");
    $stmt->execute([$ticket_id, $data['customer_name'], $data['email'], $data['subject'], $data['message']]);
    echo json_encode(["success" => true, "ticket_id" => $ticket_id]);

} elseif ($method === 'GET') {
    // Get all tickets (admin) or by email (customer)
    $email = $_GET['email'] ?? '';
    if ($email) {
        $stmt = $pdo->prepare("SELECT * FROM support_tickets WHERE email = ? ORDER BY created_at DESC");
        $stmt->execute([$email]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM support_tickets ORDER BY created_at DESC");
        $stmt->execute();
    }
    echo json_encode(["success" => true, "tickets" => $stmt->fetchAll()]);

} elseif ($method === 'PUT') {
    // Update ticket status (admin)
    $ticket_id = $data['ticket_id'] ?? '';
    $status    = $data['status']    ?? '';
    $allowed   = ['Open','In Progress','Resolved'];
    if (!$ticket_id || !in_array($status, $allowed)) {
        echo json_encode(["success" => false, "error" => "Invalid ticket ID or status"]);
        exit;
    }
    $stmt = $pdo->prepare("UPDATE support_tickets SET status = ? WHERE ticket_id = ?");
    $stmt->execute([$status, $ticket_id]);
    echo json_encode(["success" => true]);
}
?>