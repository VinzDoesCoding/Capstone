<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "printshop");

if ($conn->connect_error) {
    echo json_encode(["error" => "❌ Database connection failed."]);
    exit;
}

$order_id = $_GET['order_id'] ?? '';
if (empty($order_id)) {
    echo json_encode(["error" => "⚠️ Order ID missing."]);
    exit;
}

$order_id = $conn->real_escape_string($order_id);

// Function to fetch order data and return JSON
function fetchOrder($conn, $table, $order_id, $statusOverride = null) {
    $sql = "SELECT * FROM $table WHERE order_id = '$order_id'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return [
            "order_id" => $row['order_id'],
            "status" => $statusOverride ?? $row["status"],
            "customer_name" => $row["customer_name"],
            "items" => $row["items"],
            "pickup_time" => $row["pickup_time"],
            "payment_method" => $row["payment_method"] ?? 'N/A',
            "order_time" => $row["order_time"],
            "design_file" => $row["design_file"]
        ];
    }
    return null;
}

// Search in orders
$data = fetchOrder($conn, 'orders', $order_id);

// If not found, check completed_orders
if (!$data) {
    $data = fetchOrder($conn, 'completed_orders', $order_id, 'Completed');
}

// If not found, check canceled_orders
if (!$data) {
    $data = fetchOrder($conn, 'canceled_orders', $order_id, 'Canceled');
}

if ($data) {
    echo json_encode($data);
} else {
    echo json_encode(["error" => "❌ Order not found."]);
}

$conn->close();
?>
