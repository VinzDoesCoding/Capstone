<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$conn = new mysqli("localhost", "root", "", "printshop");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "❌ No items to submit.";
    exit;
}

// ===== Get customer info =====
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$pickup_time = $_POST['pickup_time'] ?? '';
$payment_method = $_POST['payment_method'] ?? 'cash';
$payment_proof = null;

// Update all cart items with user info
foreach ($_SESSION['cart'] as &$item) {
    $item['customer_name'] = $name;
    $item['email'] = $email;
    $item['phone'] = $phone;
    $item['pickup_time'] = $pickup_time;
}
unset($item); // break reference

// ===== Design file upload =====
$uploaded_file = '';
if (isset($_FILES['design_file']) && $_FILES['design_file']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['design_file']['tmp_name'];
    $file_name = basename($_FILES['design_file']['name']);
    $unique_name = time() . "_" . uniqid() . "_" . $file_name;
    $target_path = "uploads/" . $unique_name;

    if (move_uploaded_file($file_tmp, $target_path)) {
        $uploaded_file = $target_path;
    }
}

// ===== Payment proof upload =====
if (
  ($payment_method === 'gcash' || $payment_method === 'chinabank') &&
  isset($_FILES['gcash_proof']) &&
  $_FILES['gcash_proof']['error'] === UPLOAD_ERR_OK
) {
    $upload_subdir = ($payment_method === 'gcash') ? "gcash" : "chinabank";
    $proof_dir = "uploads/$upload_subdir/";

    if (!is_dir($proof_dir)) {
        mkdir($proof_dir, 0777, true);
    }

    $proof_name = time() . "_" . uniqid() . "_" . basename($_FILES['gcash_proof']['name']);
    $proof_path = $proof_dir . $proof_name;

    if (move_uploaded_file($_FILES['gcash_proof']['tmp_name'], $proof_path)) {
        $payment_proof = $proof_path;
    } else {
        echo "❌ Failed to upload payment proof.";
        exit;
    }
}


// ===== Insert each cart item =====
foreach ($_SESSION['cart'] as $item) {
    $order_id = uniqid("ORD-");
    $customer_name = $conn->real_escape_string($item['customer_name']);
    $items = $conn->real_escape_string($item['product'] . " - " . $item['color'] . " - " . $item['size']);
    $method = $conn->real_escape_string($item['method']);
    $quantity = (int)$item['quantity'];
    $order_time = date("Y-m-d H:i:s");
    $pickup = $conn->real_escape_string($item['pickup_time']);
    $status = "In Queue";
    $priority = 0;
    $phone = $conn->real_escape_string($item['phone']);
    $email = $conn->real_escape_string($item['email']);

    $sql = "INSERT INTO orders 
    (order_id, customer_name, items, method, quantity, order_time, pickup_time, status, priority, phone, email, design_file, payment_method, payment_proof) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param(
            "ssssisssisssss",
            $order_id,
            $customer_name,
            $items,
            $method,
            $quantity,
            $order_time,
            $pickup,
            $status,
            $priority,
            $phone,
            $email,
            $uploaded_file,
            $payment_method,
            $payment_proof
        );

        if ($stmt->execute()) {
            echo "✅ Order $order_id submitted successfully!<br>";
        } else {
            echo "❌ Failed to insert order $order_id: " . $stmt->error . "<br>";
        }

        $stmt->close();
    } else {
        echo "❌ Prepare failed: " . $conn->error . "<br>";
    }
}

// Clear cart
unset($_SESSION['cart']);
?>
