<?php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$data   = json_decode(file_get_contents("php://input"), true);

if ($method === 'GET') {
    $stmt = $pdo->prepare("SELECT id, employee_id, name, email, role, status FROM staff ORDER BY id ASC");
    $stmt->execute();
    echo json_encode(["success" => true, "staff" => $stmt->fetchAll()]);

} elseif ($method === 'POST') {
    $required = ['employee_id','name','email','password','role'];
    foreach ($required as $f) {
        if (empty($data[$f])) {
            echo json_encode(["success" => false, "error" => "Missing: $f"]);
            exit;
        }
    }
    // Check unique employee_id
    $check = $pdo->prepare("SELECT id FROM staff WHERE employee_id = ?");
    $check->execute([$data['employee_id']]);
    if ($check->fetch()) {
        echo json_encode(["success" => false, "error" => "Employee ID already exists"]);
        exit;
    }
    $stmt = $pdo->prepare("INSERT INTO staff (employee_id, name, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$data['employee_id'], $data['name'], $data['email'], $data['password'], $data['role']]);
    echo json_encode(["success" => true]);

} elseif ($method === 'DELETE') {
    $id = $data['id'] ?? '';
    if (!$id) { echo json_encode(["success" => false, "error" => "ID required"]); exit; }
    $stmt = $pdo->prepare("DELETE FROM staff WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["success" => true]);

} elseif ($method === 'PUT') {
    // Update role
    $id   = $data['id']   ?? '';
    $role = $data['role'] ?? '';
    if (!$id || !$role) { echo json_encode(["success" => false, "error" => "ID and role required"]); exit; }
    $stmt = $pdo->prepare("UPDATE staff SET role = ? WHERE id = ?");
    $stmt->execute([$role, $id]);
    echo json_encode(["success" => true]);
}
?>