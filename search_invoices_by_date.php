<?php
include "db.php";
header('Content-Type: application/json');

$start_date = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

try {
    if (!empty($start_date) && !empty($end_date)) {
        $stmt = $pdo->prepare("
            SELECT invoice_no, final_total, created_at 
            FROM invoices 
            WHERE DATE(created_at) BETWEEN ? AND ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$start_date, $end_date]);
    } else {
        $stmt = $pdo->prepare("
            SELECT invoice_no, final_total, created_at 
            FROM invoices 
            ORDER BY created_at DESC 
            LIMIT 100
        ");
        $stmt->execute();
    }
    
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