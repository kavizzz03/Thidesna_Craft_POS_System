<?php
include "db.php";
header('Content-Type: application/json');

try {
    // Get total items count
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM items");
    $total_items = $stmt->fetch()['total'];
    
    // Get total bills count
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM invoices");
    $total_bills = $stmt->fetch()['total'];
    
    // Get today's sales
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(final_total), 0) as total FROM invoices WHERE DATE(created_at) = CURDATE()");
    $stmt->execute();
    $today_sales = $stmt->fetch()['total'];
    
    // Get monthly sales
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(final_total), 0) as total FROM invoices WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
    $stmt->execute();
    $monthly_sales = $stmt->fetch()['total'];
    
    // Get last 7 days sales for chart
    $stmt = $pdo->prepare("
        SELECT DATE(created_at) as date, COALESCE(SUM(final_total), 0) as total 
        FROM invoices 
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    $stmt->execute();
    $last_7_days_raw = $stmt->fetchAll();
    
    // Fill missing dates with 0
    $sales_data = [];
    for($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $found = false;
        foreach($last_7_days_raw as $day) {
            if($day['date'] == $date) {
                $sales_data[] = ['date' => date('M d', strtotime($date)), 'total' => floatval($day['total'])];
                $found = true;
                break;
            }
        }
        if(!$found) {
            $sales_data[] = ['date' => date('M d', strtotime($date)), 'total' => 0];
        }
    }
    
    // Get top selling items from invoice_items table
    $stmt = $pdo->prepare("
        SELECT description, SUM(total) as total_sales 
        FROM invoice_items 
        GROUP BY description 
        ORDER BY total_sales DESC 
        LIMIT 5
    ");
    $stmt->execute();
    $top_items_raw = $stmt->fetchAll();
    
    // Format top items with proper data types
    $top_items = [];
    foreach($top_items_raw as $item) {
        $top_items[] = [
            'description' => $item['description'],
            'total_sales' => floatval($item['total_sales'])
        ];
    }
    
    // Get recent invoices
    $stmt = $pdo->prepare("
        SELECT invoice_no, final_total, created_at 
        FROM invoices 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $recent_invoices_raw = $stmt->fetchAll();
    
    // Format recent invoices with proper data types
    $recent_invoices = [];
    foreach($recent_invoices_raw as $inv) {
        $recent_invoices[] = [
            'invoice_no' => $inv['invoice_no'],
            'final_total' => floatval($inv['final_total']),
            'created_at' => $inv['created_at']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'total_items' => intval($total_items),
        'total_bills' => intval($total_bills),
        'today_sales' => floatval($today_sales),
        'monthly_sales' => floatval($monthly_sales),
        'last_7_days' => $sales_data,
        'top_items' => $top_items,
        'recent_invoices' => $recent_invoices
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
}
?>