<?php
session_start();
$customer_id = $_SESSION['customer_id'];

$conn = new mysqli("localhost", "root", "", "printshop");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE orders SET status = 'Confirmed' WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customer_id);

if ($stmt->execute()) {
  echo "✅ Order confirmed successfully!";
} else {
  echo "❌ Failed to confirm order.";
}
?>
