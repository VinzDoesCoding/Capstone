<?php
session_start();
$conn = new mysqli("localhost", "root", "", "printshop");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customer_name = "Guest";
$customer_id = $_SESSION['customer_id'] ?? uniqid('guest_');

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    http_response_code(400);
    echo "❌ Cart is empty.";
    exit;
}

foreach ($_SESSION['cart'] as $item) {
    $order_id = uniqid("ORD");
    $items = $item['product'] . " - " . $item['color'] . ", " . $item['size'];

    // OPTIONAL: You can adjust this based on product type
    $method = in_array($item['product'], ['T-SHIRT', 'CAP']) ? "Silkscreen" : "DTF";

    $quantity = (int)$item['quantity'];
    $order_time = date("Y-m-d H:i:s");
    $pickup_time = date("Y-m-d H:i:s", strtotime("+2 hours"));
    $status = "In Queue";

    $stmt = $conn->prepare("INSERT INTO orders (order_id, customer_name, items, method, quantity, order_time, pickup_time, status, customer_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssissss", $order_id, $customer_name, $items, $method, $quantity, $order_time, $pickup_time, $status, $customer_id);
    $stmt->execute();
}

$_SESSION['cart'] = []; // clear cart
http_response_code(200);
echo "✅ Order submitted.";
?>
