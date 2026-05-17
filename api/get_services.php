<?php
require_once 'db.php';

$stmt = $pdo->prepare("SELECT * FROM services ORDER BY id ASC");
$stmt->execute();
$services = $stmt->fetchAll();
echo json_encode(["success" => true, "services" => $services]);
?>