<?php
include "db.php";
header('Content-Type: application/json');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (empty($search)) {
    echo json_encode(null);
    exit;
}

try {
    // Search by item_code or description
    $stmt = $pdo->prepare("SELECT * FROM items WHERE item_code LIKE ? OR description LIKE ? LIMIT 1");
    $stmt->execute(["%$search%", "%$search%"]);
    $item = $stmt->fetch();
    
    if ($item) {
        echo json_encode([
            'item_code' => $item['item_code'],
            'description' => $item['description'],
            'price' => $item['price']
        ]);
    } else {
        echo json_encode(null);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>