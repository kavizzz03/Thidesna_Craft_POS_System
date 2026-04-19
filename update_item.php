<?php
include "db.php";
header('Content-Type: application/json');

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0;

if ($id <= 0 || empty($description) || $price <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE items SET description = ?, price = ? WHERE id = ?");
    $stmt->execute([$description, $price, $id]);
    
    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>