<?php
session_start();
header('Content-Type: application/json');

$cart = $_SESSION['cart'] ?? [];
$response = [];

foreach ($cart as $item) {
  $response[] = [
    'items' => $item['product'] . ' - ' . $item['color'] . ', ' . $item['size'],
    'customer_name' => 'Guest',
    'method' => 'DTF',
    'quantity' => $item['quantity'],
    'pickup_time' => date("H:i", strtotime("+2 hours"))
  ];
}

echo json_encode($response);
