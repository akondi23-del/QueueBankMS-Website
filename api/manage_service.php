<?php
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Add service
    if (empty($data['name']) || empty($data['duration'])) {
        echo json_encode(["success" => false, "error" => "Name and duration are required"]);
        exit;
    }
    $stmt = $pdo->prepare("INSERT INTO services (icon, name, description, duration) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $data['icon'] ?? '🏦',
        $data['name'],
        $data['description'] ?? '',
        $data['duration']
    ]);
    echo json_encode(["success" => true, "id" => $pdo->lastInsertId()]);

} elseif ($method === 'DELETE') {
    // Delete service
    $id = $data['id'] ?? '';
    if (!$id) {
        echo json_encode(["success" => false, "error" => "Service ID required"]);
        exit;
    }
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["success" => true]);
}
?>