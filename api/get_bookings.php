<?php
require_once 'db.php';

$email = $_GET['email'] ?? '';
$date  = $_GET['date']  ?? '';

if ($email) {
    // Customer: get bookings by email
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE email = ? ORDER BY created_at DESC");
    $stmt->execute([$email]);
} elseif ($date) {
    // Staff: get bookings by date
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE date = ? ORDER BY slot ASC");
    $stmt->execute([$date]);
} else {
    // Admin: get all bookings
    $stmt = $pdo->prepare("SELECT * FROM bookings ORDER BY created_at DESC");
    $stmt->execute();
}

$bookings = $stmt->fetchAll();
echo json_encode(["success" => true, "bookings" => $bookings]);
?>