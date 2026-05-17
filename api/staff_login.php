<?php
require_once 'db.php';

$data     = json_decode(file_get_contents("php://input"), true);
$emp_id   = trim($data['employee_id'] ?? '');
$password = $data['password'] ?? '';

if (!$emp_id || !$password) {
    echo json_encode(["success" => false, "error" => "Employee ID and password are required"]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM staff WHERE employee_id = ? AND status = 'Active'");
$stmt->execute([$emp_id]);
$staff = $stmt->fetch();

if (!$staff || $staff['password'] !== $password) {
    echo json_encode(["success" => false, "error" => "Invalid credentials"]);
    exit;
}

echo json_encode([
    "success" => true,
    "staff" => [
        "employee_id" => $staff['employee_id'],
        "name"        => $staff['name'],
        "role"        => $staff['role'],
    ]
]);
?>