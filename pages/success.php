<?php
session_start();
if (!isset($_GET['payment_id'])) {
    header("Location: index.php");
    exit;
}

$payment_id = $_GET['payment_id'];
echo "<h2>Payment Successful!</h2>";
echo "<p>Your Payment ID: " . htmlspecialchars($payment_id) . "</p>";

// Clear the cart after successful payment
unset($_SESSION['cart']);
?>
