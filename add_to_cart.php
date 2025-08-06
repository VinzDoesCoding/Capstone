<?php
session_start();

// Get POST data
$product = $_POST['product'] ?? '';
$quantity = $_POST['quantity'] ?? 1;
$color = $_POST['color'] ?? '';
$size = $_POST['size'] ?? '';
$customer_name = $_POST['name'] ?? 'Guest';
$pickup_time = $_POST['pickup_time'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$method = $_POST['method'] ?? 'DTF'; // <-- Add this line

// Create cart item array
$item = [
  'product' => $product,
  'quantity' => $quantity,
  'color' => $color,
  'size' => $size,
  'customer_name' => $customer_name,
  'pickup_time' => $pickup_time,
  'phone' => $phone,
  'email' => $email,
  'method' => $method // <-- Add this line
];

// Store in session
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

$_SESSION['cart'][] = $item;

// Return JSON response
echo json_encode(['success' => true]);
?>
