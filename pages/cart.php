<?php
include '../includes/connect.php'; // Database connection
header('Content-Type: application/json');

// Fetch product details for cart page
if (isset($_GET['fetch_product_details']) && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    // Prepare SQL to get the product details
    $stmt = $conn->prepare("SELECT name, price_per_day, image_url FROM products WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['product' => $row]);  // Send product details as JSON
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
    exit;
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

    // Retrieve existing cart from local storage (sent as part of the request)
    $cart = isset($_POST['cart']) ? json_decode($_POST['cart'], true) : [];

    // Check for duplicate entries based on product_id and dates
    $is_duplicate = false;
    foreach ($cart as $cart_item) {
        if ($cart_item['product_id'] == $product_id && strtotime($cart_item['dates']) == strtotime($dates)) {
            $is_duplicate = true;
            break;
        }
    }

    // Add to cart if not duplicate
    if (!$is_duplicate) {
        $cart[] = ['product_id' => $product_id, 'dates' => $dates];
        echo json_encode(['status' => 'success', 'message' => 'Product added to cart', 'cart' => json_encode($cart)]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Product already in cart']);
    }
    exit;
}

// Handle fetching cart items
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch_cart'])) {
    // Retrieve cart from localStorage (sent as part of the request)
    $cart_data = isset($_GET['cart']) ? json_decode($_GET['cart'], true) : [];

    if (empty($cart_data)) {
        echo json_encode(['cart_items' => [], 'cart_total' => 0]);
        exit;
    }

    $cart_items = $cart_data;
    $response = [];
    $cart_total = 0; // Initialize cart total

    foreach ($cart_items as $item) {
        $product_id = intval($item['product_id']);
        $dates = explode(" - ", $item['dates']); // Split start and end dates
        
        // Validate date format
        if (count($dates) == 2) {
            $start_date = strtotime($dates[0]);
            $end_date = strtotime($dates[1]);
            $days = ($end_date - $start_date) / (60 * 60 * 24); // Convert seconds to days
        } else {
            $days = 1; // Default to 1 day if date format is incorrect
        }

        // Fetch product details
        $stmt = $conn->prepare("SELECT name, price_per_day, image_url FROM products WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($row = $result->fetch_assoc()) {
            $rent = $row['price_per_day'] * $days; // Calculate rent
            $cart_total += $rent; // Add rent to total cart value
    
            $response[] = [
                'product_id' => $product_id,
                'name' => $row['name'],
                'price_per_day' => $row['price_per_day'],
                'dates' => $item['dates'],
                'image_url' => $row['image_url'],
                'rent' => $rent // Add calculated rent
            ];
        }
    }

    // Include total cart value in response
    echo json_encode(['cart_items' => $response, 'cart_total' => $cart_total]);
    exit;
}

// Read raw JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Handle removing items from the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($data["action"]) && $data["action"] === "remove") {
    $product_id = intval($data["product_id"]);

    // Retrieve cart from localStorage (sent as part of the request)
    $cart = isset($data['cart']) ? $data['cart'] : [];

    if (empty($cart)) {
        echo json_encode([
            "status" => "error",
            "message" => "Cart is empty"
        ]);
        exit;
    }

    // Remove item from cart
    $cart_updated = false;
    foreach ($cart as $key => $item) {
        if ($item["product_id"] == $product_id) {
            unset($cart[$key]); // Remove the item
            $cart_updated = true;
            break;
        }
    }

    if (!$cart_updated) {
        echo json_encode([
            "status" => "error",
            "message" => "Product not found in cart"
        ]);
        exit;
    }

    // Re-index array
    $cart = array_values($cart);

    // Send updated cart back as JSON response
    echo json_encode([
        "status" => "success",
        "cart_items" => $cart
    ]);
    exit;
}


?>
