<?php
include "db.php";
header('Content-Type: application/json');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = intval($_GET['limit'] ?? 100);

try {
    $sql = "SELECT invoice_no, final_total, created_at FROM invoices";
    $params = [];
    
    if (!empty($search)) {
        $sql .= " WHERE invoice_no LIKE ?";
        $params = ["%$search%"];
    }
    
    $sql .= " ORDER BY created_at DESC LIMIT ?";
    $params[] = $limit;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $invoices = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'invoices' => $invoices,
        'count' => count($invoices)
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
}
?>