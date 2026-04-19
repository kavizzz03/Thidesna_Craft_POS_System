<?php
include "db.php";
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['items']) || empty($data['items'])) {
    echo json_encode(['error' => 'Invalid invoice data']);
    exit;
}

try {
    $pdo->beginTransaction();
    
    // Generate unique invoice number
    $invoice_no = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
    
    // Calculate totals
    $subtotal = 0;
    foreach ($data['items'] as $item) {
        $item_total = ($item['qty'] * $item['price']) * (1 - $item['discount'] / 100);
        $subtotal += $item_total;
    }
    
    $bill_discount_percent = floatval($data['bill_discount']);
    $bill_discount_amount = $subtotal * ($bill_discount_percent / 100);
    $after_discount = $subtotal - $bill_discount_amount;
    
    $tax_percent = floatval($data['tax']);
    $tax_amount = $after_discount * ($tax_percent / 100);
    $final_total = $after_discount + $tax_amount;
    
    // Insert invoice
    $stmt = $pdo->prepare("
        INSERT INTO invoices (invoice_no, subtotal, bill_discount_percent, bill_discount_amount, tax_percent, tax_amount, final_total) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$invoice_no, $subtotal, $bill_discount_percent, $bill_discount_amount, $tax_percent, $tax_amount, $final_total]);
    
    // Insert invoice items
    $stmt = $pdo->prepare("
        INSERT INTO invoice_items (invoice_no, item_code, description, quantity, price, discount_percent, total) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    foreach ($data['items'] as $item) {
        $item_total = ($item['qty'] * $item['price']) * (1 - $item['discount'] / 100);
        $stmt->execute([
            $invoice_no,
            $item['item_code'],
            $item['description'],
            $item['qty'],
            $item['price'],
            $item['discount'],
            $item_total
        ]);
    }
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'invoice' => $invoice_no,
        'final_total' => $final_total
    ]);
    
} catch(Exception $e) {
    $pdo->rollBack();
    echo json_encode(['error' => $e->getMessage()]);
}
?>