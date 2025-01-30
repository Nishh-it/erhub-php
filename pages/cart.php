<?php
session_start();
include '../includes/connect.php'; // Database connection
header('Content-Type: application/json');

// Ensure the cart session is an array
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding a product to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['dates'])) {
    $product_id = intval($_POST['product_id']);
    $dates = $_POST['dates'];

    // Validate product_id against the database
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();
    
    if (!$product_result || $product_result->num_rows == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        exit;
    }

    // Check for duplicate entries
    $is_duplicate = false;
    foreach ($_SESSION['cart'] as $cart_item) {
        if ($cart_item['product_id'] == $product_id && strtotime($cart_item['dates']) == strtotime($dates)) {
            $is_duplicate = true;
            break;
        }
    }

    // Add to cart if not duplicate
    if (!$is_duplicate) {
        $_SESSION['cart'][] = ['product_id' => $product_id, 'dates' => $dates];
        echo json_encode(['status' => 'success', 'message' => 'Product added to cart']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Product already in cart']);
    }
    exit;
}

// Handle fetching cart items
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch_cart'])) {
    if (empty($_SESSION['cart'])) {
        echo json_encode([]);
        exit;
    }    
    $cart_items = $_SESSION['cart'];
    $response = [];

    foreach ($cart_items as $item) {
        $product_id = intval($item['product_id']);
        
        $stmt = $conn->prepare("SELECT name, price_per_day, image_url FROM products WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $response[] = [
                'product_id' => $product_id,
                'name' => $row['name'],
                'price_per_day' => $row['price_per_day'],
                'dates' => $item['dates'],
                'image_url' => $row['image_url'] 
            ];
        }
    }

    echo json_encode($response);
    exit;
}

?>