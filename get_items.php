<?php
include "db.php";
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT id, item_code, description, price, created_at FROM items ORDER BY id DESC");
    $items = $stmt->fetchAll();
    echo json_encode($items);
} catch(PDOException $e) {
    echo json_encode([]);
}
?>