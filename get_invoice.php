<?php
include "db.php";
header('Content-Type: application/json');

$invoice_no = isset($_GET['invoice_no']) ? trim($_GET['invoice_no']) : '';

if (empty($invoice_no)) {
    echo json_encode(['error' => 'Invoice number required']);
    exit;
}

try {
    // Get invoice details
    $stmt = $pdo->prepare("SELECT * FROM invoices WHERE invoice_no = ?");
    $stmt->execute([$invoice_no]);
    $invoice = $stmt->fetch();
    
    if (!$invoice) {
        echo json_encode(['error' => 'Invoice not found']);
        exit;
    }
    
    // Get invoice items
    $stmt = $pdo->prepare("SELECT * FROM invoice_items WHERE invoice_no = ? ORDER BY id");
    $stmt->execute([$invoice_no]);
    $items = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'invoice' => $invoice,
        'items' => $items,
        'item_count' => count($items)
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>