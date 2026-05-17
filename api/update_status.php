<?php
require_once 'db.php';

$data   = json_decode(file_get_contents("php://input"), true);
$code   = $data['code']   ?? '';
$status = $data['status'] ?? '';

$allowed = ['Confirmed','Completed','Cancelled','No-show'];
if (!$code || !in_array($status, $allowed)) {
    echo json_encode(["success" => false, "error" => "Invalid code or status"]);
    exit;
}

$stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE code = ?");
$stmt->execute([$status, $code]);

echo json_encode(["success" => true]);
?>