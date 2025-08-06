<?php
session_start();

if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "printshop");

// Refresh priorities before display
include('ai_prioritize.php');

// Fetch orders sorted by priority
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$sql = "SELECT * FROM orders";

if (!empty($statusFilter)) {
    $statusFilter = $conn->real_escape_string($statusFilter);
    $sql .= " WHERE status = '$statusFilter'";
}

$sql .= " ORDER BY priority ASC, pickup_time ASC";

$completed_result = $conn->query("SELECT * FROM completed_orders ORDER BY pickup_time DESC");
$canceled_result = $conn->query("SELECT * FROM canceled_orders ORDER BY canceled_at DESC");

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <style>
body {
  font-family: Arial, sans-serif;
  padding: 20px;
  transition: background-color 0.2s, color 0.2s;
}

h1 {
  color: var(--accent);
}

/* Light Mode */
body.light-mode {
  background-image: url(invbg.png);background-size: cover; background-position: center;;
  color: #111;
}

body.light-mode table {
  background-color: #fff;
  color: #111;
  border: 1px solid #ccc;
}

body.light-mode th,
body.light-mode td {
  background-color: #eee;
  color: #1e1e1e;
}

body.light-mode tr:nth-child(even) {
  background-color: #f9f9f9;
}

body.light-mode tr:hover {
  background-color: #e5e5e5;
}

body.light-mode select, body.light-mode button {
  background-color: #f0f0f0;
  color: #111;
  border: 1px solid #999;
}

/* Dark Mode */
body.dark-mode {
  background-image: url(bg.png);background-size: cover; background-position: center;;
  color: #f1f1f1;
  --accent: #00d1ff;
}

body.dark-mode table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  background-color: #1e1e1e;
  border: 1px solid #333;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 0 15px rgba(0,0,0,0.3);
}

body.dark-mode th, 
body.dark-mode td {
  background-color: #2d2d2d;
  color: #ffffff;
  border-bottom: 2px solid #444;
}

body.dark-mode tr {
  border-bottom: 1px solid #333;
}

body.dark-mode tr:nth-child(even) {
  background-color: #252525;
}

body.dark-mode tr:hover {
  background-color: #2c2c2c;
  color: #ffffff;
}

body.dark-mode select,
body.dark-mode button {
  background-color: #333;
  color: #fff;
  border: 1px solid #555;
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 14px;
}

body.dark-mode select:hover,
body.dark-mode button:hover {
  background-color: #444;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  background-color: #1e1e1e;
  border: 1px solid #333;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 0 15px rgba(0,0,0,0.3);
}

th, td {
  padding: 12px 16px;
  text-align: left;
}

th {
  background-color: #2d2d2d;
  color: #ffffff;
  border-bottom: 2px solid #444;
}

tr {
  border-bottom: 1px solid #333;
}

tr:nth-child(even) {
  background-color: #252525;
}

tr:hover {
  background-color: #2c2c2c;
}

select, button {
  background-color: #333;
  color: #fff;
  border: 1px solid #555;
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 14px;
}

select:hover, button:hover {
  background-color: #444;
}

button {
  cursor: pointer;
}

/* Pill styling */
.pill {
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 0.9rem;
  font-weight: bold;
  display: inline-block;
  min-width: 90px;
  text-align: center;
}

/* Method Pills */
.pill.method-dtf {
  background-color: #1e5980ff;
  color: white;
}

.pill.method-silkscreen {
  background-color: #6a3d7aff;
  color: white;
}

/* Payment Method Pills */
.pill.payment-gcash {
  background-color: #1824ccff;
  color: white;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 600;
  display: inline-block;
  text-transform: uppercase;
}

.pill.payment-chinabank {
  background-color: #b94747;
  color: white;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 600;
  display: inline-block;
  text-transform: uppercase;
}

/* Dark Mode Pills */
body.dark-mode .pill.in-queue {
  background-color: #b33939;
  color: #fff;
}
body.dark-mode .pill.processing {
  background-color: #d4a017;
  color: #fff;
}
body.dark-mode .pill.completed {
  background-color: #2ecc71;
  color: #fff;
}

/* Light Mode Pills */
body.light-mode .pill.in-queue {
  background-color: #ffc2c2;
  color: #1e1e1e;
}
body.light-mode .pill.processing {
  background-color: #fff2b2;
  color: #1e1e1e;
}
body.light-mode .pill.completed {
  background-color: #c2f7e1;
  color: #1e1e1e;
}

.status-in-queue {
  color: orange;
}
.status-processing {
  color: yellowgreen;
}
.status-completed {
  color: lightgreen;
}

  </style>
</head>
<body>

  <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
  <a href="logout.php" style="color:red;">Logout</a>

  <label style="float: right;">
  <input type="checkbox" id="modeToggle" onchange="toggleMode()" style="transform: scale(1.5); margin-right: 8px;">
  <span style="font-size: 1rem;">🌙 Dark Mode</span>
  </label>

  <!-- Export individual tables -->
<button onclick="exportTableToExcel('ordersTable', 'ActiveOrders')">Export Active Orders</button>
<button onclick="exportTableToExcel('completedTable', 'CompletedOrders')">Export Completed Orders</button>
<button onclick="exportTableToExcel('canceledTable', 'CanceledOrders')">Export Canceled Orders</button>

<!-- Export all tables -->
<form method="POST" action="export_all.php" style="display:inline;">
    <button type="submit">Export All Tables</button>
</form>


  <h1>🧠 AI Prioritized Orders (Admin Panel)</h1>

  <form method="GET" style="margin-top: 20px;">
  <label>Filter by Status:</label>
  <select name="status">
    <option value="">All</option>
    <option value="In Queue">In Queue</option>
    <option value="Completed">Completed</option>
    <option value="Processing">Processing</option>
  </select>
    <button type="submit">Apply</button>
  </form>

  <table id="ordersTable">
    <tr>
      <th>Priority</th>
      <th>Order ID</th>
      <th>Customer Name</th>
      <th>Items</th>
      <th>Method</th>
      <th>Quantity</th>
      <th>Order Time</th>
      <th>Pickup Time</th>
      <th>Phone</th>
      <th>Email</th>
      <th>Design File</th>
      <th>Payment Method</th>
      <th>Payment Proof</th>
      <th>Status</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr class="status-<?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>">
      <td><?php echo $row['priority']; ?></td>
      <td><?php echo $row['order_id']; ?></td>
      <td><?php echo $row['customer_name']; ?></td>
      <td><?php echo $row['items']; ?></td>
      <td>
        <span class="pill method-<?php echo strtolower($row['method']); ?>">
          <?php echo $row['method']; ?>
        </span>
      </td>
      <td><?php echo $row['quantity']; ?></td>
      <td><?php echo $row['order_time']; ?></td>
      <td><?php echo $row['pickup_time']; ?></td>
      <td><?php echo htmlspecialchars($row['phone']); ?></td>
      <td><?php echo htmlspecialchars($row['email']); ?></td>
      <td>
  <?php if (!empty($row['design_file'])): ?>
    <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $row['design_file'])): ?>
        <img src="<?php echo htmlspecialchars($row['design_file']); ?>" width="80">
    <?php else: ?>
        <a href="<?php echo htmlspecialchars($row['design_file']); ?>" download>Download</a>
    <?php endif; ?>
<?php else: ?>
    <em>No design uploaded</em>
<?php endif; ?>
</td>
<td style="text-align: center;">
  <?php
    $method = strtolower($row['payment_method']);
  ?>
  <?php if ($method === 'gcash'): ?>
    <span class="pill payment-gcash">GCASH</span>
  <?php elseif ($method === 'chinabank'): ?>
    <span class="pill payment-chinabank">CHINABANK</span>
  <?php else: ?>
    <span style="color: gray;">N/A</span>
  <?php endif; ?>
</td>
<td>
  <?php if (!empty($row['payment_proof'])): ?>
    <a href="<?= htmlspecialchars($row['payment_proof']) ?>" target="_blank">
      <img src="<?= htmlspecialchars($row['payment_proof']) ?>" alt="Proof" style="max-height: 80px; border-radius: 6px; border: 1px solid #ccc;">
    </a>
  <?php else: ?>
    <span style="color: gray;">N/A</span>
  <?php endif; ?>
</td>

<!-- Status Column -->
<td>
  <form method="POST" action="update_status.php" style="display: flex; gap: 8px; align-items: center;">
    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
    <span class="pill <?php echo strtolower(str_replace(' ', '-', trim($row['status']))); ?>">
      <?php echo $row['status']; ?>
    </span>
    <select name="status">
      <option value="In Queue" <?php if ($row['status'] == 'In Queue') echo 'selected'; ?>>In Queue</option>
      <option value="Processing" <?php if ($row['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
      <option value="Completed" <?php if ($row['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
      <option value="Canceled" <?php if ($row['status'] == 'Canceled') echo 'selected'; ?>>Canceled</option>
    </select>
    <button type="submit">Update</button>
  </form>
</td>
    </tr>
<?php endwhile; ?>
  </table>

<h2 style="margin-top: 60px;">📏 View Size Chart</h2>
<select id="chartSelector" onchange="showChart()" style="margin-bottom: 20px;">
  <option value="">-- Select Product --</option>
  <option value="T-SHIRT">T-SHIRT</option>
  <option value="CAP">CAP</option>
  <option value="BEDROOM SLIPPERS">BEDROOM SLIPPERS</option>
  <option value="SCHOOL/OFFICE UNIFORMS">SCHOOL/OFFICE UNIFORMS</option>
  <option value="BASKETBALL UNIFORMS">BASKETBALL UNIFORMS</option>
  <option value="JOGGING PANTS">JOGGING PANTS</option>
  <option value="OVERALLS">OVERALLS</option>
  <option value="JACKETS">JACKETS</option>
  <option value="LONGSLEEVE">LONGSLEEVE</option>
  <option value="THROWPILLOWS">THROWPILLOWS</option>
  <option value="BEDDINGS">BEDDINGS</option>
  <option value="CANVAS BAGS & POUCHES">CANVAS BAGS & POUCHES</option>
  <option value="NON-WOVEN BAGS">NON-WOVEN BAGS</option>
  <option value="KRAFT PAPER BAGS">KRAFT PAPER BAGS</option>
  <option value="ID LACE/LANYARDS">ID LACE/LANYARDS</option>
</select>

<div id="chartDisplay"></div>

<h2 style="margin-top: 60px;">✅ Completed Orders</h2>
<table id="completedTable">
  <tr>
    <th>Order ID</th>
    <th>Customer Name</th>
    <th>Items</th>
    <th>Method</th>
    <th>Quantity</th>
    <th>Order Time</th>
    <th>Pickup Time</th>
    <th>Phone</th>
    <th>Email</th>
    <th>Design File</th>
    <th>Payment Method</th>
    <th>Payment Proof</th>
  </tr>
  <?php while($row = $completed_result->fetch_assoc()): ?>
 <tr>
  <td><?= $row['order_id'] ?></td>
  <td><?= $row['customer_name'] ?></td>
  <td><?= $row['items'] ?></td>
  <td>
    <span class="pill method-<?= strtolower($row['method']) ?>">
     <?= $row['method'] ?>
    </span>
  </td>
  <td><?= $row['quantity'] ?></td>
  <td><?= $row['order_time'] ?></td>
  <td><?= $row['pickup_time'] ?></td>
  <td><?= $row['phone'] ?></td>
  <td><?= $row['email'] ?></td>
  <td>
    <?php if (!empty($row['design_file'])): ?>
    <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $row['design_file'])): ?>
        <img src="<?php echo htmlspecialchars($row['design_file']); ?>" width="80">
    <?php else: ?>
        <a href="<?php echo htmlspecialchars($row['design_file']); ?>" download>Download</a>
    <?php endif; ?>
<?php else: ?>
    <em>No design uploaded</em>
<?php endif; ?>
  </td>
<td style="text-align: center;">
  <?php
    $method = strtolower($row['payment_method']);
  ?>
  <?php if ($method === 'gcash'): ?>
    <span class="pill payment-gcash">GCASH</span>
  <?php elseif ($method === 'chinabank'): ?>
    <span class="pill payment-chinabank">CHINABANK</span>
  <?php else: ?>
    <span style="color: gray;">N/A</span>
  <?php endif; ?>
</td>
<td>
  <?php if (!empty($row['payment_proof'])): ?>
    <a href="<?= htmlspecialchars($row['payment_proof']) ?>" target="_blank">
      <img src="<?= htmlspecialchars($row['payment_proof']) ?>" alt="Proof" style="max-height: 80px; border-radius: 6px; border: 1px solid #ccc;">
    </a>
  <?php else: ?>
    <span style="color: gray;">N/A</span>
  <?php endif; ?>
</td>

</tr>

  <?php endwhile; ?>
</table>

<h2 style="margin-top: 60px; color: red;">❌ Canceled Orders</h2>
<table id="canceledTable">
  <tr>
    <th>Order ID</th>
    <th>Customer Name</th>
    <th>Items</th>
    <th>Method</th>
    <th>Quantity</th>
    <th>Order Time</th>
    <th>Pickup Time</th>
    <th>Phone</th>
    <th>Email</th>
    <th>Design File</th>
    <th>Payment Method</th>
    <th>Payment Proof</th>
  </tr>
  <?php while($row = $canceled_result->fetch_assoc()): ?>
 <tr>
  <td><?= $row['order_id'] ?></td>
  <td><?= $row['customer_name'] ?></td>
  <td><?= $row['items'] ?></td>
  <td>
    <span class="pill method-<?= strtolower($row['method']) ?>">
     <?= $row['method'] ?>
    </span>
  </td>
  <td><?= $row['quantity'] ?></td>
  <td><?= $row['order_time'] ?></td>
  <td><?= $row['pickup_time'] ?></td>
  <td><?= $row['phone'] ?></td>
  <td><?= $row['email'] ?></td>
  <td>
    <?php if (!empty($row['design_file'])): ?>
    <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $row['design_file'])): ?>
        <img src="<?php echo htmlspecialchars($row['design_file']); ?>" width="80">
    <?php else: ?>
        <a href="<?php echo htmlspecialchars($row['design_file']); ?>" download>Download</a>
    <?php endif; ?>
<?php else: ?>
    <em>No design uploaded</em>
<?php endif; ?>
  </td>
<td style="text-align: center;">
  <?php
    $method = strtolower($row['payment_method']);
  ?>
  <?php if ($method === 'gcash'): ?>
    <span class="pill payment-gcash">GCASH</span>
  <?php elseif ($method === 'chinabank'): ?>
    <span class="pill payment-chinabank">CHINABANK</span>
  <?php else: ?>
    <span style="color: gray;">N/A</span>
  <?php endif; ?>
</td>
<td>
  <?php if (!empty($row['payment_proof'])): ?>
    <a href="<?= htmlspecialchars($row['payment_proof']) ?>" target="_blank">
      <img src="<?= htmlspecialchars($row['payment_proof']) ?>" alt="Proof" style="max-height: 80px; border-radius: 6px; border: 1px solid #ccc;">
    </a>
  <?php else: ?>
    <span style="color: gray;">N/A</span>
  <?php endif; ?>
</td>

</tr>

  <?php endwhile; ?>
</table>


<script>

  function exportTableToExcel(tableID, filename = '') {
    var table = document.getElementById(tableID);
    var html = table.outerHTML.replace(/ /g, '%20');

    filename = filename ? filename + '.xls' : 'excel_data.xls';

    var a = document.createElement('a');
    a.href = 'data:application/vnd.ms-excel,' + html;
    a.download = filename;
    a.click();
}

  function toggleMode() {
    const body = document.body;
    const isDark = document.getElementById("modeToggle").checked;
    body.classList.toggle("dark-mode", isDark);
    body.classList.toggle("light-mode", !isDark);
    localStorage.setItem("mode", isDark ? "dark" : "light");
  }

  window.onload = function () {
    const savedMode = localStorage.getItem("mode") || "dark";
    document.body.classList.add(savedMode + "-mode");
    document.getElementById("modeToggle").checked = savedMode === "dark";
  };
  function showChart() {
  const product = document.getElementById('chartSelector').value;
  const displayDiv = document.getElementById('chartDisplay');
  displayDiv.innerHTML = sizeCharts[product] || '<p style="color:gray;">MABUHAY!</p>';
  }
  const sizeCharts = {
  'T-SHIRT': `
<h4 style="margin-top:10px;">T-Shirt Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size</th>
      <th style="padding: 10px; border: 1px solid #666;">Dimensions (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Small</td><td style="padding:8px; border: 1px solid #555;">13 x 17</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Medium</td><td style="padding:8px; border: 1px solid #555;">14 x 18</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Large</td><td style="padding:8px; border: 1px solid #555;">15 x 19</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids XL</td><td style="padding:8px; border: 1px solid #555;">16 x 20</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Teen</td><td style="padding:8px; border: 1px solid #555;">17 x 22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XS</td><td style="padding:8px; border: 1px solid #555;">18 x 24</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Small</td><td style="padding:8px; border: 1px solid #555;">19 x 25.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Medium</td><td style="padding:8px; border: 1px solid #555;">20 x 26</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Large</td><td style="padding:8px; border: 1px solid #555;">21 x 27</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XL</td><td style="padding:8px; border: 1px solid #555;">22.5 x 28.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXL</td><td style="padding:8px; border: 1px solid #555;">23.5 x 22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXXL</td><td style="padding:8px; border: 1px solid #555;">24.5 x 29</td></tr>
  </tbody>
</table>
  `,
  'SCHOOL/OFFICE UNIFORMS': `
<h4 style="margin-top:10px;">Uniforms Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size</th>
      <th style="padding: 10px; border: 1px solid #666;">Top (in)</th>
      <th style="padding: 10px; border: 1px solid #666;">Pants (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="padding:8px; border: 1px solid #555;">Small</td>
      <td style="padding:8px; border: 1px solid #555;">21 x 27</td>
      <td style="padding:8px; border: 1px solid #555;">32 x 40</td>
    </tr>
    <tr>
      <td style="padding:8px; border: 1px solid #555;">Medium</td>
      <td style="padding:8px; border: 1px solid #555;">22 x 28</td>
      <td style="padding:8px; border: 1px solid #555;">34 x 41</td>
    </tr>
    <tr>
      <td style="padding:8px; border: 1px solid #555;">Large</td>
      <td style="padding:8px; border: 1px solid #555;">23 x 29</td>
      <td style="padding:8px; border: 1px solid #555;">36 x 42</td>
    </tr>
    <tr>
      <td style="padding:8px; border: 1px solid #555;">XL</td>
      <td style="padding:8px; border: 1px solid #555;">24 x 30</td>
      <td style="padding:8px; border: 1px solid #555;">38 x 44</td>
    </tr>
    <tr>
      <td style="padding:8px; border: 1px solid #555;">XXL</td>
      <td style="padding:8px; border: 1px solid #555;">25 x 31</td>
      <td style="padding:8px; border: 1px solid #555;">40 x 46</td>
    </tr>
    <tr>
      <td style="padding:8px; border: 1px solid #555;">XXXL</td>
      <td style="padding:8px; border: 1px solid #555;">26 x 32</td>
      <td style="padding:8px; border: 1px solid #555;">42 x 48</td>
    </tr>
  </tbody>
</table>

  `,
'BASKETBALL UNIFORMS': `
<h4 style="margin-top:10px;">Basketball Uniform Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size</th>
      <th style="padding: 10px; border: 1px solid #666;">Top (in)</th>
      <th style="padding: 10px; border: 1px solid #666;">Shorts (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">XS</td>
      <td style="padding: 8px; border: 1px solid #555;">19 x 28</td>
      <td style="padding: 8px; border: 1px solid #555;">40 x 18</td>
    </tr>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">Small</td>
      <td style="padding: 8px; border: 1px solid #555;">20 x 29</td>
      <td style="padding: 8px; border: 1px solid #555;">42 x 19</td>
    </tr>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">Medium</td>
      <td style="padding: 8px; border: 1px solid #555;">21 x 30</td>
      <td style="padding: 8px; border: 1px solid #555;">44 x 20</td>
    </tr>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">Large</td>
      <td style="padding: 8px; border: 1px solid #555;">22 x 31</td>
      <td style="padding: 8px; border: 1px solid #555;">46 x 21</td>
    </tr>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">XL</td>
      <td style="padding: 8px; border: 1px solid #555;">23 x 32</td>
      <td style="padding: 8px; border: 1px solid #555;">48 x 22</td>
    </tr>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">XXL</td>
      <td style="padding: 8px; border: 1px solid #555;">24 x 33</td>
      <td style="padding: 8px; border: 1px solid #555;">50 x 23</td>
    </tr>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">XXXL</td>
      <td style="padding: 8px; border: 1px solid #555;">25 x 34</td>
      <td style="padding: 8px; border: 1px solid #555;">52 x 24</td>
    </tr>
  </tbody>
</table>

`,
  'JOGGING PANTS': `
<h4 style="margin-top:10px;">Jogging Pants Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size Range</th>
      <th style="padding: 10px; border: 1px solid #666;">Waist (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="padding:8px; border: 1px solid #555;">Small - Medium</td><td style="padding:8px; border: 1px solid #555;">24 - 44</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Large - XL</td><td style="padding:8px; border: 1px solid #555;">25 - 50</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXL - XXXL</td><td style="padding:8px; border: 1px solid #555;">28 - 56</td></tr>
  </tbody>
</table>
  `,
  'OVERALLS': `
<h4 style="margin-top:10px;">Overalls Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size</th>
      <th style="padding: 10px; border: 1px solid #666;">Waist (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="padding:8px; border: 1px solid #555;">XS</td><td style="padding:8px; border: 1px solid #555;">17</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Small</td><td style="padding:8px; border: 1px solid #555;">18</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Medium</td><td style="padding:8px; border: 1px solid #555;">19</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Large</td><td style="padding:8px; border: 1px solid #555;">20.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XL</td><td style="padding:8px; border: 1px solid #555;">22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXL</td><td style="padding:8px; border: 1px solid #555;">24</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXXL</td><td style="padding:8px; border: 1px solid #555;">26</td></tr>
  </tbody>
</table>

  `,
  'JACKETS': `
<h4 style="margin-top:10px;">Jacket Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size</th>
      <th style="padding: 10px; border: 1px solid #666;">Dimensions (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Small</td><td style="padding:8px; border: 1px solid #555;">13 x 17</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Medium</td><td style="padding:8px; border: 1px solid #555;">14 x 18</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Large</td><td style="padding:8px; border: 1px solid #555;">15 x 19</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids XL</td><td style="padding:8px; border: 1px solid #555;">16 x 20</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Teen</td><td style="padding:8px; border: 1px solid #555;">17 x 22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XS</td><td style="padding:8px; border: 1px solid #555;">18 x 24</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Small</td><td style="padding:8px; border: 1px solid #555;">19 x 25.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Medium</td><td style="padding:8px; border: 1px solid #555;">20 x 26</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Large</td><td style="padding:8px; border: 1px solid #555;">21 x 27</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XL</td><td style="padding:8px; border: 1px solid #555;">22.5 x 28.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXL</td><td style="padding:8px; border: 1px solid #555;">23.5 x 22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXXL</td><td style="padding:8px; border: 1px solid #555;">24.5 x 29</td></tr>
  </tbody>
</table>

  `,
  'LONGSLEEVE': `
<h4 style="margin-top:10px;">Longsleeve Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size</th>
      <th style="padding: 10px; border: 1px solid #666;">Dimensions (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Small</td><td style="padding:8px; border: 1px solid #555;">13 x 17</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Medium</td><td style="padding:8px; border: 1px solid #555;">14 x 18</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Large</td><td style="padding:8px; border: 1px solid #555;">15 x 19</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids XL</td><td style="padding:8px; border: 1px solid #555;">16 x 20</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Teen</td><td style="padding:8px; border: 1px solid #555;">17 x 22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XS</td><td style="padding:8px; border: 1px solid #555;">18 x 24</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Small</td><td style="padding:8px; border: 1px solid #555;">19 x 25.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Medium</td><td style="padding:8px; border: 1px solid #555;">20 x 26</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Large</td><td style="padding:8px; border: 1px solid #555;">21 x 27</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XL</td><td style="padding:8px; border: 1px solid #555;">22.5 x 28.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXL</td><td style="padding:8px; border: 1px solid #555;">23.5 x 22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXXL</td><td style="padding:8px; border: 1px solid #555;">24.5 x 29</td></tr>
  </tbody>
</table>

  `,
  'CAP': `
<h4>Cap Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Size</th>
    <th style="padding: 10px; border: 1px solid #666;">Details</th>
  </tr>
  <tr>
    <td style="padding: 8px; border: 1px solid #555;">One Size</td>
    <td style="padding: 8px; border: 1px solid #555;">Adjustable – fits most</td>
  </tr>
</table>
  `,
  'BEDROOM SLIPPERS': `
<h4>Slippers Size Chart (Euro Sizes)</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Euro Size</th>
  </tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">EU 36 - 37</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">EU 38 - 39</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">EU 40 - 41</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">EU 42 - 43</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">EU 44 - 45</td></tr>
</table>

  `,
  'THROWPILLOWS': `
<h4>Throw Pillow Sizes</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Size</th>
    <th style="padding: 10px; border: 1px solid #666;">Dimensions</th>
  </tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Small</td><td style="padding: 8px; border: 1px solid #555;">12in x 12in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Medium</td><td style="padding: 8px; border: 1px solid #555;">14in x 14in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Large</td><td style="padding: 8px; border: 1px solid #555;">16in x 16in</td></tr>
</table>

  `,
  'BEDDINGS': `
<h4>Bedding Sizes</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Type</th>
    <th style="padding: 10px; border: 1px solid #666;">Dimensions</th>
  </tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Single</td><td style="padding: 8px; border: 1px solid #555;">36in x 75in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Semi-single</td><td style="padding: 8px; border: 1px solid #555;">42in x 75in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Twin</td><td style="padding: 8px; border: 1px solid #555;">39in x 75in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Full</td><td style="padding: 8px; border: 1px solid #555;">54in x 75in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Queen</td><td style="padding: 8px; border: 1px solid #555;">60in x 80in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">King</td><td style="padding: 8px; border: 1px solid #555;">76in x 80in</td></tr>
</table>

  `,
  'CANVAS BAGS & POUCHES': `
<h4>Canvas Bag & Pouch Sizes</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Size</th>
    <th style="padding: 10px; border: 1px solid #666;">Dimensions</th>
  </tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Small</td><td style="padding: 8px; border: 1px solid #555;">10in x 12in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Medium</td><td style="padding: 8px; border: 1px solid #555;">12in x 14in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Large</td><td style="padding: 8px; border: 1px solid #555;">14in x 16in</td></tr>
</table>

  `,
  'NON-WOVEN BAGS': `
<h4>Non-Woven Bag Sizes</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Size</th>
    <th style="padding: 10px; border: 1px solid #666;">Dimensions</th>
  </tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Small</td><td style="padding: 8px; border: 1px solid #555;">10in x 12in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Medium</td><td style="padding: 8px; border: 1px solid #555;">12in x 14in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Large</td><td style="padding: 8px; border: 1px solid #555;">14in x 16in</td></tr>
</table>

  `,
  'KRAFT PAPER BAGS': `
<h4>Kraft Paper Bag Sizes</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Size</th>
    <th style="padding: 10px; border: 1px solid #666;">Dimensions</th>
  </tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Small</td><td style="padding: 8px; border: 1px solid #555;">10in x 12in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Medium</td><td style="padding: 8px; border: 1px solid #555;">12in x 14in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Large</td><td style="padding: 8px; border: 1px solid #555;">14in x 16in</td></tr>
</table>

  `,
  'ID LACE/LANYARDS': `
<h4>Lanyard Size</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Type</th>
    <th style="padding: 10px; border: 1px solid #666;">Length</th>
  </tr>
  <tr>
    <td style="padding: 8px; border: 1px solid #555;">Standard</td>
    <td style="padding: 8px; border: 1px solid #555;">18 inches</td>
  </tr>
</table>
  `,
};
</script>

</body>
</html>