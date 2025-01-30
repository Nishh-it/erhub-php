<?php
session_start();
include '../includes/connect.php';

$product_id = 0; // Initialize product_id

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    
    if (!$product) {
        echo "<p class='error-message'>Product not found.</p>";
        exit;
    }
}

if (!$result) {
    echo "<p class='error-message'>Database query failed: " . mysqli_error($conn) . "</p>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            animation: fadeIn 0.8s ease-in-out;
        }
        .left-section {
            flex: 1;
            text-align: center;
        }

        .left-section img {
            max-width: 100%;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .left-section img:hover {
            transform: scale(1.05);
        }

        .thumbnails img {
            width: 100px;
            margin: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .thumbnails img:hover {
            transform: scale(1.05);
        }

        .right-section {
            flex: 1;
            padding-left: 40px;
        }

        .product-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            display: flex;
            justify-content: space-between;
        }

        .product-info-buttons {
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 15px;
            margin-top: 10px;
            text-align: center;
            background: #ffcc00;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background 0.3s ease, transform 0.2s;
        }

        .btn:hover {
            background: #f0b300;
            transform: translateY(-3px);
        }

        .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 9999; /* Ensures it's on top of other elements */
    }

    /* Centering the overlay content */
    .overlay-content {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        position: relative;
        z-index: 10000; /* Ensures the content is above the calendar */
    }

    /* Make the calendar appear on top of the overlay */
    .daterangepicker {
        z-index: 10001; /* Ensures the calendar is above everything else */
    }

    /* Style for the overlay button */
    #close-overlay {
        margin-top: 15px;
    }

        .product-info {
            text-align: left;
            margin-top: 20px;
        }

        .product-info h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .product-info p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 10px;
        }

        .features ul, .faq ul {
            list-style: none;
            padding: 0;
        }

        .features li, .faq li {
            padding: 12px;
            background: #f9f9f9;
            margin: 5px 0;
            border-radius: 5px;
        }

        .reviews .review {
            background: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .related-products {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 40px;
        }

        .product-card {
            flex: 1 1 calc(33% - 20px);
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            max-width: 100%;
            border-radius: 5px;
        }

        .error-message {
            text-align: center;
            color: red;
            font-size: 1.5rem;
            margin-top: 50px;
        }
        #add-to-cart {
        background-color: #28a745;
    }
    #add-to-cart:hover {
        background-color: #218838;
    }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="left-section">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <div class="thumbnails">
                <img src="../assets/images/sample1.jpg" alt="Thumbnail">
                <img src="../assets/images/sample2.jpg" alt="Thumbnail">
                <img src="../assets/images/sample3.jpg" alt="Thumbnail">
            </div>
        </div>
        <div class="right-section">
            <div class="product-title">
                <?php echo htmlspecialchars($product['name']); ?>
                <span>&hearts;</span>
            </div>
            <div class="product-info-buttons">
                <button class="btn">Check the price &rarr;</button>
                <button class="btn">Offers for you &rarr;</button>
            </div>
            <div class="box-ft">
                <p><strong>Zero Deposit</strong></p>
                <p><strong>FREE delivery & returns</strong></p>
                <p><strong>Pay on delivery available</strong></p>
            </div>
            <button id="select-dates-btn" class="btn">Select your dates 📅</button>

            <div id="date-picker-overlay" class="overlay">
    <div class="overlay-content">
        <h2>Select Delivery & Return Dates</h2>
        <input type="text" id="date-range" placeholder="Select dates">
        <button id="close-overlay" class="btn">Close</button>
        <div id="selected-dates"></div>
        <button id="add-to-cart" class="btn" style="display: none;">Add to Cart</button>
    </div>
</div>
        </div>
    </div>

    <div class="features">
        <h2 class="section-title">Features & Specifications</h2>
        <ul>
            <li>High-resolution imaging with advanced sensors</li>
            <li>Connectivity options: Wi-Fi, Bluetooth, NFC</li>
            <li>Long-lasting battery performance</li>
            <li>Lightweight and durable design</li>
            <li>Compatibility with multiple accessories</li>
        </ul>
    </div>

    <div class="product-videos">
        <h2 class="section-title">Product Videos</h2>
        <iframe width="100%" height="315" src="https://www.youtube.com/embed/samplevideo1" frameborder="0"></iframe>
        <iframe width="100%" height="315" src="https://www.youtube.com/embed/samplevideo2" frameborder="0"></iframe>
    </div>

    <div class="faq">
        <h2 class="section-title">Frequently Asked Questions</h2>
        <ul>
            <li><strong>How can I rent this product?</strong> - Click 'Rent Now' and follow the checkout process.</li>
            <li><strong>What is the rental period?</strong> - The minimum rental period is 3 days.</li>
            <li><strong>Do I need to pay a deposit?</strong> - No deposit required for this product.</li>
        </ul>
    </div>

    <div class="reviews">
        <h2 class="section-title">Customer Reviews</h2>
        <div class="review">
            <p><strong>John Doe:</strong> "Amazing product, worked perfectly for my trip!"</p>
        </div>
        <div class="review">
            <p><strong>Jane Smith:</strong> "Great service and the product was in top condition."</p>
        </div>
    </div>

    <div class="related-products">
        <h2 class="section-title">You May Also Like</h2>
        <div class="product-card">
            <img src="../assets/images/sample1.jpg" alt="Related Product">
            <p>Canon 2000D</p>
            <a href="#" class="btn">View Rent</a>
        </div>
        <div class="product-card">
            <img src="../assets/images/sample2.jpg" alt="Related Product">
            <p>Sony ZV1</p>
            <a href="#" class="btn">View Rent</a>
        </div>
        <div class="product-card">
            <img src="../assets/images/sample3.jpg" alt="Related Product">
            <p>DJI R3 Gimbal</p>
            <a href="#" class="btn">View Rent</a>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
 $(document).ready(function() {
    $('#select-dates-btn').click(function() {
        $('#date-picker-overlay').fadeIn();
    });

    $('#close-overlay').click(function() {
        $('#date-picker-overlay').fadeOut();
    });

    $('#date-range').daterangepicker({
        autoApply: true,
        minDate: moment(),
        locale: {
            format: 'YYYY-MM-DD'
        },
        singleDatePicker: false,
        showDropdowns: true,
        drops: 'down'
    });

    // Change the Close button to Add to Cart button after selecting dates
    $('#date-range').on('apply.daterangepicker', function(ev, picker) {
    $('#close-overlay').hide(); 
    $('#add-to-cart').show();  
    $('#selected-dates').text("Selected dates: " + picker.startDate.format('YYYY-MM-DD') + " to " + picker.endDate.format('YYYY-MM-DD'));
    });


    // Handle Add to Cart button click
    $('#add-to-cart').click(function() {
    var selectedDates = $('#date-range').val();
    var productId = <?php echo json_encode($product_id); ?>;

    
    $.ajax({
        url: 'cart.php',
        method: 'POST',
        data: {
            product_id: productId,
            dates: selectedDates
        },
        dataType: 'json',  // Ensure JSON response handling
        success: function(response) {
            if (response.status === 'success') {
                alert('Product added to cart!');
                updateCartCount();
            } else {
                alert(response.message);
            }
            $('#date-picker-overlay').fadeOut();
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText);
            alert('There was an error processing your request.');
        }
    });
});

});

    </script>
     <script src="../scripts/script.js"></script>
</body>
</html>
