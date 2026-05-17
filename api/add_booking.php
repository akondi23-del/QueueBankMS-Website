<?php
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
$required = ['code','service_name','service_icon','duration','date','slot','first_name','last_name','email','phone'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(["success" => false, "error" => "Missing field: $field"]);
        exit;
    }
}

// Check if slot is already taken
$stmt = $pdo->prepare("SELECT id FROM bookings WHERE date = ? AND slot = ? AND status = 'Confirmed'");
$stmt->execute([$data['date'], $data['slot']]);
if ($stmt->fetch()) {
    echo json_encode(["success" => false, "error" => "Slot already booked"]);
    exit;
}

// Check if code is unique
$stmt = $pdo->prepare("SELECT id FROM bookings WHERE code = ?");
$stmt->execute([$data['code']]);
if ($stmt->fetch()) {
    echo json_encode(["success" => false, "error" => "Code already exists"]);
    exit;
}

// Insert booking
$stmt = $pdo->prepare("
    INSERT INTO bookings (code, service_name, service_icon, duration, date, slot, first_name, last_name, email, phone, notes, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Confirmed')
");
$stmt->execute([
    $data['code'],
    $data['service_name'],
    $data['service_icon'],
    $data['duration'],
    $data['date'],
    $data['slot'],
    $data['first_name'],
    $data['last_name'],
    $data['email'],
    $data['phone'],
    $data['notes'] ?? ''
]);

echo json_encode(["success" => true, "code" => $data['code']]);
?>