<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Connect to the database
$conn = new mysqli("localhost", "root", "", "printshop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ New Smart Priority Function
function calculate_priority($order) {
    $now = time();
    $pickup = strtotime($order['pickup_time']);
    $time_left = ($pickup - $now) / 60; // in minutes

    // Normalize time_left into tiers
    if ($time_left <= 60) {
        $time_score = 1; // very urgent
    } elseif ($time_left <= 180) {
        $time_score = 2;
    } elseif ($time_left <= 1440) { // within 24 hours
        $time_score = 3;
    } else {
        $time_score = 4;
    }

    // Method weight: Silkscreen is more complex
    $method_score = strtolower($order['method']) === 'silkscreen' ? 1 : 2;

    // Quantity weight: more quantity = more time required
    $quantity = (int)$order['quantity'];
    if ($quantity <= 5) {
        $quantity_score = 1;
    } elseif ($quantity <= 15) {
        $quantity_score = 2;
    } else {
        $quantity_score = 3;
    }

    // Final priority: Lower total = higher urgency
    $priority = $time_score + $method_score + $quantity_score;
    return $priority;
}

// ✅ Recalculate and update all orders
$orders = $conn->query("SELECT * FROM orders");
while ($order = $orders->fetch_assoc()) {
    if (strtolower((string)($order['status'] ?? '')) === 'completed') {
        continue; // ⛔ Skip completed orders
    }

    $priority = calculate_priority($order);
    $order_id = $conn->real_escape_string($order['order_id']);
    $conn->query("UPDATE orders SET priority = $priority WHERE order_id = '$order_id'");
}


echo "✅ AI prioritization updated successfully.";
?>
