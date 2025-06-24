<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$db = "printshop"; // Change to your DB name

$conn = new mysqli("localhost", "root", "", "printshop");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM orders ORDER BY order_time DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<div class='order'>";
    echo "<strong>Order ID:</strong> " . $row['order_id'] . "<br>";
    echo "<strong>Customer:</strong> " . $row['customer_name'] . "<br>";
    echo "<strong>Items:</strong> " . $row['items'] . "<br>";
    echo "<strong>Method:</strong> " . $row['method'] . "<br>";
    echo "<strong>Quantity:</strong> " . $row['quantity'] . "<br>";
    echo "<strong>Pickup Time:</strong> " . $row['pickup_time'] . "<br>";
    echo "<strong>Status:</strong> " . $row['status'] . "<br>";
    echo "</div><hr>";
  }
} else {
  echo "No orders found.";
}
$conn->close();

$product = $_POST['product'];
$quantity = (int)$_POST['quantity'];
$color = $_POST['color'];
$size = isset($_POST['size']) ? $_POST['size'] : 'N/A';
$customer_name = "Guest"; // Or collect from a login/session in future

$items = "$product - $color, $size";
$method = "Silkscreen"; // Optional: You can add a dropdown for DTF vs Silkscreen
$order_id = uniqid("ORD");
$order_time = date("Y-m-d H:i:s");
$pickup_time = date("Y-m-d H:i:s", strtotime("+2 hours"));
$status = "In Queue";

$sql = "INSERT INTO orders (order_id, customer_name, items, method, quantity, order_time, pickup_time, status)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssisss", $order_id, $customer_name, $items, $method, $quantity, $order_time, $pickup_time, $status);

if ($stmt->execute()) {
  // Redirect back with success message
  header("Location: order.html?success=1");
  exit();
} else {
  // Redirect back with error message
  header("Location: order.html?error=1");
  exit();
}


?>
