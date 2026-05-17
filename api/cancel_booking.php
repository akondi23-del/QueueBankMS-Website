<?php
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$code = $data['code'] ?? '';

if (!$code) {
    echo json_encode(["success" => false, "error" => "Booking code is required"]);
    exit;
}

$stmt = $pdo->prepare("UPDATE bookings SET status = 'Cancelled' WHERE code = ? AND status = 'Confirmed'");
$stmt->execute([$code]);

if ($stmt->rowCount() > 0) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Booking not found or already cancelled"]);
}
?>