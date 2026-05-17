<?php
require_once 'db.php';

$date = $_GET['date'] ?? '';
$slot = $_GET['slot'] ?? '';

if (!$date || !$slot) {
    echo json_encode(["success" => false, "error" => "Date and slot are required"]);
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM bookings WHERE date = ? AND slot = ? AND status = 'Confirmed'");
$stmt->execute([$date, $slot]);

$taken = $stmt->fetch() ? true : false;
echo json_encode(["success" => true, "available" => !$taken]);
?>