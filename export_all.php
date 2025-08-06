<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = new mysqli("localhost", "root", "", "printshop");

// Fetch data from tables
$active = $conn->query("SELECT * FROM orders");
$completed = $conn->query("SELECT * FROM completed_orders");
$canceled = $conn->query("SELECT * FROM canceled_orders");

$spreadsheet = new Spreadsheet();

// --- Active Orders Sheet ---
$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle('Active Orders');
$sheet1->fromArray(['Order ID','Customer Name','Items','Status'], NULL, 'A1');
$row = 2;
while($data = $active->fetch_assoc()) {
    $sheet1->fromArray([$data['order_id'], $data['customer_name'], $data['items'], $data['status']], NULL, 'A'.$row++);
}

// --- Completed Orders Sheet ---
$sheet2 = $spreadsheet->createSheet();
$sheet2->setTitle('Completed Orders');
$sheet2->fromArray(['Order ID','Customer Name','Items','Status'], NULL, 'A1');
$row = 2;
while($data = $completed->fetch_assoc()) {
    $sheet2->fromArray([$data['order_id'], $data['customer_name'], $data['items'], 'Completed'], NULL, 'A'.$row++);
}

// --- Canceled Orders Sheet ---
$sheet3 = $spreadsheet->createSheet();
$sheet3->setTitle('Canceled Orders');
$sheet3->fromArray(['Order ID','Customer Name','Items','Status'], NULL, 'A1');
$row = 2;
while($data = $canceled->fetch_assoc()) {
    $sheet3->fromArray([$data['order_id'], $data['customer_name'], $data['items'], 'Canceled'], NULL, 'A'.$row++);
}

// Output to browser
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="all_orders.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;

