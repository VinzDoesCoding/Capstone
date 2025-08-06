<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "printshop");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $order_id = $conn->real_escape_string($_POST['order_id']);
    $new_status = $conn->real_escape_string($_POST['status']);

    // Get order info before changes
    $result = $conn->query("SELECT * FROM orders WHERE order_id = '$order_id'");
    $order = ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;

    // Move to completed_orders if status is Completed
    if ($new_status === 'Completed' && $order) {
        $stmt = $conn->prepare("INSERT INTO completed_orders 
            (order_id, customer_name, items, method, quantity, order_time, pickup_time, phone, email, design_file, payment_method, payment_proof)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisssssss",
            $order['order_id'],
            $order['customer_name'],
            $order['items'],
            $order['method'],
            $order['quantity'],
            $order['order_time'],
            $order['pickup_time'],
            $order['phone'],
            $order['email'],
            $order['design_file'],
            $order['payment_method'],
            $order['payment_proof']
        );
        $stmt->execute();
        $stmt->close();

        $conn->query("DELETE FROM orders WHERE order_id = '$order_id'");

    // Move to canceled_orders if status is Canceled
    } elseif ($new_status === 'Canceled' && $order) {
        $stmt = $conn->prepare("INSERT INTO canceled_orders 
            (order_id, customer_name, items, method, quantity, order_time, pickup_time, phone, email, design_file, payment_method, payment_proof)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisssssss",
            $order['order_id'],
            $order['customer_name'],
            $order['items'],
            $order['method'],
            $order['quantity'],
            $order['order_time'],
            $order['pickup_time'],
            $order['phone'],
            $order['email'],
            $order['design_file'],
            $order['payment_method'],
            $order['payment_proof']
        );
        $stmt->execute();
        $stmt->close();

        $conn->query("DELETE FROM orders WHERE order_id = '$order_id'");

    } else {
        // For In Queue or Processing
        $conn->query("UPDATE orders SET status = '$new_status' WHERE order_id = '$order_id'");
    }

    // Send email notification
    if ($order && !empty($order['email'])) {
        $to = $order['email'];
        $subject = "JIMACA Prints - Order Status Update";
        $message = "Hello " . $order['customer_name'] . ",\n\n"
                 . "Your order (ID: " . $order['order_id'] . ") has been updated.\n\n"
                 . "📦 Status: " . $new_status . "\n"
                 . "🕓 Pickup Time: " . $order['pickup_time'] . "\n\n"
                 . "Thank you for ordering with JIMACA Prints.\n\n"
                 . "- JIMACA Prints Team";

        $headers = "From: no-reply@jimacaprints.local";
        mail($to, $subject, $message, $headers);
    }

    $conn->close();
    header("Location: admin_dashboard.php");
    exit;
}
?>
