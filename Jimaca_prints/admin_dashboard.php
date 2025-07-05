<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "printshop");

// Refresh priorities before display
include('ai_prioritize.php');

// Fetch orders sorted by priority
$result = $conn->query("SELECT * FROM orders ORDER BY priority ASC, pickup_time ASC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: left;
    }
    th {
      background-color: #222;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f5f5f5;
    }
  </style>
</head>
<body>
  <h1>🧠 AI Prioritized Orders (Admin Panel)</h1>
  <table>
    <tr>
      <th>Priority</th>
      <th>Order ID</th>
      <th>Customer Name</th>
      <th>Items</th>
      <th>Method</th>
      <th>Quantity</th>
      <th>Order Time</th>
      <th>Pickup Time</th>
      <th>Status</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['priority']; ?></td>
      <td><?php echo $row['order_id']; ?></td>
      <td><?php echo $row['customer_name']; ?></td>
      <td><?php echo $row['items']; ?></td>
      <td><?php echo $row['method']; ?></td>
      <td><?php echo $row['quantity']; ?></td>
      <td><?php echo $row['order_time']; ?></td>
      <td><?php echo $row['pickup_time']; ?></td>
      <td><?php echo $row['status']; ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
