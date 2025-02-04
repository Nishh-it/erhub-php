<?php
// Start session (if not already started)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database Connection
$host = "localhost"; // Example: "localhost"
$username = "root";
$password = "";
$database = "equipment_rental_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Razorpay API Credentials
define('RAZORPAY_KEY_ID', 'rzp_test_LXZO1v4DgjDFKy');  // Replace with your test/live key
define('RAZORPAY_KEY_SECRET', 'YOUR_KEY_SECRET'); // Replace with your key secret

// Include Razorpay SDK
require '../razorpay/Razorpay.php';
use Razorpay\Api\Api;

// Initialize Razorpay API
$api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);
?>
