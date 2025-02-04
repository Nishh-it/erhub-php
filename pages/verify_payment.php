<?php
session_start();
include '../includes/connect.php';
require '../vendor/autoload.php';

use Razorpay\Api\Api;

$key_id = "rzp_test_LXZO1v4DgjDFKy";
$key_secret = "DWMxXvD5lDCfKaC9LME1494q";
$api = new Api($key_id, $key_secret);

$order_id = $_POST['order_id'];
$payment_id = $_POST['payment_id'];

// Fetch order from Razorpay
$order = $api->order->fetch($order_id);

if ($order && $order['status'] == 'paid') {
    $stmt = $conn->prepare("INSERT INTO orders (user_id, razorpay_order_id, razorpay_payment_id, amount, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issis", $_SESSION['user_id'], $order_id, $payment_id, $order['amount'], 'Paid');
    $stmt->execute();
    
    // Clear cart after successful payment
    unset($_SESSION['cart']);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Payment verification failed']);
}
?>
