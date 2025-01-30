document.addEventListener('DOMContentLoaded', () => {
    // Get elements
    const loginBtn = document.getElementById('loginBtn');
    const closePopup = document.getElementById('close-login');
    const loginFormContainer = document.getElementById("login-form-container");
    const loginOverlay = document.getElementById("login-overlay");
    const loginForm = document.querySelector('.form-box.login-content');
    const registerForm = document.querySelector('.form-box.register');
    const forgotForm = document.querySelector('.form-box.forgot');
    const registerLink = document.querySelector('.register-link');
    const loginLink = document.querySelector('.login-link');
    const forgotLink = document.querySelector('.forgot-link');
    const createAccountLink = document.querySelector('.create-link');
    const loginFormCon = document.querySelector('#login-form');
    const cartOverlay = document.getElementById("cart-overlay");
    const cartSidebar = document.getElementById("cart-sidebar");
    const cartButton = document.querySelector(".cart img");
    const closeCart = document.getElementById("close-cart");

    // Open cart overlay
    cartButton.addEventListener("click", () => {
        cartOverlay.style.display = "flex";
        loadCartItems();  // Load cart items via AJAX
    });

    // Close cart overlay
    closeCart.addEventListener("click", () => {
        cartOverlay.style.display = "none";
    });

    // Close cart on clicking outside the sidebar
    cartOverlay.addEventListener("click", (e) => {
        if (e.target === cartOverlay) {
            cartOverlay.style.display = "none";
        }
    });

    // Show login form when login button is clicked
    loginBtn.addEventListener("click", function () {
        loginOverlay.style.display = "block";
        loginFormContainer.style.display = "block";
        loginForm.style.display = "block";
        registerForm.style.display = "none";
        forgotForm.style.display = "none";
    });

    // Close login popup when close icon or overlay is clicked
    function closeLoginPopup() {
        loginFormContainer.style.display = 'none';
        loginOverlay.style.display = 'none';
    }

    closePopup.addEventListener('click', closeLoginPopup);
    loginOverlay.addEventListener('click', closeLoginPopup);

    // Close popup when pressing Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeLoginPopup();
        }
    });

    // Switch to register form
    registerLink.addEventListener("click", function (e) {
        e.preventDefault();
        loginForm.style.display = "none";
        registerForm.style.display = "block";
        forgotForm.style.display = "none";
    });

    // Switch back to login form from register
    loginLink.addEventListener("click", function (e) {
        e.preventDefault();
        registerForm.style.display = "none";
        loginForm.style.display = "block";
        forgotForm.style.display = "none";
    });

    // Switch to forgot password form
    forgotLink.addEventListener("click", function (e) {
        e.preventDefault();
        loginForm.style.display = "none";
        registerForm.style.display = "none";
        forgotForm.style.display = "block";
    });

    // Switch back to register from forgot password form
    createAccountLink.addEventListener("click", function (e) {
        e.preventDefault();
        forgotForm.style.display = "none";
        registerForm.style.display = "block";
    });
});
    // AJAX Login Handling
    loginFormCon.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(loginFormCon);
        fetch('../pages/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                window.location.href = '../pages/index.php';
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Handle registration form submission via AJAX
    registerForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const registerData = new FormData(registerForm);
        fetch('../pages/register.php', {  // Corrected to register.php
            method: 'POST',
            body: registerData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                toggleForms('login');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Handle forgot password form submission via AJAX
    forgotForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const forgotData = new FormData(forgotForm);
        fetch('../pages/forgot.php', {  // Corrected to forgot.php
            method: 'POST',
            body: forgotData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                toggleForms('login');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Function to load cart items or show "Nothing to see here"
    function loadCartItems() {
        $.ajax({
            url: 'cart.php?fetch_cart=true',
            method: 'GET',
            dataType: 'json',
            success: function(cart) {
                $('#cart-items').html('');
    
                if (cart.length > 0) {
                    cart.forEach(item => {
                        $('#cart-items').append(`
                            <div class="cart-item">
                            <img src="${item.image_url}" alt="${item.name}" class="cart-product-image">
                            <div class="cart-item-details">
                                <p>${item.name} - ${item.dates}</p>
                                <p>Price: ₹${item.price_per_day}</p>
                            </div>
                        </div>
                        `);
                    });
                } else {
                    $('#cart-items').html('<p>Nothing to see here</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Cart Fetch Error:", xhr.responseText);
            }
        });
    }    

    // Function to add item to cart
    function addToCart(productId, dates) {
        const cart = JSON.parse(sessionStorage.getItem('cart')) || [];
        
        // Check if the item already exists in the cart
        const existingItemIndex = cart.findIndex(item => item.product_id === productId && item.dates === dates);
        
        if (existingItemIndex === -1) { // If item doesn't exist, add it
            cart.push({ product_id: productId, dates: dates });
            sessionStorage.setItem('cart', JSON.stringify(cart));
            console.log('Product added to cart:', { product_id: productId, dates: dates });
        } else {
            console.log('This product is already in the cart.');
        }
    }
    

