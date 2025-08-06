<?php
session_start();

if (isset($_POST['index']) && is_numeric($_POST['index']) && isset($_SESSION['cart'])) {
    $index = (int) $_POST['index'];

    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        // Reindex to maintain array order
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid index or cart not set']);
