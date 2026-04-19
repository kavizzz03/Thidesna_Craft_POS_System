<?php
include 'db.php';

header('Content-Type: application/json');

// 🔹 Get last item code (FIXED sorting)
$query = "SELECT item_code 
          FROM items 
          ORDER BY CAST(SUBSTRING(item_code,4) AS UNSIGNED) DESC 
          LIMIT 1";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_code = $row['item_code'];

    $number = intval(substr($last_code, 3)) + 1;
} else {
    $number = 1;
}

// Generate new code
$item_code = 'ITM' . str_pad($number, 4, '0', STR_PAD_LEFT);

// 🔹 Get POST data safely
$description = trim($_POST['description'] ?? '');
$price = floatval($_POST['price'] ?? 0);

// 🔹 Validation
if ($description === '' || $price <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid description or price"
    ]);
    exit;
}

// 🔹 Optional: prevent duplicate description
$check = $conn->prepare("SELECT item_code FROM items WHERE description = ?");
$check->bind_param("s", $description);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows > 0) {
    $existing = $check_result->fetch_assoc();

    echo json_encode([
        "status" => "exists",
        "message" => "Item already exists",
        "item_code" => $existing['item_code']
    ]);
    exit;
}

// 🔹 Insert item
$sql = "INSERT INTO items (item_code, description, price)
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssd", $item_code, $description, $price);

if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" => "Item added successfully",
        "item_code" => $item_code,
        "description" => $description,
        "price" => $price
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}

// Close
$stmt->close();
$conn->close();
?>