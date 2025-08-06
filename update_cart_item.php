<?php
session_start();

if (isset($_POST['index'], $_POST['key'], $_POST['value'])) {
    $index = (int) $_POST['index'];
    $key = $_POST['key'];
    $value = $_POST['value'];

    if (isset($_SESSION['cart'][$index]) && array_key_exists($key, $_SESSION['cart'][$index])) {
        $_SESSION['cart'][$index][$key] = $value;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid cart index or key']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
}
