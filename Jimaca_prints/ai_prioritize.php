<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Add DB connection
$conn = new mysqli("localhost", "root", "", "printshop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Priority calculation function
function calculate_priority($order) {
    $priority = 0;
    $now = time();
    $pickup = strtotime($order['pickup_time']);
    $time_left = ($pickup - $now) / 60;

    if ($time_left < 60) {
        $priority += 1;
    } elseif ($time_left < 180) {
        $priority += 2;
    } else {
        $priority += 3;
    }

    if ($order['method'] == 'Silkscreen') {
        $priority += 2;
    } elseif ($order['method'] == 'DTF') {
        $priority += 1;
    }

    return $priority;
}

// ✅ Update all orders with computed priority
$orders = $conn->query("SELECT * FROM orders");
while ($order = $orders->fetch_assoc()) {
    $priority = calculate_priority($order);
    $order_id = $conn->real_escape_string($order['order_id']);
    $conn->query("UPDATE orders SET priority = $priority WHERE order_id = '$order_id'");
}

echo "✅ AI prioritization ran & priorities updated.";
?>
