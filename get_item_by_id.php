<?php
include "db.php";
header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(null);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, item_code, description, price FROM items WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    echo json_encode($item);
} catch(PDOException $e) {
    echo json_encode(null);
}
?>