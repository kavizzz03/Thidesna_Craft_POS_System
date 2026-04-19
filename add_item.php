<?php
include "db.php";
header('Content-Type: application/json');

$item_code = isset($_POST['item_code']) ? trim($_POST['item_code']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0;

if (empty($description) || $price <= 0) {
    echo json_encode(['error' => 'Invalid item data']);
    exit;
}

// Generate item code if not provided
if (empty($item_code)) {
    $prefix = 'ITEM';
    $date = date('ymd');
    $random = rand(100, 999);
    $item_code = $prefix . $date . $random;
}

try {
    // Check if item code already exists
    $stmt = $pdo->prepare("SELECT id FROM items WHERE item_code = ?");
    $stmt->execute([$item_code]);
    if ($stmt->fetch()) {
        // Generate new code if duplicate
        $item_code = $prefix . $date . rand(100, 999);
    }
    
    $stmt = $pdo->prepare("INSERT INTO items (item_code, description, price) VALUES (?, ?, ?)");
    $stmt->execute([$item_code, $description, $price]);
    
    echo json_encode([
        'success' => true,
        'item_code' => $item_code,
        'description' => $description,
        'price' => $price,
        'id' => $pdo->lastInsertId()
    ]);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>