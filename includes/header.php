<!-- <?php
session_start();
ini_set('display_errors', 0);
error_reporting(E_ALL);
?> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
     <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ER-HUB</title>
    <meta name="description" content="ER-HUB - Rent your equipment hassle-free">
    <link rel="icon" type="image/png" href="../assets/images/2.jpg">
    <!-- <link rel="stylesheet" href="../assets/css/style2.css"> -->
<style>

/* General Reset */
* {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Header Section */
header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 15px 60px;
    background: #162938;
    justify-content: space-between;
    align-items: center;
    z-index: 1000;
}

/* .logo {
    font-size: 2em;
    color: azure;
    user-select: none;
} */

/* Search Bar */
.search-bar {
    border-radius: 18px;
    background: white;
    flex-grow: 1;
    display: flex;
    justify-content: center;
    margin: 0 60px;
    max-width: 600px;
}

.search-bar input {
    width: 97%;
    padding: 10px;
    font-size: 1rem;
    border-radius: 20px;
    border: 1px solid #fff;
    outline: none;
}

.search-bar input:focus {
    border-color: #007bff;
}

.search-bar button {
    padding: 5px;
    background: transparent;
    border: none;
    cursor: pointer;
}

.search-icon img {
    width: 20px;
    height: 20px;
}

/* Cart Icon */
.cart img {
    background: whitesmoke;
    border-radius: 14px;
    width: 40px;
    height: 40px;
    cursor: pointer;
}

/* Login/Logout Button */
/* .btnLogin-popup, .btnLogout {
    width: 130px;
    height: 50px;
    background: transparent;
    border: 2px solid #fff;
    outline: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.1em;
    color: #fff;
    font-weight: 500;
    transition: .6s;
} */

/* .btnLogin-popup:hover, .btnLogout:hover {
    background: #fff;
    color: blue;
    transform: scale(1.05);
} */

/* Background overlay */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6); /* Semi-transparent black */
    display: none; /* Initially hidden */
    z-index: 1000;
}

#login-overlay.active {
    display: block;
}

.logo h2 {
    font-size: 2em;
    color: azure;
    user-select: none;
}

.navbar .btnLogin-popup, 
.navbar .btnLogout {
    width: 130px;
    height: 50px;
    background: transparent;
    border: 2px solid #fff;
    outline: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.1em;
    color: #fff;
    font-weight: 500;
    margin-left: 40px;
}

.navbar .btnLogin-popup:hover, 
.navbar .btnLogout:hover {
    background: #fff;
    color: blue;
    transition: .6s;
}

.wrapper {
    margin-top : 250px;
    position: relative;
    width: 400px;
    height: 440px;
    background: transparent;
    border: 2px solid rgba(255,255,255,.5);
    border-radius: 20px;
    backdrop-filter: blur(20px);
    box-shadow: 0 0 30px rgba(0,0,0.5);
    display: flex;
    /* justify-content: center;
    align-items: center;
    overflow: hidden; 
    transform: scale(0); */
    transition: transform .5s ease, height .2s ease;
}

.wrapper.active-popup {
    transform: scale(1);
}

.form-box h2 {
    font-size: 2em;
    color: #162716;
    text-align: center;
}

.input-box {
    position: relative;
    width: 100%;
    height: 50px;
    border-bottom: 2px solid #162716;
    margin: 30px 0;
}

.input-box label {
    position: absolute;
    top: 50%;
    left: 5px;
    transform: translateY(-50%);
    font-size: 1em;
    color: #162938;
    font-weight: 500;
    pointer-events: none;
    transition: .5s;
}

.input-box input:focus ~ label,
.input-box input:valid ~ label {
    top: -5px;
}

.input-box input {
    width: 100%;
    height: 100%;
    background: transparent;
    border: none;
    outline: none;
    font-size: 1em;
    color: #162938;
    font-weight: 600;
    padding: 0 35px 0 5px;
}

.input-box .icon {
    position: absolute;
    right: 8px;
    font-size: 1.2em;
    color: #162938;
    line-height: 57px;
}

.remember-forgot {
    font-size: .9em;
    color: #162938;
    font-weight: 500;
    margin: -15px 0 15px;
    display: flex;
    justify-content: space-between;
}

.remember-forgot label input {
    accent-color: #162938;
    margin-right: 3px;
}

.remember-forgot a {
    color: #162938;
    text-decoration: none;
}

.remember-forgot a:hover {
    text-decoration: underline;
}

.btn {
    width: 100%;
    height: 45px;
    background: #162938;
    border: none;
    outline: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1em;
    color: #fff;
    font-weight: 500;
}

.login-register p a {
    color: #162938;
    text-decoration: none;
    font-weight: 600;
}

.login-register p a:hover {
    text-decoration: underline;
}

.overlay {
    position: fixed;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    top: 0;
    left: 0;
    display: none;
}

.wrapper .icon-close {
    position: absolute;
    top: 0;
    right: 0;
    width: 45px;
    height: 45px;
    font-size: 2em;
    color: #162938;
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 50%;
    cursor: pointer;
}


/* Navigation Bar */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    background-color: #162938;;
}

.navbar .logo {
    font-size: 24px;
    font-weight: bold;
    color: white;
    text-decoration: none;
}

.navbar ul {
    list-style: none;
    display: flex;
    margin: 0;
    padding: 0;
}

.navbar ul li {
    margin: 0 15px;
}

.navbar ul li a {
    text-decoration: none;
    color: white;
    font-size: 18px;
    padding: 10px 15px;
    transition: background 0.3s ease;
}

.navbar ul li a:hover {
    background-color: #004494;
    border-radius: 5px;
}

/* Login/Logout Button */
.navbar .auth-btn {
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 18px;
    border-radius: 5px;
    transition: background 0.3s ease;
}

.navbar .auth-btn:hover {
    background-color: #218838;
}

/* Main Content */
.main-content {
    flex: 1;
    padding: 20px;
    text-align: center;
}

/* Footer Styles */
footer {
    background-color: #343a40;
    color: white;
    text-align: center;
    padding: 15px 0;
    margin-top: auto;
    width: 100%;
    position: relative;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
    }
    
    .navbar ul {
        flex-direction: column;
        text-align: center;
    }

    .navbar ul li {
        margin: 10px 0;
    }

    .navbar .btnLogin-popup, .navbar .search-bar {
        width: 100%;
        text-align: center;
    }
}

/* Overlay for the login form */
.overlay {
    display: none; /* Initially hidden */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

/* Login popup form styling */
.login-popup {
    display: none; /* Initially hidden */
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
    z-index: 10000;
}

/* Close button for the form */
.close-btn {
    font-size: 30px;
    background: none;
    border: none;
    position: absolute;
    top: 5px;
    right: 10px;
    cursor: pointer;
}

/* Make sure login form has some spacing */
.login-content form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.login-content input {
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.login-content button {
    padding: 10px;
    background: #162938;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.login-content button:hover {
    background: #162938;
}

/* Initially hide register and forgot password forms */
.form-box.register,
.form-box.forgot {
    display: none;
}

/* Show forms when they are active */
.form-box.active {
    display: block;
}

/* Cart Overlay */
#cart-overlay {
    display: none; /* Default hidden */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    justify-content: flex-end; /* Sidebar alignment */
    align-items: flex-start; /* Ensures proper top alignment */
    padding-top: 50px; /* Adds space between the top and the popup */
}

/* Cart Sidebar */
.cart-popup {
    width: 30%;
    max-width: 400px;
    height: 100%;
    background: #fff;
    box-shadow: -4px 0 15px rgba(0, 0, 0, 0.2);
    position: relative;
    padding: 20px;
    overflow-y: auto;
}

.cart-popup h2 {
    text-align: center;
    margin-bottom: 20px;
}

.cart-popup .empty-cart {
    text-align: center;
    color: #999;
    font-size: 1.2em;
    margin-top: 50px;
}

.cart-footer {
    text-align: center;
    margin-top: 20px;
}

.cart-footer .btn {
    width: 90%;
    padding: 10px;
    background: #162938;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1em;
}

.cart-footer .btn:hover {
    background: #004494;
}

/* Close Button */
#close-cart {
    font-size: 25px;
    position: absolute;
    top: 15px;
    right: 20px;
    cursor: pointer;
}

.cart-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.cart-product-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 15px;
}

.cart-item-details {
    flex: 1;
}


</style>

</head>
<body>
    <header>
    <nav class="navbar">
    <div class="logo">
            <h2>Logo</h2>
        </div>

        <!-- Search Bar in the Center -->
        <div class="search-bar">
            <input type="text" id="search" placeholder="Search for products...">
            <button class="search-icon">
             <img src="../assets/images/ser.png" alt="Search">
            </button>
        </div>

        <!-- Cart Icon on the Right -->
        <div class="cart">
            <img src="../assets/images/cart.png" alt="Cart">
        </div>

        <!-- Cart Overlay -->
<div id="cart-overlay" class="overlay">
    <div id="cart-sidebar" class="cart-popup">
        <span id="close-cart" class="close-btn">&times;</span>
        <h2>Your Cart</h2>
        <div id="cart-items">
            <!-- Cart items will be dynamically loaded here -->
            <p class="empty-cart">Nothing to see here.</p>
        </div>
        <div class="cart-footer">
            <button id="checkout-btn" class="btn">Proceed to Checkout</button>
        </div>
    </div>
</div>
        

         <!-- Login/Logout Button -->
         <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <a href="../pages/login.php?logout=true">
                    <button class="btnLogout">Logout</button>
                </a>
            <?php else: ?>
                <button id="loginBtn" class="btnLogin-popup">Login</button>
            <?php endif; ?>
            </nav>
    </header>

<div id="login-overlay" class="overlay"></div>

<!-- Login & Registration Form Container -->
<div id="login-form-container" class="login-popup <?php if (!empty($error)) echo 'active-popup'; ?>">
    <span id="close-login" class="close-btn">&times;</span>

    <!-- Login Form -->
    <div class="form-box login-content">
    <span class="icon-close"><ion-icon name="close"></ion-icon></span>
    <h2 class="form-title">Login</h2>
    <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <form id="login-form" action="login.php" method="post">
            <div class="input-box">
            <span class="icon"><ion-icon name="mail"></ion-icon></span>
                <input type="email" name="email" required>
                <label>Email</label>
            </div>
            <div class="input-box">
            <span class="icon"><ion-icon name="lock"></ion-icon></span>
                <input type="password" name="password" required>
                <label>Password</label>
                <span class="toggle-password">Show</span>
            </div>
            <div class="remember-forgot">
                    <label><input type="checkbox">Remember me</label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>
            <button type="submit" class="btn" name="login">Login</button>
            <div class="login-register">
                    <p>Don't have an account?
                    <a href="#" class="register-link">Register</a></p>
                </div>
        </form>
    </div>

    <!-- Registration Form -->
    <div class="form-box register">
    <span class="icon-close"><ion-icon name="close"></ion-icon></span>
    <h2 class="form-title">Registration</h2>
    <form id="register-form" action="login.php" method="post">
            <div class="input-box">
            <span class="icon"><ion-icon name="person"></ion-icon></span>
                <input type="text" name="name" required>
                <label>Username</label>
            </div>
            <div class="input-box">
            <span class="icon"><ion-icon name="mail"></ion-icon></span>
                <input type="email" name="email" required>
                <label>Email</label>
            </div>
            <div class="input-box">
            <span class="icon"><ion-icon name="lock"></ion-icon></span>
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <div class="remember-forgot">
                    <label><input type="checkbox">Agree to Terms & Conditions</label>
            </div>
            <button type="submit" class="btn" name="register">Register</button>
            <div class="login-register">
                    <p>Already have an account?
                    <a href="#" class="login-link">Login</a></p>
                </div>
        </form>
    </div>

    <!-- Forgot Password Form -->
    <div class="form-box forgot">
    <h2 class="form-title">Reset Password</h2>
        <form id="forgot-form" action="login.php" method="post">
            <div class="input-box">
            <span class="icon"><ion-icon name="mail"></ion-icon></span>
                <input type="email" name="email" required>
                <label>Email</label>
            </div>
            <button type="submit" class="btn">Send Reset Link</button>
            <div class="forgot-register">
                    <p>Create new account </p>
                    <a href="#" class="create-link">Register</a></p>
                </div>
        </form>
    </div>
</div>

    <script src="../scripts/script.js"></script>
</body>
</html>
