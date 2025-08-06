<?php
session_start();
$conn = new mysqli("localhost", "root", "", "printshop");

$customer_name = $_SESSION['customer_name'] ?? "Guest";
$customer_id = $_SESSION['customer_id'] ?? uniqid('guest_');

foreach ($_SESSION['cart'] as $item) {
    $order_id = uniqid("ORD");
    $items = $item['product'] . " - " . $item['color'] . ", " . $item['size'];
    $method = in_array($item['product'], ['T-SHIRT', 'CAP']) ? "Silkscreen" : "DTF";
    $quantity = (int)$item['quantity'];
    $order_time = date("Y-m-d H:i:s");
    $pickup_time = date("Y-m-d H:i:s", strtotime("+2 hours"));
    $status = "In Queue";

    $stmt = $conn->prepare("INSERT INTO orders (order_id, customer_name, items, method, quantity, order_time, pickup_time, status, customer_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssissss", $order_id, $customer_name, $items, $method, $quantity, $order_time, $pickup_time, $status, $customer_id);
    $stmt->execute();
}

$_SESSION['cart'] = [];
header("Location: ordersite.php?success=1");
exit();