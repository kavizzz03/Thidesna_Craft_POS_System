<?php
include "db.php";
header('Content-Type: application/json');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (empty($search)) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT item_code, description, price 
        FROM items 
        WHERE item_code LIKE ? OR description LIKE ? 
        ORDER BY description 
        LIMIT 10
    ");
    $stmt->execute(["%$search%", "%$search%"]);
    $items = $stmt->fetchAll();
    
    echo json_encode($items);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>