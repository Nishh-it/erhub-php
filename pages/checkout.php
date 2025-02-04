<?php
session_start();
include '../includes/connect.php'; 
require '../vendor/autoload.php'; 
use Razorpay\Api\Api;

$key_id = "rzp_test_LXZO1v4DgjDFKy";
$key_secret = "DWMxXvD5lDCfKaC9LME1494q"; // Use environment variable
$api = new Api($key_id, $key_secret);

// Read the cart data from the request body
$cart_data = json_decode(file_get_contents('php://input'), true);
if (!isset($cart_data['cart']) || empty($cart_data['cart'])) {
    echo json_encode(['error' => 'Cart is empty']);
    exit;
}

// Store cart in session
$_SESSION['cart'] = $cart_data['cart'];

$total_amount = 0;

foreach ($cart_data['cart'] as $item) {
    if (!isset($item['dates']) || empty($item['dates'])) {
        continue; // Skip items with missing date ranges
    }

    $dates = explode(' - ', $item['dates']);
    if (count($dates) !== 2) {
        continue; // Skip invalid date formats
    }

    $start_date = strtotime($dates[0]);
    $end_date = strtotime($dates[1]);
    
    if (!$start_date || !$end_date || $end_date < $start_date) {
        continue; // Skip invalid dates
    }

    $days = max(1, ($end_date - $start_date) / (60 * 60 * 24));

    $stmt = $conn->prepare("SELECT price_per_day FROM products WHERE id = ?");
    $stmt->bind_param("i", $item['product_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $total_amount += $row['price_per_day'] * $days;
    }
    $stmt->close();
}

// Create order in Razorpay
try {
    $order = $api->order->create([
        'receipt' => 'ORD' . time(),
        'amount' => $total_amount * 100,
        'currency' => 'INR',
        'payment_capture' => 1
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Razorpay Order creation failed: ' . $e->getMessage()]);
    exit;
}

echo json_encode([
    'order_id' => $order['id'],
    'amount' => $total_amount * 100,
    'currency' => 'INR',
    'key' => $key_id
]);

?>
