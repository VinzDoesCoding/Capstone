<?php
session_start();
header('Content-Type: application/json');

// Update name/email/phone in existing cart items
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone'])) {
  foreach ($_SESSION['cart'] as &$item) {
    $item['customer_name'] = $_POST['name'];
    $item['email'] = $_POST['email'];
    $item['phone'] = $_POST['phone'];
  }
  unset($item); // break reference
}

$cart = $_SESSION['cart'] ?? [];
$response = [];

foreach ($cart as $index => $item) {
  $response[] = [
    'index' => $index,
    'product' => $item['product'],
    'color' => $item['color'],
    'size' => $item['size'],
    'customer_name' => $item['customer_name'] ?? 'Guest',  // ✅ use updated cart value
    'method' => 'DTF',  // or use $item['method'] if dynamic
    'quantity' => $item['quantity'],
    'pickup_time' => $item['pickup_time'] ?? date("H:i", strtotime("+2 hours"))
  ];
}

echo json_encode($response);
