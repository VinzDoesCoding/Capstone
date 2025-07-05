<?php
session_start();
header('Content-Type: application/json');

$customer_id = $_SESSION['customer_id'] ?? uniqid("guest_");

// Connect to DB
$conn = new mysqli("localhost", "root", "", "printshop");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'DB connection failed']);
    exit;
}

// Get POST data
$product = $_POST['product'] ?? '';
$quantity = (int)($_POST['quantity'] ?? 1);
$color = $_POST['color'] ?? '';
$size = $_POST['size'] ?? 'N/A';

$items = "$product - $color - $size";
$method = ($product === "T-SHIRT" || $product === "CAP") ? 'Silkscreen' : 'DTF';
$order_id = uniqid("ORD");
$order_time = date("Y-m-d H:i:s");
$pickup_time = date("Y-m-d H:i:s", strtotime("+6 hours"));
$status = "Pending";
$customer_name = "Guest"; // Or replace with real login if available

// Insert into orders table
$stmt = $conn->prepare("INSERT INTO orders (order_id, customer_id, customer_name, items, method, quantity, order_time, pickup_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssiiss", $order_id, $customer_id, $customer_name, $items, $method, $quantity, $order_time, $pickup_time, $status);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
