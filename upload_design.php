<?php
session_start();

if (!isset($_FILES['design'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
    exit;
}

$upload_dir = 'uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$file = $_FILES['design'];
$filename = uniqid("design_") . "_" . basename($file['name']);
$target_file = $upload_dir . $filename;

$allowed = ['jpg', 'jpeg', 'png', 'pdf'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
    exit;
}

if (move_uploaded_file($file['tmp_name'], $target_file)) {
    $_SESSION['uploaded_design'] = $target_file;
    echo json_encode(['success' => true, 'file' => $target_file]);
} else {
    echo json_encode(['success' => false, 'message' => 'Upload failed.']);
}
?>
