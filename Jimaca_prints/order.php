<?php
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
?>
