<?php
include "db.php";
header('Content-Type: application/json');

$invoice_no = isset($_GET['invoice_no']) ? trim($_GET['invoice_no']) : '';

if (empty($invoice_no)) {
    echo json_encode(['error' => 'Invoice number required']);
    exit;
}

try {
    // Get invoice with item count
    $stmt = $pdo->prepare("
        SELECT i.*, 
               COUNT(ii.id) as total_items,
               SUM(ii.quantity) as total_quantity
        FROM invoices i
        LEFT JOIN invoice_items ii ON i.invoice_no = ii.invoice_no
        WHERE i.invoice_no = ?
        GROUP BY i.id
    ");
    $stmt->execute([$invoice_no]);
    $invoice = $stmt->fetch();
    
    if (!$invoice) {
        echo json_encode(['error' => 'Invoice not found']);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'invoice' => $invoice
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>