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
    const checkoutBtn = document.getElementById("checkout-btn");
    
        if (checkoutBtn) {
            checkoutBtn.addEventListener("click", function () {
                let cart = JSON.parse(sessionStorage.getItem("cart")) || [];
    
                if (cart.length === 0) {
                    alert("Your cart is empty!");
                    return;
                }
    
                fetch("checkout.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ cart: cart }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert("Checkout failed: " + data.error);
                    } else {
                        openRazorpayPayment(data);
                    }
                })
                .catch(error => {
                    console.error("Error during checkout:", error);
                });
            });
        }
    
    function openRazorpayPayment(orderData) {
        var options = {
            "key": orderData.key,
            "amount": orderData.amount,
            "currency": orderData.currency,
            "name": "Your Rental Website",
            "description": "Secure Payment",
            "order_id": orderData.order_id,
            "handler": function (response) {
                alert("Payment successful! Payment ID: " + response.razorpay_payment_id);
                window.location.href = "success.php?payment_id=" + response.razorpay_payment_id;
            },
            "theme": {
                "color": "#3399cc"
            }
        };
    
        var rzp1 = new Razorpay(options);
        rzp1.open();
    }
    
    // Open cart overlay
    cartButton.addEventListener("click", () => {
        cartOverlay.style.display = "flex";
        loadCartItems();
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

    // Switch forms
    registerLink.addEventListener("click", (e) => {
        e.preventDefault();
        toggleForms('register');
    });

    loginLink.addEventListener("click", (e) => {
        e.preventDefault();
        toggleForms('login');
    });

    forgotLink.addEventListener("click", (e) => {
        e.preventDefault();
        toggleForms('forgot');
    });

    createAccountLink.addEventListener("click", (e) => {
        e.preventDefault();
        toggleForms('register');
    });

    function toggleForms(formType) {
        loginForm.style.display = formType === 'login' ? "block" : "none";
        registerForm.style.display = formType === 'register' ? "block" : "none";
        forgotForm.style.display = formType === 'forgot' ? "block" : "none";
    }

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
        fetch('../pages/register.php', {
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
        fetch('../pages/forgot.php', {
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
            success: function(response) {
                $('#cart-items').html('');

                if (response.cart_items.length > 0) {
                    let totalCartValue = response.cart_total;

                    response.cart_items.forEach(item => {
                        $('#cart-items').append(`
                            <div class="cart-item">
                                <img src="${item.image_url}" alt="${item.name}" class="cart-item-image">
                                <div class="cart-item-details">
                                    <p>${item.name} - ${item.dates}</p>
                                    <p>Price per day: ₹${item.price_per_day}</p>
                                    <p>Total Rent: ₹${item.rent}</p>
                                </div>
                            </div>
                        `);
                    });

                    $('#cart-items').append(`<p class="cart-total">Total Cart Value: ₹${totalCartValue}</p>`);
                } else {
                    $('#cart-items').html('<p>Nothing to see here</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Cart Fetch Error:", xhr.responseText);
            }
        });
    }
     
});

    // Function to add item to cart
function addToCart(productId, selectedDates) {
    $.ajax({
        url: 'cart.php',
        method: 'POST',
        data: {
            product_id: productId,
            dates: selectedDates
        },
        dataType: 'json',
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
}

