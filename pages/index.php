<?php
session_start();
include '../includes/connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Rental</title>
    <!-- <link rel="stylesheet" href="../assets/css/style2.css"> -->
    <style>
    /* General Page Styles */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
        color: #333;
    }

    .main-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Trending Products Section */
    .trending-products {
        margin-bottom: 40px;
    }

    .trending-products h2,
    .categories h2 {
        text-align: center;
        font-size: 2rem;
        color: #222;
        margin-bottom: 20px;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        justify-content: center;
    }

    .product-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        text-align: center;
        transition: transform 0.3s ease-in-out;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-card img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
    }

    .product-card h3 {
        font-size: 1.5rem;
        margin: 15px 0 10px;
    }

    .product-card p {
        font-size: 1.2rem;
        color: #555;
    }

    .btn {
        display: inline-block;
        background: #007bff;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        transition: background 0.3s ease;
    }

    .btn:hover {
        background: #0056b3;
    }

    /* Categories Section */
    .categories {
        margin-bottom: 40px;
    }

    .category-section {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .category-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        text-align: center;
        max-width: 300px;
    }

    .category-card img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
    }

    .category-card h3 {
        font-size: 1.5rem;
        margin: 15px 0 10px;
    }

    .category-card .btn {
        margin-top: 10px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .product-grid, .category-section {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .product-card h3,
        .category-card h3 {
            font-size: 1.2rem;
        }
    }
</style>

</head>
<body>

    <?php include '../includes/header.php'; ?>

    <main class="main-content">
    <section class="trending-products">
        <h2>Trending Products</h2>
        <div class="product-grid">
            <?php
            $query = "SELECT * FROM products WHERE trending = 1 LIMIT 6";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="product-card">';
                    echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<p>₹' . htmlspecialchars($row['price_per_day']) . ' / day</p>';
                    echo '<a href="product.php?id=' . $row['id'] . '" class="btn">View Details</a>'; // Link to product page
                    echo '</div>';
                }
            } else {
                echo '<p>No trending products available.</p>';
            }
            ?>
        </div>
    </section>

    <section class="categories">
        <h2>Browse Categories</h2>
        <div class="category-section">
            <?php
            $cat_query = "SELECT * FROM categories";
            $cat_result = mysqli_query($conn, $cat_query);
            if (mysqli_num_rows($cat_result) > 0) {
                while ($cat = mysqli_fetch_assoc($cat_result)) {
                    echo '<div class="category-card">';
                    echo '<img src="' . htmlspecialchars($cat['category_img']) . '" alt="' . htmlspecialchars($cat['category_name']) . '" class="category-image">';
                    echo '<h3>' . htmlspecialchars($cat['category_name']) . '</h3>';
                    echo '<a href="pages/category.php?id=' . $cat['id'] . '" class="btn">Explore</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>No categories available.</p>';
            }
            ?>
        </div>
    </section>
</main>


    <?php include '../includes/footer.php'; ?>

    <script src="../scripts/script.js"></script>
</body>
</html>
